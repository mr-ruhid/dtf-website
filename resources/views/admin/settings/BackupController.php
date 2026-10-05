<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupController extends Controller
{
    protected string $backupDir;

    public function __construct()
    {
        $this->backupDir = storage_path('app/backups');
    }

    public function index()
    {
        $backups = $this->listBackups();

        return view('admin.settings.backup', compact('backups'));
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:database,files,full'],
            'name' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            if (!File::isDirectory($this->backupDir)) {
                File::makeDirectory($this->backupDir, 0755, true);
            }

            $timestamp = date('Y-m-d_H-i-s');
            $customName = !empty($data['name']) ? '_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $data['name']) : '';
            $fileName = 'backup_' . $data['type'] . '_' . $timestamp . $customName . '.zip';
            $zipPath = $this->backupDir . '/' . $fileName;

            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                return back()->withErrors(['error' => 'Failed to create backup file.']);
            }

            if (in_array($data['type'], ['database', 'full'])) {
                $this->addDatabaseToZip($zip);
            }

            if (in_array($data['type'], ['files', 'full'])) {
                $this->addFilesToZip($zip);
            }

            $zip->close();

            $size = File::size($zipPath);

            return back()->with('status', "Backup created: {$fileName} (" . $this->formatSize($size) . ")");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Backup failed: ' . $e->getMessage()]);
        }
    }

    public function download(string $fileName)
    {
        $path = $this->backupDir . '/' . basename($fileName);

        if (!File::exists($path)) {
            return back()->withErrors(['error' => 'Backup file not found.']);
        }

        return response()->download($path);
    }

    public function destroy(string $fileName)
    {
        $path = $this->backupDir . '/' . basename($fileName);

        if (!File::exists($path)) {
            return back()->withErrors(['error' => 'Backup file not found.']);
        }

        File::delete($path);

        return back()->with('status', 'Backup deleted successfully.');
    }

    public function restore(Request $request, string $fileName)
    {
        $path = $this->backupDir . '/' . basename($fileName);

        if (!File::exists($path)) {
            return back()->withErrors(['error' => 'Backup file not found.']);
        }

        $request->validate([
            'confirm' => ['required', 'accepted'],
        ]);

        try {
            $tempDir = storage_path('app/restore_temp');

            if (File::isDirectory($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            File::makeDirectory($tempDir, 0755, true);

            $zip = new ZipArchive();
            if ($zip->open($path) !== true) {
                return back()->withErrors(['error' => 'Failed to open backup file.']);
            }

            $zip->extractTo($tempDir);
            $zip->close();

            $restored = [];

            if (File::exists($tempDir . '/database.sql')) {
                $this->restoreDatabase($tempDir . '/database.sql');
                $restored[] = 'database';
            }

            if (File::isDirectory($tempDir . '/files')) {
                $this->restoreFiles($tempDir . '/files');
                $restored[] = 'files';
            }

            File::deleteDirectory($tempDir);

            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('cache:clear');

            $summary = !empty($restored) ? implode(', ', $restored) : 'nothing';

            return back()->with('status', "Restore completed: {$summary}.");

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Restore failed: ' . $e->getMessage()]);
        }
    }

    protected function addDatabaseToZip(ZipArchive $zip): void
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');

            if (File::exists($dbPath)) {
                $zip->addFile($dbPath, 'database.sql');
            }
            return;
        }

        if ($driver === 'mysql') {
            $sql = $this->dumpMysql();
            $zip->addFromString('database.sql', $sql);
            return;
        }
    }

    protected function dumpMysql(): string
    {
        $connection = config('database.connections.mysql');
        $tables = DB::select('SHOW TABLES');
        $dbName = $connection['database'];
        $key = 'Tables_in_' . $dbName;

        $output = "-- RJ SHOP Database Backup\n";
        $output .= "-- Generated: " . now()->toDateTimeString() . "\n\n";
        $output .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$key;

            $create = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $output .= "\n-- Table: {$tableName}\n";
            $output .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $output .= $create[0]->{'Create Table'} . ";\n\n";

            $rows = DB::table($tableName)->get();

            foreach ($rows as $row) {
                $values = array_map(function ($value) {
                    if (is_null($value)) return 'NULL';
                    return "'" . addslashes($value) . "'";
                }, (array) $row);

                $output .= "INSERT INTO `{$tableName}` VALUES (" . implode(',', $values) . ");\n";
            }

            $output .= "\n";
        }

        $output .= "SET FOREIGN_KEY_CHECKS=1;\n";

        return $output;
    }

    protected function addFilesToZip(ZipArchive $zip): void
    {
        $paths = [
            'app',
            'resources',
            'routes',
            'config',
            'database',
            'public',
        ];

        foreach ($paths as $path) {
            $fullPath = base_path($path);

            if (!File::exists($fullPath)) {
                continue;
            }

            foreach (File::allFiles($fullPath) as $file) {
                $relative = $file->getRelativePathname();

                if ($path === 'public' && (str_starts_with($relative, 'storage') || str_starts_with($relative, 'uploads'))) {
                    continue;
                }

                if ($path === 'database' && str_ends_with($relative, '.sqlite')) {
                    continue;
                }

                $zipPath = 'files/' . $path . '/' . $relative;
                $zip->addFile($file->getRealPath(), $zipPath);
            }
        }
    }

    protected function restoreDatabase(string $sqlPath): void
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');

            if (File::exists($dbPath)) {
                File::copy($dbPath, $dbPath . '.old_' . date('YmdHis'));
            }

            File::copy($sqlPath, $dbPath);
            return;
        }

        if ($driver === 'mysql') {
            $sql = File::get($sqlPath);

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $statements = array_filter(array_map('trim', explode(";\n", $sql)));

            foreach ($statements as $statement) {
                if (empty($statement) || str_starts_with($statement, '--')) {
                    continue;
                }

                try {
                    DB::statement($statement);
                } catch (\Exception $e) {
                    // Skip failed statements
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    protected function restoreFiles(string $filesDir): void
    {
        $paths = ['app', 'resources', 'routes', 'config', 'database', 'public'];

        foreach ($paths as $path) {
            $sourcePath = $filesDir . '/' . $path;
            $destPath = base_path($path);

            if (!File::exists($sourcePath)) {
                continue;
            }

            if ($path === 'public') {
                foreach (File::allFiles($sourcePath) as $file) {
                    $relative = $file->getRelativePathname();

                    if (str_starts_with($relative, 'storage') || str_starts_with($relative, 'uploads')) {
                        continue;
                    }

                    $target = $destPath . '/' . $relative;
                    $targetDir = dirname($target);

                    if (!File::isDirectory($targetDir)) {
                        File::makeDirectory($targetDir, 0755, true);
                    }

                    File::copy($file->getRealPath(), $target);
                }
                continue;
            }

            File::copyDirectory($sourcePath, $destPath);
        }
    }

    protected function listBackups(): array
    {
        if (!File::isDirectory($this->backupDir)) {
            return [];
        }

        $files = File::files($this->backupDir);
        $backups = [];

        foreach ($files as $file) {
            if ($file->getExtension() !== 'zip') {
                continue;
            }

            $name = $file->getFilename();
            $type = 'unknown';

            if (str_contains($name, '_database_')) $type = 'database';
            elseif (str_contains($name, '_files_')) $type = 'files';
            elseif (str_contains($name, '_full_')) $type = 'full';

            $backups[] = [
                'name' => $name,
                'type' => $type,
                'size' => $file->getSize(),
                'size_human' => $this->formatSize($file->getSize()),
                'created_at' => date('d M Y, H:i', $file->getMTime()),
                'timestamp' => $file->getMTime(),
            ];
        }

        usort($backups, fn($a, $b) => $b['timestamp'] - $a['timestamp']);

        return $backups;
    }

    protected function formatSize(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}

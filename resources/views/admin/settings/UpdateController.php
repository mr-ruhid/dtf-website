<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class UpdateController extends Controller
{
    protected array $protectedPaths = [
        '.env',
        '.env.example',
        'storage',
        'public/storage',
        'vendor',
        'node_modules',
        '.git',
        '.gitignore',
        'database/database.sqlite',
        'composer.lock',
    ];

    protected array $updateablePaths = [
        'app',
        'bootstrap',
        'config',
        'database',
        'public',
        'resources',
        'routes',
        'tests',
    ];

    public function install(Request $request)
    {
        $request->validate([
            'update_file' => ['required', 'file', 'mimes:zip', 'max:102400'],
        ]);

        $startTime = microtime(true);

        try {
            $updateDir = storage_path('app/updates');
            $extractDir = storage_path('app/updates/extracted');

            if (File::isDirectory($updateDir)) {
                File::deleteDirectory($updateDir);
            }
            File::makeDirectory($updateDir, 0755, true);
            File::makeDirectory($extractDir, 0755, true);

            $zipPath = $request->file('update_file')->storeAs('updates', 'update_' . date('YmdHis') . '.zip');
            $fullZipPath = storage_path('app/' . $zipPath);

            if (!class_exists('ZipArchive')) {
                return back()->withErrors(['error' => 'ZipArchive extension is not enabled on this server. Please enable it in PHP settings.']);
            }

            $zip = new ZipArchive();
            if ($zip->open($fullZipPath) !== true) {
                return back()->withErrors(['error' => 'Failed to open the update ZIP file.']);
            }

            $zip->extractTo($extractDir);
            $zip->close();

            $sourceRoot = $this->findSourceRoot($extractDir);

            if (!$sourceRoot) {
                return back()->withErrors(['error' => 'Invalid update package structure.']);
            }

            $backupPath = $this->createBackup();

            $copiedFiles = $this->copyFiles($sourceRoot);
            $addedFiles = $copiedFiles['copied'];
            $skippedFiles = $copiedFiles['skipped'];

            $output = '';
            try {
                Artisan::call('migrate', ['--force' => true]);
                $output .= Artisan::output();
            } catch (\Exception $e) {
                $output .= "\nMigration error: " . $e->getMessage();
            }

            Artisan::call('view:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('cache:clear');

            $this->cleanup($updateDir);

            $duration = round(microtime(true) - $startTime, 2);

            return back()->with('status', "Update installed successfully in {$duration}s. Files updated: {$addedFiles}. Protected files skipped: {$skippedFiles}. Backup: " . basename($backupPath));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        }
    }

    protected function findSourceRoot(string $extractDir): ?string
    {
        $items = File::directories($extractDir);
        $files = File::files($extractDir);

        if (count($files) > 0 && File::exists($extractDir . '/composer.json')) {
            return $extractDir;
        }

        if (count($items) === 1 && File::exists($items[0] . '/composer.json')) {
            return $items[0];
        }

        foreach ($items as $item) {
            if (File::exists($item . '/composer.json')) {
                return $item;
            }
        }

        return null;
    }

    protected function createBackup(): string
    {
        $backupDir = storage_path('app/backups');
        if (!File::isDirectory($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $backupName = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
        $backupPath = $backupDir . '/' . $backupName;

        $zip = new ZipArchive();
        if ($zip->open($backupPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return $backupPath;
        }

        foreach ($this->updateablePaths as $path) {
            $fullPath = base_path($path);
            if (File::exists($fullPath)) {
                $this->addToZip($zip, $fullPath, $path);
            }
        }

        $zip->close();

        return $backupPath;
    }

    protected function addToZip(ZipArchive $zip, string $path, string $basePath): void
    {
        if (File::isFile($path)) {
            $zip->addFile($path, $basePath);
        } elseif (File::isDirectory($path)) {
            foreach (File::allFiles($path) as $file) {
                $relativePath = $basePath . '/' . $file->getRelativePathname();
                $zip->addFile($file->getRealPath(), $relativePath);
            }
        }
    }

    protected function copyFiles(string $sourceRoot): array
    {
        $copied = 0;
        $skipped = 0;

        foreach ($this->updateablePaths as $path) {
            $sourcePath = $sourceRoot . '/' . $path;
            $destPath = base_path($path);

            if (!File::exists($sourcePath)) {
                continue;
            }

            if ($path === 'public') {
                $result = $this->copyPublicFiles($sourcePath, $destPath);
                $copied += $result['copied'];
                $skipped += $result['skipped'];
                continue;
            }

            if (File::isDirectory($sourcePath)) {
                File::copyDirectory($sourcePath, $destPath);
                $copied += count(File::allFiles($sourcePath));
            }
        }

        return ['copied' => $copied, 'skipped' => $skipped];
    }

    protected function copyPublicFiles(string $sourcePath, string $destPath): array
    {
        $copied = 0;
        $skipped = 0;

        foreach (File::allFiles($sourcePath) as $file) {
            $relative = $file->getRelativePathname();

            if (str_starts_with($relative, 'storage/') || str_starts_with($relative, 'storage\\')) {
                $skipped++;
                continue;
            }

            if (str_starts_with($relative, 'uploads/') || str_starts_with($relative, 'uploads\\')) {
                $skipped++;
                continue;
            }

            $targetPath = $destPath . '/' . $relative;
            $targetDir = dirname($targetPath);

            if (!File::isDirectory($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            File::copy($file->getRealPath(), $targetPath);
            $copied++;
        }

        return ['copied' => $copied, 'skipped' => $skipped];
    }

    protected function cleanup(string $dir): void
    {
        try {
            if (File::isDirectory($dir)) {
                File::deleteDirectory($dir);
            }
        } catch (\Exception $e) {
            // Silent
        }
    }
}

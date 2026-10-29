<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class UpdateController extends Controller
{
    private string $repo = 'mr-ruhid/dtf-website';
    private string $currentVersion = '1.1.2';

    public function check()
    {
        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
        ])->get("https://api.github.com/repos/{$this->repo}/releases/latest");

        if ($response->failed()) {
            return response()->json(['error' => 'GitHub API request failed'], 500);
        }

        $release = $response->json();
        $latestVersion = ltrim($release['tag_name'] ?? '', 'v');

        return response()->json([
            'current_version' => $this->currentVersion,
            'latest_version' => $latestVersion,
            'download_url' => $release['zipball_url'] ?? '',
            'release_notes' => $release['body'] ?? '',
            'is_update_available' => version_compare($latestVersion, $this->currentVersion, '>'),
        ]);
    }

    public function install(Request $request)
    {
        $request->validate([
            'download_url' => 'required|url',
        ]);

        $downloadUrl = $request->input('download_url');
        $zipPath = storage_path('app/update-' . time() . '.zip');
        $extractPath = storage_path('app/update-temp-' . time());

        try {
            $response = Http::withOptions(['stream' => true])->timeout(300)->get($downloadUrl);
            if ($response->failed()) {
                return back()->withErrors(['error' => 'Failed to download update package from GitHub.']);
            }
            file_put_contents($zipPath, $response->body());

            $zip = new ZipArchive;
            if ($zip->open($zipPath) !== true) {
                return back()->withErrors(['error' => 'Failed to open downloaded ZIP file.']);
            }
            File::ensureDirectoryExists($extractPath);
            $zip->extractTo($extractPath);
            $zip->close();

            $dirs = File::directories($extractPath);
            if (empty($dirs)) {
                return back()->withErrors(['error' => 'Invalid update package structure.']);
            }
            $source = $dirs[0];

            $this->backupCurrentCode();
            $this->copyDirectory($source, base_path());

            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('optimize:clear');

            return back()->with('status', 'System updated successfully to version ' . $this->currentVersion);
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
        } finally {
            File::delete($zipPath);
            File::deleteDirectory($extractPath);
        }
    }

    private function backupCurrentCode(): void
    {
        $backupDir = storage_path('app/backups/update-' . date('Y-m-d-His'));
        File::ensureDirectoryExists($backupDir);

        foreach (['app', 'routes', 'resources', 'config', 'database/migrations'] as $dir) {
            $source = base_path($dir);
            if (File::isDirectory($source)) {
                File::copyDirectory($source, $backupDir . '/' . $dir);
            }
        }
    }

    private function copyDirectory(string $source, string $destination): void
    {
        $exclude = [
            '.env',
            'storage',
            'vendor',
            'node_modules',
            'public/storage',
            'database/database.sqlite',
            '.git',
        ];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $item->getPathname());
            $relativePath = str_replace('\\', '/', $relativePath);

            $skip = false;
            foreach ($exclude as $ex) {
                if ($relativePath === $ex || str_starts_with($relativePath, $ex . '/')) {
                    $skip = true;
                    break;
                }
            }
            if ($skip) {
                continue;
            }

            $target = $destination . DIRECTORY_SEPARATOR . $relativePath;

            if ($item->isDir()) {
                File::ensureDirectoryExists($target);
            } else {
                File::ensureDirectoryExists(dirname($target));
                File::copy($item->getPathname(), $target);
            }
        }
    }
}

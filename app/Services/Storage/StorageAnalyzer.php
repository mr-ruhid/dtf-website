<?php

namespace App\Services\Storage;

use Illuminate\Support\Facades\Storage;

class StorageAnalyzer
{
    protected string $disk;

    public function __construct()
    {
        $this->disk = config('storage-manager.disk', 'public');
    }

    public function overview(): array
    {
        $folders = config('storage-manager.folders', []);

        $items = [];
        $totalSize = 0;
        $totalFiles = 0;

        foreach ($folders as $key => $meta) {
            $path = $meta['path'];
            $stat = $this->folderStats($path);

            $items[] = [
                'key' => $key,
                'path' => $path,
                'label' => $meta['label'] ?? ucfirst($key),
                'icon' => $meta['icon'] ?? 'fa-folder',
                'protected' => (bool) ($meta['protected'] ?? false),
                'size' => $stat['size'],
                'size_human' => $this->humanSize($stat['size']),
                'files' => $stat['files'],
                'exists' => $stat['exists'],
            ];

            $totalSize += $stat['size'];
            $totalFiles += $stat['files'];
        }

        usort($items, fn($a, $b) => $b['size'] <=> $a['size']);

        return [
            'disk' => $this->disk,
            'total_size' => $totalSize,
            'total_size_human' => $this->humanSize($totalSize),
            'total_files' => $totalFiles,
            'folders' => $items,
            'disk_free' => $this->diskFree(),
            'disk_total' => $this->diskTotal(),
        ];
    }

    public function folderStats(string $path): array
    {
        $disk = Storage::disk($this->disk);

        if (!$disk->exists($path)) {
            return ['size' => 0, 'files' => 0, 'exists' => false];
        }

        $size = 0;
        $files = 0;

        try {
            foreach ($disk->allFiles($path) as $file) {
                $files++;
                try {
                    $size += (int) $disk->size($file);
                } catch (\Throwable $e) {
                    // skip
                }
            }
        } catch (\Throwable $e) {
            return ['size' => 0, 'files' => 0, 'exists' => false];
        }

        return ['size' => $size, 'files' => $files, 'exists' => true];
    }

    public function largestFiles(int $limit = 10): array
    {
        $folders = config('storage-manager.folders', []);
        $disk = Storage::disk($this->disk);
        $out = [];

        foreach ($folders as $key => $meta) {
            $path = $meta['path'];

            if (!$disk->exists($path)) {
                continue;
            }

            try {
                foreach ($disk->allFiles($path) as $file) {
                    try {
                        $size = (int) $disk->size($file);
                        $out[] = [
                            'path' => $file,
                            'name' => basename($file),
                            'folder_key' => $key,
                            'folder_label' => $meta['label'] ?? $key,
                            'size' => $size,
                            'size_human' => $this->humanSize($size),
                            'modified_at' => $disk->lastModified($file),
                        ];
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        usort($out, fn($a, $b) => $b['size'] <=> $a['size']);

        return array_slice($out, 0, $limit);
    }

    public function recentFiles(int $hours = 24, int $limit = 20): array
    {
        $folders = config('storage-manager.folders', []);
        $disk = Storage::disk($this->disk);
        $since = now()->subHours($hours)->timestamp;
        $out = [];

        foreach ($folders as $key => $meta) {
            $path = $meta['path'];

            if (!$disk->exists($path)) {
                continue;
            }

            try {
                foreach ($disk->allFiles($path) as $file) {
                    try {
                        $modified = (int) $disk->lastModified($file);
                        if ($modified < $since) continue;

                        $size = (int) $disk->size($file);

                        $out[] = [
                            'path' => $file,
                            'name' => basename($file),
                            'folder_key' => $key,
                            'folder_label' => $meta['label'] ?? $key,
                            'size' => $size,
                            'size_human' => $this->humanSize($size),
                            'modified_at' => $modified,
                        ];
                    } catch (\Throwable $e) {
                        continue;
                    }
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        usort($out, fn($a, $b) => $b['modified_at'] <=> $a['modified_at']);

        return array_slice($out, 0, $limit);
    }

    public function humanSize(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1024 * 1024) return round($bytes / 1024, 1) . ' KB';
        if ($bytes < 1024 * 1024 * 1024) return round($bytes / 1024 / 1024, 2) . ' MB';
        return round($bytes / 1024 / 1024 / 1024, 2) . ' GB';
    }

    protected function diskFree(): ?int
    {
        try {
            $root = storage_path('app/' . $this->disk);
            $free = @disk_free_space($root);
            return $free !== false ? (int) $free : null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function diskTotal(): ?int
    {
        try {
            $root = storage_path('app/' . $this->disk);
            $total = @disk_total_space($root);
            return $total !== false ? (int) $total : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}

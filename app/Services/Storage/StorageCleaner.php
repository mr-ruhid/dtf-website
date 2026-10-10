<?php

namespace App\Services\Storage;

use App\Models\Order;
use App\Models\OrderDesign;
use App\Models\ProductImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageCleaner
{
    protected string $disk;

    protected StorageAnalyzer $analyzer;

    public function __construct(StorageAnalyzer $analyzer)
    {
        $this->disk = config('storage-manager.disk', 'public');
        $this->analyzer = $analyzer;
    }

    public function cleanTmpUploads(int $hours = 24): array
    {
        return $this->cleanTmpFolder('tmp/uploads', $hours);
    }

    public function cleanTmpZips(int $hours = 6): array
    {
        return $this->cleanTmpFolder('tmp-zips', $hours);
    }

    protected function cleanTmpFolder(string $path, int $hours): array
    {
        $disk = Storage::disk($this->disk);

        if (!$disk->exists($path)) {
            return ['deleted' => 0, 'freed' => 0, 'freed_human' => '0 B'];
        }

        $cutoff = now()->subHours($hours)->timestamp;
        $deleted = 0;
        $freed = 0;

        try {
            foreach ($disk->allFiles($path) as $file) {
                try {
                    $modified = (int) $disk->lastModified($file);

                    if ($modified < $cutoff) {
                        $size = (int) $disk->size($file);
                        $disk->delete($file);
                        $deleted++;
                        $freed += $size;
                    }
                } catch (\Throwable $e) {
                    Log::warning('Cleanup failed: ' . $file . ' — ' . $e->getMessage());
                }
            }

            $this->removeEmptyDirectories($path);
        } catch (\Throwable $e) {
            Log::error('Tmp cleanup failed: ' . $e->getMessage());
        }

        return [
            'deleted' => $deleted,
            'freed' => $freed,
            'freed_human' => $this->analyzer->humanSize($freed),
        ];
    }

    public function cleanOldOrderDesigns(int $ageDays = 180): array
    {
        $protectedStatuses = config('storage-manager.cleanup.order_designs_protected_statuses', []);
        $cutoff = now()->subDays($ageDays);
        $disk = Storage::disk($this->disk);

        $deleted = 0;
        $freed = 0;
        $skipped = 0;

        $designs = OrderDesign::with('item.order')
            ->where('created_at', '<', $cutoff)
            ->get();

        foreach ($designs as $design) {
            $order = $design->item?->order;

            if (!$order) {
                $skipped++;
                continue;
            }

            if (in_array($order->status, $protectedStatuses, true)) {
                $skipped++;
                continue;
            }

            if (!$design->file_path || Str::startsWith($design->file_path, 'http')) {
                $skipped++;
                continue;
            }

            try {
                if ($disk->exists($design->file_path)) {
                    $size = (int) $disk->size($design->file_path);
                    $disk->delete($design->file_path);
                    $freed += $size;
                }

                $design->delete();
                $deleted++;
            } catch (\Throwable $e) {
                Log::warning('Order design cleanup failed: ' . $design->id . ' — ' . $e->getMessage());
            }
        }

        return [
            'deleted' => $deleted,
            'skipped' => $skipped,
            'freed' => $freed,
            'freed_human' => $this->analyzer->humanSize($freed),
        ];
    }

    public function findOrphanFiles(): array
    {
        $disk = Storage::disk($this->disk);
        $models = config('storage-manager.cleanup.orphan_scan_models', []);

        $referenced = [];

        foreach ($models as $modelClass => $column) {
            if (!class_exists($modelClass)) {
                continue;
            }

            try {
                $values = $modelClass::query()
                    ->whereNotNull($column)
                    ->pluck($column)
                    ->filter()
                    ->map(fn($v) => is_string($v) ? trim($v) : null)
                    ->filter()
                    ->values()
                    ->all();

                foreach ($values as $v) {
                    $referenced[$v] = true;
                }
            } catch (\Throwable $e) {
                Log::warning('Orphan scan failed for ' . $modelClass . ': ' . $e->getMessage());
            }
        }

        $scannedFolders = [
            'orders/designs',
            'products',
            'categories',
        ];

        $orphans = [];

        foreach ($scannedFolders as $folder) {
            if (!$disk->exists($folder)) {
                continue;
            }

            try {
                foreach ($disk->allFiles($folder) as $file) {
                    if (isset($referenced[$file])) {
                        continue;
                    }

                    try {
                        $size = (int) $disk->size($file);
                        $orphans[] = [
                            'path' => $file,
                            'name' => basename($file),
                            'folder' => $folder,
                            'size' => $size,
                            'size_human' => $this->analyzer->humanSize($size),
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

        usort($orphans, fn($a, $b) => $b['size'] <=> $a['size']);

        return $orphans;
    }

    public function deleteOrphanFiles(array $paths): array
    {
        $disk = Storage::disk($this->disk);
        $deleted = 0;
        $freed = 0;
        $errors = [];

        $allowedFolders = [
            'orders/designs',
            'products',
            'categories',
        ];

        foreach ($paths as $path) {
            $path = trim((string) $path);

            if ($path === '' || str_contains($path, '..')) {
                continue;
            }

            $allowed = false;

            foreach ($allowedFolders as $folder) {
                if (str_starts_with($path, $folder . '/')) {
                    $allowed = true;
                    break;
                }
            }

            if (!$allowed) {
                $errors[] = $path . ' — folder not allowed';
                continue;
            }

            try {
                if ($disk->exists($path)) {
                    $size = (int) $disk->size($path);
                    $disk->delete($path);
                    $deleted++;
                    $freed += $size;
                }
            } catch (\Throwable $e) {
                $errors[] = $path . ' — ' . $e->getMessage();
            }
        }

        return [
            'deleted' => $deleted,
            'freed' => $freed,
            'freed_human' => $this->analyzer->humanSize($freed),
            'errors' => $errors,
        ];
    }

    public function deleteFile(string $path): array
    {
        $disk = Storage::disk($this->disk);
        $path = trim($path);

        if ($path === '' || str_contains($path, '..')) {
            return ['success' => false, 'message' => 'Invalid path'];
        }

        $allowedFolders = array_column(config('storage-manager.folders', []), 'path');

        $allowed = false;

        foreach ($allowedFolders as $folder) {
            if (str_starts_with($path, $folder . '/')) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            return ['success' => false, 'message' => 'Folder not managed'];
        }

        if (!$disk->exists($path)) {
            return ['success' => false, 'message' => 'File not found'];
        }

        try {
            $size = (int) $disk->size($path);
            $disk->delete($path);

            return [
                'success' => true,
                'freed' => $size,
                'freed_human' => $this->analyzer->humanSize($size),
            ];
        } catch (\Throwable $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    protected function removeEmptyDirectories(string $path): void
    {
        $disk = Storage::disk($this->disk);

        try {
            foreach ($disk->directories($path) as $dir) {
                $this->removeEmptyDirectories($dir);
            }

            $files = $disk->files($path);
            $subdirs = $disk->directories($path);

            if (empty($files) && empty($subdirs)) {
                $disk->deleteDirectory($path);
            }
        } catch (\Throwable $e) {
            // ignore
        }
    }
}

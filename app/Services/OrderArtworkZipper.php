<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderArtworkZipper
{
    public function build(Order $order): ?string
    {
        if (!class_exists('\ZipArchive')) {
            logger()->warning('ZIP extension not available');
            return null;
        }

        $order->loadMissing(['items.designs']);

        $tmpDir = storage_path('app/tmp-zips');

        if (!is_dir($tmpDir)) {
            @mkdir($tmpDir, 0755, true);
        }

        $zipPath = $tmpDir . '/order-' . $order->order_number . '-' . Str::random(6) . '.zip';

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            logger()->warning('Could not create zip at ' . $zipPath);
            return null;
        }

        $added = 0;
        $usedNames = [];

        foreach ($order->items as $index => $item) {
            $folder = 'Item-' . ($index + 1);

            foreach ($item->designs as $design) {
                if (!$design->file_path) {
                    continue;
                }

                if (str_starts_with($design->file_path, 'http')) {
                    continue;
                }

                if (!Storage::disk('public')->exists($design->file_path)) {
                    continue;
                }

                $absPath = Storage::disk('public')->path($design->file_path);

                if (!is_file($absPath) || !is_readable($absPath)) {
                    continue;
                }

                $fileName = $this->uniqueName(
                    $usedNames,
                    $folder,
                    $design->original_name ?: basename($design->file_path)
                );

                if ($zip->addFile($absPath, $folder . '/' . $fileName)) {
                    $added++;
                }
            }
        }

        $zip->close();

        if ($added === 0) {
            @unlink($zipPath);
            return null;
        }

        return $zipPath;
    }

    public function cleanup(?string $zipPath): void
    {
        if (!$zipPath) {
            return;
        }

        if (is_file($zipPath)) {
            @unlink($zipPath);
        }
    }

    protected function uniqueName(array &$usedNames, string $folder, string $rawName): string
    {
        $rawName = str_replace(['/', '\\', "\0"], '_', (string) $rawName);

        if ($rawName === '' || $rawName === '.') {
            $rawName = 'artwork.png';
        }

        $ext = pathinfo($rawName, PATHINFO_EXTENSION);
        $base = pathinfo($rawName, PATHINFO_FILENAME);

        if ($base === '') {
            $base = 'artwork';
        }

        if (strlen($base) > 80) {
            $base = substr($base, 0, 80);
        }

        $candidate = $base . ($ext !== '' ? '.' . $ext : '');

        $key = $folder . '/' . $candidate;
        $i = 1;

        while (isset($usedNames[$key])) {
            $candidate = $base . '-' . $i . ($ext !== '' ? '.' . $ext : '');
            $key = $folder . '/' . $candidate;
            $i++;
        }

        $usedNames[$key] = true;

        return $candidate;
    }
}

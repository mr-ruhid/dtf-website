<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDesign;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderDownloadController extends Controller
{
    public function page(string $token)
    {
        $order = Order::with(['items.designs'])
            ->where('tracking_token', $token)
            ->firstOrFail();

        if (!$order->is_download_active) {
            return view('theme.rjshop-theme.staticpages.download-expired', [
                'order' => $order,
            ]);
        }

        $groups = $this->buildGroups($order);

        return view('theme.rjshop-theme.staticpages.download-page', [
            'order' => $order,
            'groups' => $groups,
            'totalFiles' => collect($groups)->sum(fn($g) => count($g['files'])),
        ]);
    }

    public function file(string $token, int $designId)
    {
        $order = Order::where('tracking_token', $token)->firstOrFail();

        if (!$order->is_download_active) {
            abort(410, 'Download link expired.');
        }

        $design = OrderDesign::whereHas('item', function ($q) use ($order) {
            $q->where('order_id', $order->id);
        })->findOrFail($designId);

        if (!$design->exists) {
            abort(404, 'File not found.');
        }

        $path = Storage::disk('public')->path($design->file_path);

        if (!is_file($path) || !is_readable($path)) {
            abort(404, 'File not found on disk.');
        }

        $name = $design->original_name ?: basename($design->file_path);

        return response()->download($path, $name, [
            'Content-Type' => $design->mime_type ?: 'application/octet-stream',
        ]);
    }

    public function zip(string $token): StreamedResponse
    {
        $order = Order::with(['items.designs'])
            ->where('tracking_token', $token)
            ->firstOrFail();

        if (!$order->is_download_active) {
            abort(410, 'Download link expired.');
        }

        $groups = $this->buildGroups($order);

        $totalFiles = collect($groups)->sum(fn($g) => count($g['files']));

        if ($totalFiles === 0) {
            abort(404, 'No files available for this order.');
        }

        $zipName = $order->order_number . '-artwork.zip';

        return response()->streamDownload(function () use ($order, $groups) {
            $tmpDir = storage_path('app/tmp-zips');

            if (!is_dir($tmpDir)) {
                @mkdir($tmpDir, 0755, true);
            }

            $tmpZip = $tmpDir . '/dl-' . $order->id . '-' . Str::random(8) . '.zip';

            $zip = new \ZipArchive();

            if ($zip->open($tmpZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                echo '';
                return;
            }

            $usedNames = [];

            foreach ($groups as $group) {
                $folder = $group['folder'];

                foreach ($group['files'] as $file) {
                    if (!isset($file['path']) || !$file['path']) continue;

                    $absPath = Storage::disk('public')->path($file['path']);

                    if (!is_file($absPath) || !is_readable($absPath)) continue;

                    $entryName = $this->uniqueZipName($usedNames, $folder, $file['name']);

                    $zip->addFile($absPath, $folder . '/' . $entryName);
                }
            }

            $zip->close();

            if (is_file($tmpZip)) {
                readfile($tmpZip);
                @unlink($tmpZip);
            }
        }, $zipName, [
            'Content-Type' => 'application/zip',
        ]);
    }

    protected function buildGroups(Order $order): array
    {
        $groups = [];

        foreach ($order->items as $index => $item) {
            $files = [];

            $designs = $item->designs;

            $composite = $designs->first();
            $sources = $designs->slice(1)->values();

            if ($composite && $composite->exists && !$composite->is_expired) {
                $files[] = [
                    'id' => $composite->id,
                    'name' => $composite->original_name ?: basename($composite->file_path),
                    'path' => $composite->file_path,
                    'size' => $composite->file_size_human,
                    'mime' => $composite->mime_type,
                    'kind' => 'composite',
                ];
            }

            foreach ($sources as $source) {
                if (!$source->exists || $source->is_expired) continue;

                $files[] = [
                    'id' => $source->id,
                    'name' => $source->original_name ?: basename($source->file_path),
                    'path' => $source->file_path,
                    'size' => $source->file_size_human,
                    'mime' => $source->mime_type,
                    'kind' => 'source',
                ];
            }

            if (empty($files)) continue;

            $groups[] = [
                'folder' => 'Item-' . ($index + 1),
                'title' => $item->product_name,
                'qty' => $item->quantity,
                'size_label' => ($item->print_width && $item->print_height)
                    ? (rtrim(rtrim(number_format((float) $item->print_width, 2, '.', ''), '0'), '.')
                        . ' × '
                        . rtrim(rtrim(number_format((float) $item->print_height, 2, '.', ''), '0'), '.')
                        . ' in')
                    : null,
                'files' => $files,
            ];
        }

        return $groups;
    }

    protected function uniqueZipName(array &$used, string $folder, string $rawName): string
    {
        $rawName = str_replace(['/', '\\', "\0"], '_', (string) $rawName);

        if ($rawName === '' || $rawName === '.') {
            $rawName = 'file.png';
        }

        $ext = pathinfo($rawName, PATHINFO_EXTENSION);
        $base = pathinfo($rawName, PATHINFO_FILENAME);

        if ($base === '') $base = 'file';
        if (strlen($base) > 80) $base = substr($base, 0, 80);

        $candidate = $base . ($ext !== '' ? '.' . $ext : '');
        $key = $folder . '/' . $candidate;
        $i = 1;

        while (isset($used[$key])) {
            $candidate = $base . '-' . $i . ($ext !== '' ? '.' . $ext : '');
            $key = $folder . '/' . $candidate;
            $i++;
        }

        $used[$key] = true;

        return $candidate;
    }
}

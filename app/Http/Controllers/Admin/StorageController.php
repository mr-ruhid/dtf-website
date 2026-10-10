<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Storage\StorageAnalyzer;
use App\Services\Storage\StorageCleaner;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    protected StorageAnalyzer $analyzer;

    protected StorageCleaner $cleaner;

    public function __construct(StorageAnalyzer $analyzer, StorageCleaner $cleaner)
    {
        $this->analyzer = $analyzer;
        $this->cleaner = $cleaner;
    }

    public function index()
    {
        $overview = $this->analyzer->overview();
        $largest = $this->analyzer->largestFiles(10);
        $recent = $this->analyzer->recentFiles(24, 20);
        $orphans = $this->cleaner->findOrphanFiles();

        return view('admin.storage.index', compact(
            'overview',
            'largest',
            'recent',
            'orphans'
        ));
    }

    public function cleanTmp(Request $request)
    {
        $hours = (int) $request->input('hours', 24);
        $hours = max(1, min(720, $hours));

        $uploads = $this->cleaner->cleanTmpUploads($hours);
        $zips = $this->cleaner->cleanTmpZips($hours);

        $totalDeleted = $uploads['deleted'] + $zips['deleted'];
        $totalFreed = $uploads['freed'] + $zips['freed'];

        return back()->with('status', sprintf(
            'Temp cleanup done — %d file(s) deleted, %s freed.',
            $totalDeleted,
            $this->analyzer->humanSize($totalFreed)
        ));
    }

    public function cleanOrders(Request $request)
    {
        $days = (int) $request->input('days', 180);
        $days = max(30, min(3650, $days));

        $result = $this->cleaner->cleanOldOrderDesigns($days);

        return back()->with('status', sprintf(
            'Old order designs cleanup — %d deleted, %d skipped, %s freed.',
            $result['deleted'],
            $result['skipped'],
            $result['freed_human']
        ));
    }

    public function deleteOrphans(Request $request)
    {
        $paths = $request->input('paths', []);

        if (!is_array($paths) || empty($paths)) {
            return back()->withErrors(['error' => 'No files selected.']);
        }

        $result = $this->cleaner->deleteOrphanFiles($paths);

        $msg = sprintf(
            'Orphan cleanup — %d deleted, %s freed.',
            $result['deleted'],
            $result['freed_human']
        );

        if (!empty($result['errors'])) {
            $msg .= ' ' . count($result['errors']) . ' error(s).';
        }

        return back()->with('status', $msg);
    }

    public function deleteFile(Request $request)
    {
        $path = (string) $request->input('path', '');

        $result = $this->cleaner->deleteFile($path);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Delete failed',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'freed' => $result['freed'],
            'freed_human' => $result['freed_human'],
        ]);
    }
}

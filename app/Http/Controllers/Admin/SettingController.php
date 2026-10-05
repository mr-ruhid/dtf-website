<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return redirect()->route('admin.settings.general');
    }

    public function general()
    {
        return view('admin.settings.general');
    }

    public function about()
    {
        return view('admin.settings.about');
    }

    public function update()
    {
        return view('admin.settings.update');
    }

    public function backup()
    {
        return view('admin.settings.backup');
    }

    public function cache()
    {
        $cacheDriver = config('cache.default');

        $sizes = [
            'application' => $this->folderSize(storage_path('framework/cache')),
            'views' => $this->folderSize(storage_path('framework/views')),
            'sessions' => $this->folderSize(storage_path('framework/sessions')),
            'logs' => $this->folderSize(storage_path('logs')),
            'routes' => $this->folderSize(base_path('bootstrap/cache')),
        ];

        $checks = [
            'config_cached' => app()->configurationIsCached(),
            'routes_cached' => app()->routesAreCached(),
            'events_cached' => app()->eventsAreCached(),
            'views_cached' => count(File::glob(storage_path('framework/views/*.php'))) > 0,
        ];

        return view('admin.settings.cache', compact('sizes', 'checks', 'cacheDriver'));
    }

    public function clearCache(string $type)
    {
        switch ($type) {
            case 'application':
                Cache::flush();
                $message = 'Application cache cleared.';
                break;
            case 'views':
                Artisan::call('view:clear');
                $message = 'Compiled views cleared.';
                break;
            case 'config':
                Artisan::call('config:clear');
                $message = 'Configuration cache cleared.';
                break;
            case 'routes':
                Artisan::call('route:clear');
                $message = 'Route cache cleared.';
                break;
            case 'sessions':
                foreach (File::glob(storage_path('framework/sessions/*')) as $file) {
                    if (is_file($file)) @unlink($file);
                }
                $message = 'Sessions cleared.';
                break;
            case 'logs':
                foreach (File::glob(storage_path('logs/*.log')) as $file) {
                    if (is_file($file)) @unlink($file);
                }
                $message = 'Log files cleared.';
                break;
            case 'all':
                Cache::flush();
                Artisan::call('view:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('cache:clear');
                foreach (File::glob(storage_path('framework/sessions/*')) as $file) {
                    if (is_file($file)) @unlink($file);
                }
                $message = 'All caches cleared successfully.';
                break;
            default:
                return back()->withErrors(['error' => 'Unknown cache type.']);
        }

        return back()->with('status', $message);
    }

    protected function folderSize(string $path): int
    {
        if (!File::isDirectory($path)) {
            return 0;
        }

        $size = 0;
        foreach (File::allFiles($path) as $file) {
            $size += $file->getSize();
        }
        return $size;
    }
}

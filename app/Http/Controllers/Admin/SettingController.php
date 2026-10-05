<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
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
        $keys = [
            'site_name',
            'site_tagline',
            'site_description',
            'site_email',
            'site_phone',
            'site_address',
            'site_logo',
            'site_favicon',
            'site_currency',
            'site_language',
            'site_timezone',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'linkedin_url',
            'tiktok_url',
            'maintenance_mode',
            'maintenance_message',
        ];

        $settings = Setting::getMany($keys);

        return view('admin.settings.general', compact('settings'));
    }

    public function generalUpdate(Request $request)
    {
        $data = $request->validate([
            'site_name' => ['required', 'string', 'max:100'],
            'site_tagline' => ['nullable', 'string', 'max:150'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'site_email' => ['nullable', 'email', 'max:150'],
            'site_phone' => ['nullable', 'string', 'max:30'],
            'site_address' => ['nullable', 'string', 'max:255'],
            'site_currency' => ['nullable', 'string', 'max:10'],
            'site_language' => ['nullable', 'string', 'max:10'],
            'site_timezone' => ['nullable', 'string', 'max:100'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'site_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'site_favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,ico,svg', 'max:1024'],
        ]);

        $data['maintenance_mode'] = $request->boolean('maintenance_mode') ? '1' : '0';

        foreach (['site_logo', 'site_favicon'] as $fileKey) {
            if ($request->hasFile($fileKey)) {
                $old = Setting::get($fileKey);
                if ($old && !str_starts_with($old, 'http')) {
                    Storage::disk('public')->delete($old);
                }
                $data[$fileKey] = $request->file($fileKey)->store('settings', 'public');
            }
        }

        Setting::setMany($data);

        return back()->with('status', 'General settings updated successfully.')->with('tab', 'general');
    }

    public function generalRemoveLogo()
    {
        $old = Setting::get('site_logo');
        if ($old && !str_starts_with($old, 'http')) {
            Storage::disk('public')->delete($old);
        }
        Setting::set('site_logo', null);

        return back()->with('status', 'Logo removed.')->with('tab', 'general');
    }

    public function generalRemoveFavicon()
    {
        $old = Setting::get('site_favicon');
        if ($old && !str_starts_with($old, 'http')) {
            Storage::disk('public')->delete($old);
        }
        Setting::set('site_favicon', null);

        return back()->with('status', 'Favicon removed.')->with('tab', 'general');
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

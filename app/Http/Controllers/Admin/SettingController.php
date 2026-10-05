<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.layout');
    }

    public function general()
    {
        $keys = [
            'site_name',
            'site_tagline',
            'site_description',
            'site_logo',
            'site_favicon',
            'site_currency',
            'site_language',
            'site_timezone',
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
            'site_currency' => ['nullable', 'string', 'max:10'],
            'site_language' => ['nullable', 'string', 'max:10'],
            'site_timezone' => ['nullable', 'string', 'max:100'],
            'site_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg,webp', 'max:2048'],
            'site_favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,ico,svg', 'max:1024'],
        ]);

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

    public function contact()
    {
        $keys = [
            'site_email',
            'site_phone',
            'site_address',
            'facebook_url',
            'instagram_url',
            'twitter_url',
            'youtube_url',
            'linkedin_url',
            'tiktok_url',
        ];

        $settings = Setting::getMany($keys);

        return view('admin.settings.contact', compact('settings'));
    }

    public function contactUpdate(Request $request)
    {
        $data = $request->validate([
            'site_email' => ['nullable', 'email', 'max:150'],
            'site_phone' => ['nullable', 'string', 'max:30'],
            'site_address' => ['nullable', 'string', 'max:255'],
            'facebook_url' => ['nullable', 'string', 'max:255'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'twitter_url' => ['nullable', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:255'],
            'linkedin_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
        ]);

        Setting::setMany($data);

        return back()->with('status', 'Contact & social settings updated.');
    }

    public function seo()
    {
        return view('admin.settings.seo');
    }

    public function homepage()
    {
        return view('admin.settings.homepage');
    }

    public function smtp()
    {
        return view('admin.settings.smtp');
    }

    public function system()
    {
        $data = [
            'theme' => [
                'name' => 'RJSHOP ADMIN',
                'version' => '1.2',
                'description' => 'RJ Shop theme derivative',
            ],
            'app' => [
                'name' => 'RJ SHOP AI',
                'version' => '1.1',
            ],
            'system' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'server_os' => PHP_OS,
                'database_driver' => config('database.default'),
                'database_version' => $this->getDatabaseVersion(),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'queue_driver' => config('queue.default'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'environment' => app()->environment(),
                'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
                'url' => config('app.url'),
                'max_upload_size' => ini_get('upload_max_filesize'),
                'max_post_size' => ini_get('post_max_size'),
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time') . 's',
            ],
            'extensions' => [
                'openssl' => extension_loaded('openssl'),
                'pdo' => extension_loaded('pdo'),
                'mbstring' => extension_loaded('mbstring'),
                'tokenizer' => extension_loaded('tokenizer'),
                'xml' => extension_loaded('xml'),
                'ctype' => extension_loaded('ctype'),
                'json' => extension_loaded('json'),
                'bcmath' => extension_loaded('bcmath'),
                'fileinfo' => extension_loaded('fileinfo'),
                'gd' => extension_loaded('gd'),
                'curl' => extension_loaded('curl'),
                'zip' => extension_loaded('zip'),
            ],
        ];

        return view('admin.settings.system', compact('data'));
    }

    public function maintenance()
    {
        $keys = ['maintenance_mode', 'maintenance_message'];
        $settings = Setting::getMany($keys);

        return view('admin.settings.maintenance', compact('settings'));
    }

    public function maintenanceUpdate(Request $request)
    {
        $data = $request->validate([
            'maintenance_message' => ['nullable', 'string', 'max:500'],
        ]);

        $data['maintenance_mode'] = $request->boolean('maintenance_mode') ? '1' : '0';

        Setting::setMany($data);

        return back()->with('status', 'Maintenance settings updated.');
    }

    public function security()
    {
        $logs = \App\Models\LoginLog::latest()->limit(50)->get();
        $blockedIps = \App\Models\BlockedIp::latest()->get();

        return view('admin.settings.security', compact('logs', 'blockedIps'));
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

    protected function getDatabaseVersion(): string
    {
        try {
            $driver = config('database.default');

            if ($driver === 'sqlite') {
                return DB::selectOne('select sqlite_version() as version')->version ?? 'Unknown';
            }

            if ($driver === 'mysql') {
                return DB::selectOne('select version() as version')->version ?? 'Unknown';
            }

            if ($driver === 'pgsql') {
                return DB::selectOne('select version() as version')->version ?? 'Unknown';
            }

            return 'Unknown';
        } catch (\Exception $e) {
            return 'Unknown';
        }
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

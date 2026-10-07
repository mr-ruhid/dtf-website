<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use App\Models\LoginLog;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
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

        return back()->with('status', 'General settings updated successfully.');
    }

    public function generalRemoveLogo()
    {
        $old = Setting::get('site_logo');
        if ($old && !str_starts_with($old, 'http')) {
            Storage::disk('public')->delete($old);
        }
        Setting::set('site_logo', null);

        return back()->with('status', 'Logo removed.');
    }

    public function generalRemoveFavicon()
    {
        $old = Setting::get('site_favicon');
        if ($old && !str_starts_with($old, 'http')) {
            Storage::disk('public')->delete($old);
        }
        Setting::set('site_favicon', null);

        return back()->with('status', 'Favicon removed.');
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
        $keys = [
            'meta_title',
            'meta_description',
            'meta_keywords',
            'og_image',
            'google_analytics_id',
            'google_tag_manager_id',
            'facebook_pixel_id',
            'google_verification',
            'robots_index',
            'robots_txt',
        ];

        $settings = Setting::getMany($keys);

        return view('admin.settings.seo', compact('settings'));
    }

    public function seoUpdate(Request $request)
    {
        $data = $request->validate([
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'google_analytics_id' => ['nullable', 'string', 'max:50'],
            'google_tag_manager_id' => ['nullable', 'string', 'max:50'],
            'facebook_pixel_id' => ['nullable', 'string', 'max:50'],
            'google_verification' => ['nullable', 'string', 'max:255'],
            'robots_txt' => ['nullable', 'string', 'max:5000'],
        ]);

        $data['robots_index'] = $request->boolean('robots_index') ? '1' : '0';

        if ($request->hasFile('og_image')) {
            $old = Setting::get('og_image');
            if ($old && !str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            $data['og_image'] = $request->file('og_image')->store('settings', 'public');
        }

        Setting::setMany($data);

        return back()->with('status', 'SEO settings updated.');
    }

    public function seoRemoveOg()
    {
        $old = Setting::get('og_image');
        if ($old && !str_starts_with($old, 'http')) {
            Storage::disk('public')->delete($old);
        }
        Setting::set('og_image', null);

        return back()->with('status', 'OG image removed.');
    }

    public function homepage()
    {
        $keys = [
            'hero_title',
            'hero_subtitle',
            'hero_description',
            'hero_btn1_text',
            'hero_btn1_link',
            'hero_btn2_text',
            'hero_btn2_link',
            'hero_image',
            'stat1_label', 'stat1_value',
            'stat2_label', 'stat2_value',
            'stat3_label', 'stat3_value',
            'stat4_label', 'stat4_value',
            'announcement_enabled',
            'announcement_text',
            'announcement_link',
        ];

        $settings = Setting::getMany($keys);

        return view('admin.settings.homepage', compact('settings'));
    }

    public function homepageUpdate(Request $request)
    {
        $data = $request->validate([
            'hero_title' => ['nullable', 'string', 'max:200'],
            'hero_subtitle' => ['nullable', 'string', 'max:200'],
            'hero_description' => ['nullable', 'string', 'max:500'],
            'hero_btn1_text' => ['nullable', 'string', 'max:50'],
            'hero_btn1_link' => ['nullable', 'string', 'max:255'],
            'hero_btn2_text' => ['nullable', 'string', 'max:50'],
            'hero_btn2_link' => ['nullable', 'string', 'max:255'],
            'hero_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:5120'],
            'stat1_label' => ['nullable', 'string', 'max:50'],
            'stat1_value' => ['nullable', 'string', 'max:20'],
            'stat2_label' => ['nullable', 'string', 'max:50'],
            'stat2_value' => ['nullable', 'string', 'max:20'],
            'stat3_label' => ['nullable', 'string', 'max:50'],
            'stat3_value' => ['nullable', 'string', 'max:20'],
            'stat4_label' => ['nullable', 'string', 'max:50'],
            'stat4_value' => ['nullable', 'string', 'max:20'],
            'announcement_text' => ['nullable', 'string', 'max:200'],
            'announcement_link' => ['nullable', 'string', 'max:255'],
        ]);

        $data['announcement_enabled'] = $request->boolean('announcement_enabled') ? '1' : '0';

        if ($request->hasFile('hero_image')) {
            $old = Setting::get('hero_image');
            if ($old && !str_starts_with($old, 'http')) {
                Storage::disk('public')->delete($old);
            }
            $data['hero_image'] = $request->file('hero_image')->store('settings', 'public');
        }

        Setting::setMany($data);

        return back()->with('status', 'Homepage settings updated.');
    }

    public function homepageRemoveHero()
    {
        $old = Setting::get('hero_image');
        if ($old && !str_starts_with($old, 'http')) {
            Storage::disk('public')->delete($old);
        }
        Setting::set('hero_image', null);

        return back()->with('status', 'Hero image removed.');
    }

    public function smtp()
    {
        $keys = [
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
        ];

        $settings = Setting::getMany($keys);

        return view('admin.settings.smtp', compact('settings'));
    }

    public function smtpUpdate(Request $request)
    {
        $data = $request->validate([
            'mail_mailer' => ['required', 'string', 'max:20'],
            'mail_host' => ['nullable', 'string', 'max:255'],
            'mail_port' => ['nullable', 'string', 'max:10'],
            'mail_username' => ['nullable', 'string', 'max:255'],
            'mail_password' => ['nullable', 'string', 'max:255'],
            'mail_encryption' => ['nullable', 'string', 'max:10'],
            'mail_from_address' => ['nullable', 'email', 'max:150'],
            'mail_from_name' => ['nullable', 'string', 'max:150'],
        ]);

        Setting::setMany($data);

        return back()->with('status', 'SMTP settings updated.');
    }

    public function smtpTest(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $settings = Setting::getMany([
                'mail_mailer', 'mail_host', 'mail_port', 'mail_username',
                'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name',
            ]);

            config([
                'mail.default' => $settings['mail_mailer'] ?: config('mail.default'),
                'mail.mailers.smtp.host' => $settings['mail_host'] ?: config('mail.mailers.smtp.host'),
                'mail.mailers.smtp.port' => $settings['mail_port'] ?: config('mail.mailers.smtp.port'),
                'mail.mailers.smtp.username' => $settings['mail_username'] ?: config('mail.mailers.smtp.username'),
                'mail.mailers.smtp.password' => $settings['mail_password'] ?: config('mail.mailers.smtp.password'),
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?: config('mail.mailers.smtp.encryption'),
                'mail.from.address' => $settings['mail_from_address'] ?: config('mail.from.address'),
                'mail.from.name' => $settings['mail_from_name'] ?: config('mail.from.name'),
            ]);

            Mail::raw('This is a test email from RJ SHOP. If you received this, your SMTP configuration is working correctly.', function ($message) use ($request) {
                $message->to($request->input('email'))
                        ->subject('RJ SHOP — SMTP Test');
            });

            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully to ' . $request->input('email'),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send: ' . $e->getMessage(),
            ]);
        }
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
        $keys = [
            'maintenance_mode',
            'maintenance_message',
            'maintenance_start_at',
            'maintenance_end_at',
            'maintenance_auto_disable',
            'maintenance_allowed_ips',
        ];
        $settings = Setting::getMany($keys);

        return view('admin.settings.maintenance', compact('settings'));
    }

    public function maintenanceUpdate(Request $request)
    {
        $data = $request->validate([
            'maintenance_message' => ['nullable', 'string', 'max:500'],
            'maintenance_start_at' => ['nullable', 'date'],
            'maintenance_end_at' => ['nullable', 'date'],
            'maintenance_allowed_ips' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['maintenance_mode'] = $request->boolean('maintenance_mode') ? '1' : '0';
        $data['maintenance_auto_disable'] = $request->boolean('maintenance_auto_disable') ? '1' : '0';

        Setting::setMany($data);

        return back()->with('status', 'Maintenance settings updated.');
    }

    public function security()
    {
        $logs = LoginLog::latest()->limit(50)->get();
        $blockedIps = BlockedIp::latest()->get();

        return view('admin.settings.security', compact('logs', 'blockedIps'));
    }

    public function blockIp(Request $request)
    {
        $data = $request->validate([
            'ip_address' => ['required', 'ip'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        BlockedIp::updateOrCreate(
            ['ip_address' => $data['ip_address']],
            [
                'reason' => $data['reason'] ?? 'Manually blocked by admin',
                'blocked_by' => auth()->id(),
                'blocked_until' => null,
            ]
        );

        return back()->with('status', 'IP address blocked successfully.');
    }

    public function unblockIp(BlockedIp $blockedIp)
    {
        $blockedIp->delete();

        return back()->with('status', 'IP address unblocked.');
    }

    public function update()
    {
        return view('admin.settings.update');
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

        public function designPricing()
    {
        $keys = [
            'design_custom_enabled',
            'design_custom_price_per_sq_inch',
            'design_custom_min_price',
            'design_custom_min_inch',
            'design_custom_max_inch',
        ];

        $settings = Setting::getMany($keys);

        return view('admin.settings.design-pricing', compact('settings'));
    }

    public function designPricingUpdate(Request $request)
    {
        $data = $request->validate([
            'design_custom_price_per_sq_inch' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'design_custom_min_price' => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'design_custom_min_inch' => ['nullable', 'numeric', 'min:1', 'max:60'],
            'design_custom_max_inch' => ['nullable', 'numeric', 'min:1', 'max:200'],
        ]);

        $data['design_custom_enabled'] = $request->boolean('design_custom_enabled') ? '1' : '0';

        Setting::setMany($data);

        return back()->with('status', 'Design pricing settings updated.');
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

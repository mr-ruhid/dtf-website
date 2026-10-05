@extends('admin.settings.layout')

@section('title', 'General Settings')
@section('settings-content')

<form method="POST" action="{{ route('admin.settings.general.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-globe text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Site Information</h3>
                <p class="text-xs text-gray-500">Basic details about your site</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Name <span class="text-red-500">*</span></label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings['site_name'] ?? 'RJ SHOP lite') }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                           placeholder="Short slogan"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                <textarea name="site_description" rows="2" maxlength="500"
                          placeholder="Brief description of your site for SEO"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-image text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Branding</h3>
                <p class="text-xs text-gray-500">Logo and favicon</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                @if (!empty($settings['site_logo']))
                    <div class="mb-3 p-4 bg-slate-50 border border-gray-200 rounded-lg flex items-center justify-between">
                        <img src="{{ asset('storage/' . $settings['site_logo']) }}" class="h-12 object-contain" alt="Logo">
                        <form method="POST" action="{{ route('admin.settings.general.remove-logo') }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-trash text-xs"></i> Remove
                            </button>
                        </form>
                    </div>
                @endif
                <input type="file" name="site_logo" accept="image/*"
                       class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG, WEBP · Max 2MB</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                @if (!empty($settings['site_favicon']))
                    <div class="mb-3 p-4 bg-slate-50 border border-gray-200 rounded-lg flex items-center justify-between">
                        <img src="{{ asset('storage/' . $settings['site_favicon']) }}" class="h-10 object-contain" alt="Favicon">
                        <form method="POST" action="{{ route('admin.settings.general.remove-favicon') }}">
                            @csrf
                            @method('DELETE')
                            <button class="text-xs text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-trash text-xs"></i> Remove
                            </button>
                        </form>
                    </div>
                @endif
                <input type="file" name="site_favicon" accept="image/*"
                       class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">PNG, ICO, SVG · Max 1MB · 32x32 recommended</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-address-book text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Contact Information</h3>
                <p class="text-xs text-gray-500">How customers can reach you</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings['site_email'] ?? '') }}"
                           placeholder="info@example.com"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings['site_phone'] ?? '') }}"
                           placeholder="+994 50 000 00 00"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <input type="text" name="site_address" value="{{ old('site_address', $settings['site_address'] ?? '') }}"
                       placeholder="Bakı, Azərbaycan"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-coins text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Regional</h3>
                <p class="text-xs text-gray-500">Currency, language and timezone</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Currency</label>
                <select name="site_currency"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @foreach (['USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'AZN' => 'AZN (₼)', 'TRY' => 'TRY (₺)', 'RUB' => 'RUB (₽)', 'GBP' => 'GBP (£)'] as $code => $label)
                        <option value="{{ $code }}" {{ ($settings['site_currency'] ?? 'USD') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                <select name="site_language"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @foreach (['en' => 'English', 'az' => 'Azərbaycan', 'tr' => 'Türkçe', 'ru' => 'Русский'] as $code => $label)
                        <option value="{{ $code }}" {{ ($settings['site_language'] ?? 'en') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                <select name="site_timezone"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @foreach (['UTC' => 'UTC', 'Asia/Baku' => 'Baku (GMT+4)', 'Europe/Istanbul' => 'Istanbul (GMT+3)', 'Europe/London' => 'London (GMT+0)', 'America/New_York' => 'New York (GMT-5)'] as $code => $label)
                        <option value="{{ $code }}" {{ ($settings['site_timezone'] ?? 'UTC') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                <i class="fa-solid fa-share-nodes text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Social Media</h3>
                <p class="text-xs text-gray-500">Connect your social profiles</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ([
                'facebook_url' => ['Facebook', 'fa-facebook', 'https://facebook.com/yourpage'],
                'instagram_url' => ['Instagram', 'fa-instagram', 'https://instagram.com/yourpage'],
                'twitter_url' => ['Twitter / X', 'fa-x-twitter', 'https://x.com/yourpage'],
                'youtube_url' => ['YouTube', 'fa-youtube', 'https://youtube.com/@yourchannel'],
                'linkedin_url' => ['LinkedIn', 'fa-linkedin', 'https://linkedin.com/company/yourpage'],
                'tiktok_url' => ['TikTok', 'fa-tiktok', 'https://tiktok.com/@yourpage'],
            ] as $key => $meta)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fa-brands {{ $meta[1] }} mr-1 text-gray-400"></i> {{ $meta[0] }}
                    </label>
                    <input type="text" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}"
                           placeholder="{{ $meta[2] }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-screwdriver-wrench text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Maintenance Mode</h3>
                <p class="text-xs text-gray-500">Temporarily disable the site for visitors</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="maintenance_mode" value="1"
                       {{ ($settings['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}
                       class="rounded border-gray-300 text-rose-600 focus:ring-rose-500 w-5 h-5">
                <div>
                    <p class="text-sm font-medium text-gray-800">Enable Maintenance Mode</p>
                    <p class="text-xs text-gray-500">Visitors will see a maintenance page. Admin panel remains accessible.</p>
                </div>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Message</label>
                <textarea name="maintenance_message" rows="2" maxlength="500"
                          placeholder="We'll be back soon!"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end gap-3 sticky bottom-0 bg-slate-100/80 backdrop-blur py-3 -mx-1 px-1">
        <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg transition flex items-center gap-2 shadow-lg shadow-indigo-500/20">
            <i class="fa-solid fa-floppy-disk text-xs"></i> Save Settings
        </button>
    </div>

</form>

@endsection

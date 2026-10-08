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
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tagline</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline'] ?? '') }}"
                           placeholder="Short slogan"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Site Description</label>
                <textarea name="site_description" rows="2" maxlength="500"
                          placeholder="Brief description of your site for SEO"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
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
                        <button type="button" onclick="document.getElementById('removeLogoForm').submit()"
                                class="text-xs text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-trash text-xs"></i> Remove
                        </button>
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
                        <button type="button" onclick="document.getElementById('removeFaviconForm').submit()"
                                class="text-xs text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-trash text-xs"></i> Remove
                        </button>
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
                <i class="fa-solid fa-truck-fast text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Trust Bar</h3>
                <p class="text-xs text-gray-500">Feature badges shown under the hero (icons are fixed)</p>
            </div>
        </div>

        <div class="p-6 space-y-5">
            <p class="text-xs text-gray-500 leading-relaxed">
                These 4 badges appear on the sign hero and other pages. Icons are predefined — you can customize the text freely.
            </p>

            @php
                $trustDefaults = [
                    1 => ['icon' => 'fa-award', 'title' => '5-Year Warranty', 'desc' => 'On all outdoor signage'],
                    2 => ['icon' => 'fa-shield-halved', 'title' => 'UV-Resistant', 'desc' => 'Colors that last for years'],
                    3 => ['icon' => 'fa-bolt', 'title' => 'Fast Turnaround', 'desc' => 'Ready in 24-72 hours'],
                    4 => ['icon' => 'fa-truck', 'title' => 'Free Delivery', 'desc' => 'On orders over $99'],
                ];
            @endphp

            @foreach ($trustDefaults as $i => $default)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 pb-4 {{ $i < 4 ? 'border-b border-gray-100' : '' }}">
                    <div class="md:col-span-1 flex items-center justify-center">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid {{ $default['icon'] }} text-sm"></i>
                        </div>
                    </div>

                    <div class="md:col-span-5">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                        <input type="text" name="trust_{{ $i }}_title"
                               value="{{ old('trust_' . $i . '_title', $settings['trust_' . $i . '_title'] ?? $default['title']) }}"
                               maxlength="50"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div class="md:col-span-6">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                        <input type="text" name="trust_{{ $i }}_desc"
                               value="{{ old('trust_' . $i . '_desc', $settings['trust_' . $i . '_desc'] ?? $default['desc']) }}"
                               maxlength="80"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            @endforeach
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
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach (['USD' => 'USD ($)', 'EUR' => 'EUR (€)', 'AZN' => 'AZN (₼)', 'TRY' => 'TRY (₺)', 'RUB' => 'RUB (₽)', 'GBP' => 'GBP (£)'] as $code => $label)
                        <option value="{{ $code }}" {{ ($settings['site_currency'] ?? 'USD') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Language</label>
                <select name="site_language"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach (['en' => 'English', 'az' => 'Azərbaycan', 'tr' => 'Türkçe', 'ru' => 'Русский'] as $code => $label)
                        <option value="{{ $code }}" {{ ($settings['site_language'] ?? 'en') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                <select name="site_timezone"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach (['UTC' => 'UTC', 'Asia/Baku' => 'Baku (GMT+4)', 'Europe/Istanbul' => 'Istanbul (GMT+3)', 'Europe/London' => 'London (GMT+0)', 'America/New_York' => 'New York (GMT-5)'] as $code => $label)
                        <option value="{{ $code }}" {{ ($settings['site_timezone'] ?? 'UTC') === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
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

<form method="POST" action="{{ route('admin.settings.general.remove-logo') }}" id="removeLogoForm" class="hidden">
    @csrf
    @method('DELETE')
</form>

<form method="POST" action="{{ route('admin.settings.general.remove-favicon') }}" id="removeFaviconForm" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endsection

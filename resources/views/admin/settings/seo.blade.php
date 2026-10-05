@extends('admin.settings.layout')

@section('title', 'SEO Settings')
@section('settings-content')

<form method="POST" action="{{ route('admin.settings.seo.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Global Meta Tags</h3>
                <p class="text-xs text-gray-500">Default SEO meta information</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $settings['meta_title'] ?? '') }}" maxlength="200"
                       placeholder="RJ SHOP — DTF Transfers, UV Stickers, Blank Apparel"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Recommended: 50-60 characters</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                <textarea name="meta_description" rows="3" maxlength="300"
                          placeholder="Shop premium DTF transfers, UV stickers, blank apparel and more. Fast shipping across the US."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Recommended: 150-160 characters</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" maxlength="500"
                       placeholder="dtf transfers, uv stickers, blank apparel, custom printing"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                <i class="fa-solid fa-image text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Social Share (Open Graph)</h3>
                <p class="text-xs text-gray-500">Image shown when sharing on social media</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            @if (!empty($settings['og_image']))
                <div class="p-4 bg-slate-50 border border-gray-200 rounded-lg flex items-center justify-between">
                    <img src="{{ asset('storage/' . $settings['og_image']) }}" class="h-20 rounded border" alt="OG Image">
                    <form method="POST" action="{{ route('admin.settings.seo.remove-og') }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-trash text-xs"></i> Remove
                        </button>
                    </form>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">OG Image</label>
                <input type="file" name="og_image" accept="image/*"
                       class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Recommended: 1200 × 630 px · JPG, PNG · Max 3MB</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Analytics & Tracking</h3>
                <p class="text-xs text-gray-500">Third-party analytics and verification</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Google Analytics ID</label>
                <input type="text" name="google_analytics_id" value="{{ old('google_analytics_id', $settings['google_analytics_id'] ?? '') }}"
                       placeholder="G-XXXXXXXXXX"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Google Tag Manager ID</label>
                <input type="text" name="google_tag_manager_id" value="{{ old('google_tag_manager_id', $settings['google_tag_manager_id'] ?? '') }}"
                       placeholder="GTM-XXXXXXX"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Facebook Pixel ID</label>
                <input type="text" name="facebook_pixel_id" value="{{ old('facebook_pixel_id', $settings['facebook_pixel_id'] ?? '') }}"
                       placeholder="000000000000000"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Google Search Console Verification</label>
                <input type="text" name="google_verification" value="{{ old('google_verification', $settings['google_verification'] ?? '') }}"
                       placeholder="Verification meta content value"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Only the content value, not the full meta tag</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                <i class="fa-solid fa-robot text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Robots & Indexing</h3>
                <p class="text-xs text-gray-500">Control search engine crawling</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="robots_index" value="1"
                       {{ ($settings['robots_index'] ?? '1') === '1' ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                <div>
                    <p class="text-sm font-medium text-gray-800">Allow Search Engines to Index</p>
                    <p class="text-xs text-gray-500">Disable to add noindex tag across the site</p>
                </div>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Robots.txt Content</label>
                <textarea name="robots_txt" rows="6"
                          placeholder="User-agent: *&#10;Allow: /&#10;Disallow: /admin"
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('robots_txt', $settings['robots_txt'] ?? "User-agent: *\nAllow: /\nDisallow: /admin") }}</textarea>
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

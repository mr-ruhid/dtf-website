@extends('admin.settings.layout')

@section('title', 'Homepage')
@section('settings-content')

<form method="POST" action="{{ route('admin.settings.homepage.update') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                <i class="fa-solid fa-house text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Hero Section</h3>
                <p class="text-xs text-gray-500">Main banner at the top of homepage</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Title</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $settings['hero_title'] ?? '') }}"
                           placeholder="Premium DTF Transfers & Custom Printing"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" value="{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}"
                           placeholder="Fast shipping across the US"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hero Description</label>
                <textarea name="hero_description" rows="3" maxlength="500"
                          placeholder="Shop thousands of designs, custom prints and blank apparel..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('hero_description', $settings['hero_description'] ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Button Text</label>
                    <input type="text" name="hero_btn1_text" value="{{ old('hero_btn1_text', $settings['hero_btn1_text'] ?? '') }}"
                           placeholder="Shop Now"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Button Link</label>
                    <input type="text" name="hero_btn1_link" value="{{ old('hero_btn1_link', $settings['hero_btn1_link'] ?? '') }}"
                           placeholder="/products"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Button Text</label>
                    <input type="text" name="hero_btn2_text" value="{{ old('hero_btn2_text', $settings['hero_btn2_text'] ?? '') }}"
                           placeholder="Learn More"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Button Link</label>
                    <input type="text" name="hero_btn2_link" value="{{ old('hero_btn2_link', $settings['hero_btn2_link'] ?? '') }}"
                           placeholder="/about-us"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            @if (!empty($settings['hero_image']))
                <div class="p-4 bg-slate-50 border border-gray-200 rounded-lg flex items-center justify-between">
                    <img src="{{ asset('storage/' . $settings['hero_image']) }}" class="h-24 rounded border" alt="Hero">
                    <form method="POST" action="{{ route('admin.settings.homepage.remove-image') }}">
                        @csrf
                        @method('DELETE')
                        <button class="text-xs text-red-600 hover:bg-red-50 px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-trash text-xs"></i> Remove
                        </button>
                    </form>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hero Image</label>
                <input type="file" name="hero_image" accept="image/*"
                       class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                <p class="text-xs text-gray-500 mt-1">Recommended: 800 × 800 px · PNG transparent</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <i class="fa-solid fa-chart-simple text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Statistics Bar</h3>
                <p class="text-xs text-gray-500">Numbers shown below hero section</p>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ([
                'stat1' => ['Happy Customers', '10K+'],
                'stat2' => ['Products', '500+'],
                'stat3' => ['Orders Delivered', '25K+'],
                'stat4' => ['Years Experience', '5+'],
            ] as $key => $meta)
                <div class="border border-gray-200 rounded-lg p-4 space-y-3">
                    <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">{{ $meta[0] }}</p>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="text" name="{{ $key }}_label" value="{{ old($key.'_label', $settings[$key.'_label'] ?? $meta[0]) }}"
                               placeholder="Label"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <input type="text" name="{{ $key }}_value" value="{{ old($key.'_value', $settings[$key.'_value'] ?? $meta[1]) }}"
                               placeholder="Value"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fa-solid fa-bullhorn text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Announcement Bar</h3>
                <p class="text-xs text-gray-500">Top bar message for promotions</p>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="announcement_enabled" value="1"
                       {{ ($settings['announcement_enabled'] ?? '0') === '1' ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-5 h-5">
                <div>
                    <p class="text-sm font-medium text-gray-800">Enable Announcement Bar</p>
                    <p class="text-xs text-gray-500">Shows at the very top of every page</p>
                </div>
            </label>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Announcement Text</label>
                <input type="text" name="announcement_text" value="{{ old('announcement_text', $settings['announcement_text'] ?? '') }}" maxlength="200"
                       placeholder="🎉 Free shipping on orders over $100!"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Announcement Link (optional)</label>
                <input type="text" name="announcement_link" value="{{ old('announcement_link', $settings['announcement_link'] ?? '') }}"
                       placeholder="/products"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
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

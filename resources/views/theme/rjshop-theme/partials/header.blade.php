@php
    $siteName = \App\Models\Setting::get('site_name', 'RJ Shop');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
    $headerPages = \App\Models\Page::where('show_in_header', 1)->where('status', 1)->orderBy('sort_order')->get();
    $announcementEnabled = \App\Models\Setting::get('announcement_enabled') == '1';
    $announcementText = \App\Models\Setting::get('announcement_text');
    $announcementLink = \App\Models\Setting::get('announcement_link');
@endphp

<header x-data="{ mobileOpen: false }" class="bg-white shadow-sm sticky top-0 z-40">
    @if($announcementEnabled && $announcementText)
        <div class="bg-indigo-600 text-white text-sm text-center py-2 px-4">
            @if($announcementLink)
                <a href="{{ $announcementLink }}" class="hover:underline">{{ $announcementText }}</a>
            @else
                {{ $announcementText }}
            @endif
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">
            <a href="{{ url('/') }}" class="flex items-center gap-2 shrink-0">
                @if($siteLogoUrl)
                    <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto">
                @else
                    <span class="text-xl font-bold text-gray-900">{{ $siteName }}</span>
                @endif
            </a>

            <nav class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">Home</a>
                @foreach($headerPages as $page)
                    @if($page->key !== 'home')
                        <a href="{{ url($page->slug) }}" class="text-sm font-medium text-gray-700 hover:text-indigo-600 transition">{{ $page->title }}</a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-1">
                <button type="button" class="text-gray-600 hover:text-indigo-600 transition w-10 h-10 flex items-center justify-center" aria-label="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="#" class="text-gray-600 hover:text-indigo-600 transition w-10 h-10 flex items-center justify-center relative" aria-label="Cart">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="absolute top-1 right-1 bg-indigo-600 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">0</span>
                </a>
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-600 w-10 h-10 flex items-center justify-center" aria-label="Menu">
                    <i class="fa-solid" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition class="md:hidden border-t border-gray-100 bg-white">
        <nav class="px-4 py-3 space-y-1">
            <a href="{{ url('/') }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-indigo-600">Home</a>
            @foreach($headerPages as $page)
                @if($page->key !== 'home')
                    <a href="{{ url($page->slug) }}" class="block py-2 text-sm font-medium text-gray-700 hover:text-indigo-600">{{ $page->title }}</a>
                @endif
            @endforeach
        </nav>
    </div>
</header>

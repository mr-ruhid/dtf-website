@php
    $siteName = \App\Models\Setting::get('site_name', 'RJ Shop');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
    $headerPages = \App\Models\Page::where('type', 'static')
        ->where('status', 1)
        ->orderBy('sort_order')
        ->get();
    $announcementEnabled = \App\Models\Setting::get('announcement_enabled') == '1';
    $announcementText = \App\Models\Setting::get('announcement_text');
    $announcementLink = \App\Models\Setting::get('announcement_link');
@endphp

<header x-data="{ mobileOpen: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 20"
        :class="scrolled ? 'bg-[#05030f]/95 backdrop-blur-xl shadow-[0_4px_30px_rgba(99,102,241,0.15)]' : 'bg-[#05030f]'"
        class="transition-all duration-300 border-b border-indigo-500/20">

    @if($announcementEnabled && $announcementText)
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-xs md:text-sm">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(90deg,transparent,rgba(255,255,255,0.4),transparent)] animate-shimmer"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 py-2 text-center font-medium">
                <span class="inline-flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-emerald-300 rounded-full animate-pulse"></span>
                    @if($announcementLink)
                        <a href="{{ $announcementLink }}" class="hover:underline font-semibold">{{ $announcementText }}</a>
                    @else
                        <span class="font-semibold">{{ $announcementText }}</span>
                    @endif
                </span>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">

            <a href="{{ url('/') }}" class="group flex items-center gap-3 shrink-0">
                @if($siteLogoUrl)
                    <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto brightness-0 invert">
                    <span class="text-xl md:text-2xl font-black tracking-tight text-white">
                        {{ $siteName }}
                    </span>
                @else
                    <div class="relative w-11 h-11 flex items-center justify-center">
                        <div class="absolute inset-0 rounded-full border-2 border-indigo-400/60 animate-orbit-slow"></div>
                        <div class="absolute inset-1.5 rounded-full border border-purple-400/60 animate-orbit-reverse"></div>
                        <div class="absolute inset-3 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500"></div>
                        <span class="relative z-10 text-white font-black text-xs">RJ</span>
                    </div>
                    <span class="text-xl md:text-2xl font-black tracking-tight text-white">
                        {{ $siteName }}<span class="text-indigo-400">.</span>
                    </span>
                @endif
            </a>

            <nav class="hidden md:flex items-center gap-2">
                <a href="{{ url('/') }}"
                   class="relative px-4 py-2 text-sm font-semibold {{ request()->is('/') ? 'text-white' : 'text-gray-300' }} hover:text-white transition">
                    <span class="relative z-10">Home</span>
                    @if(request()->is('/'))
                        <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-gradient-to-r from-indigo-400 to-pink-400 rounded-full"></span>
                    @endif
                </a>

                @foreach($headerPages as $page)
                    @if($page->key !== 'home')
                        <a href="{{ url($page->slug) }}"
                           class="relative px-4 py-2 text-sm font-semibold {{ request()->is($page->slug) ? 'text-white' : 'text-gray-300' }} hover:text-white transition">
                            <span class="relative z-10">{{ $page->title }}</span>
                            @if(request()->is($page->slug))
                                <span class="absolute bottom-0 left-1/2 -translate-x-1/2 w-6 h-0.5 bg-gradient-to-r from-indigo-400 to-pink-400 rounded-full"></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                <button type="button"
                        class="w-10 h-10 flex items-center justify-center rounded-full text-gray-300 hover:text-white hover:bg-white/10 transition"
                        aria-label="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                <a href="#"
                   class="relative w-10 h-10 flex items-center justify-center rounded-full text-gray-300 hover:text-white hover:bg-white/10 transition"
                   aria-label="Cart">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="absolute -top-0.5 -right-0.5 bg-gradient-to-br from-indigo-500 to-pink-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center ring-2 ring-[#05030f]">0</span>
                </a>

                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden w-10 h-10 flex items-center justify-center rounded-full text-gray-300 hover:text-white hover:bg-white/10 transition"
                        aria-label="Menu">
                    <i class="fa-solid text-lg" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    @include('theme.rjshop-theme.partials.header-mega-menu')

    <div x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden border-t border-indigo-500/20 bg-[#05030f]">

        <nav class="px-4 py-4 space-y-1">
            <a href="{{ url('/') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white hover:bg-white/5 transition">
                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                <span>Home</span>
            </a>

            @foreach($headerPages as $page)
                @if($page->key !== 'home')
                    <a href="{{ url($page->slug) }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-white hover:bg-white/5 transition">
                        <span class="w-1.5 h-1.5 bg-purple-400 rounded-full"></span>
                        <span>{{ $page->title }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>
</header>

@push('styles')
<style>
@keyframes orbit-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes orbit-reverse { from { transform: rotate(360deg); } to { transform: rotate(0deg); } }
.animate-orbit-slow { animation: orbit-slow 20s linear infinite; }
.animate-orbit-reverse { animation: orbit-reverse 14s linear infinite; }

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.animate-shimmer { animation: shimmer 3s ease-in-out infinite; }
</style>
@endpush

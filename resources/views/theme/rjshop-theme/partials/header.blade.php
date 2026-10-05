@php
    $siteName = \App\Models\Setting::get('site_name', 'RJ Shop');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
    $headerPages = \App\Models\Page::where('show_in_header', 1)->where('status', 1)->orderBy('sort_order')->get();
    $announcementEnabled = \App\Models\Setting::get('announcement_enabled') == '1';
    $announcementText = \App\Models\Setting::get('announcement_text');
    $announcementLink = \App\Models\Setting::get('announcement_link');
@endphp

<header x-data="{ mobileOpen: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 20"
        :class="scrolled ? 'bg-[#05030f]/90 backdrop-blur-xl border-b border-indigo-500/20' : 'bg-[#05030f]/60 backdrop-blur-md border-b border-white/5'"
        class="sticky top-0 z-50 transition-all duration-500">

    @if($announcementEnabled && $announcementText)
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 text-white text-xs md:text-sm">
            <div class="absolute inset-0 opacity-30">
                <div class="absolute top-0 left-0 w-full h-full bg-[linear-gradient(90deg,transparent,rgba(255,255,255,0.3),transparent)] animate-shimmer"></div>
            </div>
            <div class="relative max-w-7xl mx-auto px-4 py-2 text-center font-mono tracking-wider">
                <span class="inline-flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    @if($announcementLink)
                        <a href="{{ $announcementLink }}" class="hover:underline">{{ $announcementText }}</a>
                    @else
                        {{ $announcementText }}
                    @endif
                </span>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-20">

            <a href="{{ url('/') }}" class="group flex items-center gap-3 shrink-0">
                <div class="relative w-10 h-10 flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full border border-indigo-500/40 animate-orbit-slow"></div>
                    <div class="absolute inset-1 rounded-full border border-purple-500/40 animate-orbit-reverse"></div>
                    <div class="absolute inset-2 rounded-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 opacity-60 blur-sm group-hover:opacity-100 transition"></div>
                    <div class="relative z-10 w-6 h-6 flex items-center justify-center">
                        @if($siteLogoUrl)
                            <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="w-full h-full object-contain">
                        @else
                            <span class="text-white font-black text-xs">RJ</span>
                        @endif
                    </div>
                    <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-1.5 h-1.5 bg-indigo-400 rounded-full shadow-[0_0_10px_2px_rgba(99,102,241,0.9)] animate-spin-slow origin-[50%_calc(50%_+_20px)]"></div>
                </div>

                @if(!$siteLogoUrl)
                    <span class="text-lg md:text-xl font-black tracking-tight text-white">
                        {{ $siteName }}
                        <span class="text-indigo-400">.</span>
                    </span>
                @endif
            </a>

            <nav class="hidden md:flex items-center gap-1">
                <a href="{{ url('/') }}"
                   class="group relative px-4 py-2 font-mono text-xs uppercase tracking-widest text-gray-400 hover:text-white transition">
                    <span class="relative z-10">Home</span>
                    <span class="absolute inset-0 rounded-full bg-indigo-500/0 group-hover:bg-indigo-500/10 border border-transparent group-hover:border-indigo-500/30 transition-all"></span>
                    <span class="absolute -bottom-px left-1/2 -translate-x-1/2 w-0 h-px bg-gradient-to-r from-indigo-400 to-pink-400 group-hover:w-3/4 transition-all duration-300"></span>
                </a>

                @foreach($headerPages as $page)
                    @if($page->key !== 'home')
                        <a href="{{ url($page->slug) }}"
                           class="group relative px-4 py-2 font-mono text-xs uppercase tracking-widest text-gray-400 hover:text-white transition {{ request()->is($page->slug) ? 'text-white' : '' }}">
                            <span class="relative z-10">{{ $page->title }}</span>
                            <span class="absolute inset-0 rounded-full bg-indigo-500/0 group-hover:bg-indigo-500/10 border border-transparent group-hover:border-indigo-500/30 transition-all"></span>
                            <span class="absolute -bottom-px left-1/2 -translate-x-1/2 w-0 h-px bg-gradient-to-r from-indigo-400 to-pink-400 group-hover:w-3/4 transition-all duration-300"></span>
                            @if(request()->is($page->slug))
                                <span class="absolute -top-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-indigo-400 rounded-full shadow-[0_0_8px_2px_rgba(99,102,241,0.9)]"></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-1">
                <button type="button"
                        class="group relative w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition"
                        aria-label="Search">
                    <span class="absolute inset-0 rounded-full border border-transparent group-hover:border-indigo-500/40 group-hover:bg-indigo-500/10 transition-all"></span>
                    <i class="fa-solid fa-magnifying-glass relative z-10 text-sm"></i>
                </button>

                <a href="#"
                   class="group relative w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition"
                   aria-label="Cart">
                    <span class="absolute inset-0 rounded-full border border-transparent group-hover:border-indigo-500/40 group-hover:bg-indigo-500/10 transition-all"></span>
                    <i class="fa-solid fa-bag-shopping relative z-10 text-sm"></i>
                    <span class="absolute top-1 right-1 z-20 bg-gradient-to-br from-indigo-500 to-pink-500 text-white text-[9px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-[0_0_10px_rgba(236,72,153,0.6)]">0</span>
                </a>

                <button @click="mobileOpen = !mobileOpen"
                        class="md:hidden relative w-10 h-10 flex items-center justify-center text-gray-400 hover:text-white transition"
                        aria-label="Menu">
                    <i class="fa-solid text-sm" :class="mobileOpen ? 'fa-xmark' : 'fa-bars'"></i>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileOpen"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden border-t border-indigo-500/20 bg-[#05030f]/95 backdrop-blur-xl">

        <nav class="px-4 py-4 space-y-1">
            <a href="{{ url('/') }}"
               class="group flex items-center gap-3 px-4 py-3 rounded-xl font-mono text-xs uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/5 border border-transparent hover:border-indigo-500/30 transition">
                <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full shadow-[0_0_8px_2px_rgba(99,102,241,0.9)]"></span>
                <span>Home</span>
            </a>

            @foreach($headerPages as $page)
                @if($page->key !== 'home')
                    <a href="{{ url($page->slug) }}"
                       class="group flex items-center gap-3 px-4 py-3 rounded-xl font-mono text-xs uppercase tracking-widest text-gray-400 hover:text-white hover:bg-white/5 border border-transparent hover:border-indigo-500/30 transition">
                        <span class="w-1.5 h-1.5 bg-purple-400 rounded-full shadow-[0_0_8px_2px_rgba(168,85,247,0.9)]"></span>
                        <span>{{ $page->title }}</span>
                    </a>
                @endif
            @endforeach
        </nav>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
</header>

@push('styles')
<style>
@keyframes orbit-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes orbit-reverse { from { transform: rotate(360deg); } to { transform: rotate(0deg); } }
.animate-orbit-slow { animation: orbit-slow 20s linear infinite; }
.animate-orbit-reverse { animation: orbit-reverse 14s linear infinite; }
.animate-spin-slow { animation: orbit-slow 6s linear infinite; }

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.animate-shimmer { animation: shimmer 3s ease-in-out infinite; }
</style>
@endpush

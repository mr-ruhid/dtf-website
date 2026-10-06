@php
    $siteName = \App\Models\Setting::get('site_name', 'RJ Shop');
    $siteLogo = \App\Models\Setting::get('site_logo');
    $siteLogoUrl = $siteLogo ? (str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo)) : null;
    $siteDescription = \App\Models\Setting::get('site_description');
    $siteEmail = \App\Models\Setting::get('site_email');
    $sitePhone = \App\Models\Setting::get('site_phone');
    $siteAddress = \App\Models\Setting::get('site_address');

    $socials = [
        'facebook_url' => 'fa-facebook-f',
        'instagram_url' => 'fa-instagram',
        'twitter_url' => 'fa-x-twitter',
        'youtube_url' => 'fa-youtube',
        'linkedin_url' => 'fa-linkedin-in',
        'tiktok_url' => 'fa-tiktok',
    ];

    $footerMenu = \App\Models\Menu::bySlug('main-footer');

    if ($footerMenu && $footerMenu->activeItems->count()) {
        $footerNavItems = $footerMenu->activeItems;
    } else {
        $footerNavItems = collect();
        $footerNavItems->push((object) [
            'label' => 'Home',
            'url' => '/',
            'target' => '_self',
            'icon' => null,
        ]);
        foreach (\App\Models\Page::footer()->get() as $p) {
            $footerNavItems->push((object) [
                'label' => $p->title,
                'url' => '/' . $p->slug,
                'target' => '_self',
                'icon' => null,
            ]);
        }
    }
@endphp

<footer class="relative bg-[#05030f] text-gray-400 overflow-hidden mt-0">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(99,102,241,0.6) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.6) 1px, transparent 1px); background-size: 60px 60px;"></div>

    <div class="absolute -top-40 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute -bottom-40 right-1/4 w-96 h-96 bg-pink-600/10 rounded-full blur-[120px]"></div>

    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/60 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 md:pt-20 pb-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

            <div>
                <a href="{{ url('/') }}" class="group flex items-center gap-3 mb-5">
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
                    </div>
                    @if(!$siteLogoUrl)
                        <span class="text-lg font-black tracking-tight text-white">
                            {{ $siteName }}<span class="text-indigo-400">.</span>
                        </span>
                    @endif
                </a>

                @if($siteDescription)
                    <p class="text-sm leading-relaxed text-gray-500 mb-6">{{ $siteDescription }}</p>
                @endif

                @if(array_filter(array_map(fn($k) => \App\Models\Setting::get($k), array_keys($socials))))
                    <div class="flex items-center gap-2 flex-wrap">
                        @foreach($socials as $key => $icon)
                            @php $url = \App\Models\Setting::get($key); @endphp
                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener"
                                   class="group relative w-9 h-9 flex items-center justify-center rounded-full border border-white/10 bg-white/[0.03] text-gray-400 hover:text-white hover:border-indigo-500/60 hover:bg-indigo-500/10 transition-all duration-300 hover:scale-110"
                                   aria-label="{{ $key }}">
                                    <span class="absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500 to-pink-500 opacity-0 group-hover:opacity-20 blur-md transition"></span>
                                    <i class="fa-brands {{ $icon }} relative z-10 text-sm"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <div class="font-mono text-[10px] text-indigo-400 uppercase tracking-[0.3em] mb-5">// Navigation</div>
                <ul class="space-y-3">
                    @foreach($footerNavItems as $item)
                        <li>
                            <a href="{{ $item->url }}" target="{{ $item->target ?? '_self' }}"
                               class="group inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white transition">
                                <span class="w-1 h-1 bg-purple-400 rounded-full opacity-0 group-hover:opacity-100 shadow-[0_0_8px_2px_rgba(168,85,247,0.9)] transition"></span>
                                @if(!empty($item->icon))
                                    <i class="fa-solid {{ $item->icon }} text-xs"></i>
                                @endif
                                <span>{{ $item->label }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <div class="font-mono text-[10px] text-indigo-400 uppercase tracking-[0.3em] mb-5">// Contact</div>
                <ul class="space-y-4">
                    @if($siteAddress)
                        <li class="flex items-start gap-3 text-sm text-gray-400 group">
                            <div class="w-8 h-8 rounded-lg border border-white/10 bg-white/[0.03] flex items-center justify-center shrink-0 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/10 transition">
                                <i class="fa-solid fa-location-dot text-indigo-400 text-xs"></i>
                            </div>
                            <span class="pt-1.5 leading-relaxed">{{ $siteAddress }}</span>
                        </li>
                    @endif
                    @if($sitePhone)
                        <li class="flex items-start gap-3 text-sm text-gray-400 group">
                            <div class="w-8 h-8 rounded-lg border border-white/10 bg-white/[0.03] flex items-center justify-center shrink-0 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/10 transition">
                                <i class="fa-solid fa-phone text-indigo-400 text-xs"></i>
                            </div>
                            <a href="tel:{{ $sitePhone }}" class="pt-1.5 hover:text-white transition font-mono">{{ $sitePhone }}</a>
                        </li>
                    @endif
                    @if($siteEmail)
                        <li class="flex items-start gap-3 text-sm text-gray-400 group">
                            <div class="w-8 h-8 rounded-lg border border-white/10 bg-white/[0.03] flex items-center justify-center shrink-0 group-hover:border-indigo-500/50 group-hover:bg-indigo-500/10 transition">
                                <i class="fa-solid fa-envelope text-indigo-400 text-xs"></i>
                            </div>
                            <a href="mailto:{{ $siteEmail }}" class="pt-1.5 hover:text-white transition font-mono break-all">{{ $siteEmail }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div>
                <div class="font-mono text-[10px] text-indigo-400 uppercase tracking-[0.3em] mb-5">// Subscribe</div>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">Join the quantum network. Get updates, drops, and offers.</p>

                <form method="POST" action="#" class="relative">
                    @csrf
                    <div class="relative flex items-center">
                        <input type="email" name="email" placeholder="your@email.com" required
                               class="w-full px-4 py-3 pr-12 bg-white/[0.03] border border-white/10 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500/60 focus:bg-indigo-500/[0.05] focus:shadow-[0_0_20px_rgba(99,102,241,0.3)] transition-all font-mono">
                        <button type="submit"
                                class="absolute right-1.5 w-9 h-9 flex items-center justify-center rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white hover:shadow-[0_0_20px_rgba(99,102,241,0.6)] hover:scale-105 transition-all duration-300"
                                aria-label="Subscribe">
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-6 flex items-center gap-2 font-mono text-[10px] text-gray-600">
                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_2px_rgba(52,211,153,0.8)]"></span>
                    <span>NETWORK STATUS: ONLINE</span>
                </div>
            </div>
        </div>
    </div>

    <div class="relative border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="font-mono text-[11px] text-gray-600 tracking-wider">
                &copy; {{ date('Y') }} <span class="text-gray-400">{{ $siteName }}</span> — All rights reserved.
            </p>

            <div class="flex items-center gap-4 font-mono text-[10px] text-gray-600 uppercase tracking-widest">
                <span class="hidden md:flex items-center gap-1.5">
                    <span class="w-1 h-1 bg-indigo-400 rounded-full"></span>
                    Latency: 0ms
                </span>
                <span class="hidden md:flex items-center gap-1.5">
                    <span class="w-1 h-1 bg-purple-400 rounded-full"></span>
                    ψ-stable
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="w-1 h-1 bg-pink-400 rounded-full"></span>
                    v1.1
                </span>
            </div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-pink-500/40 to-transparent"></div>
</footer>

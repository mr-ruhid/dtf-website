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

    $footerPages = \App\Models\Page::footer()->get();
@endphp

<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4">
                    @if($siteLogoUrl)
                        <img src="{{ $siteLogoUrl }}" alt="{{ $siteName }}" class="h-10 w-auto brightness-0 invert">
                    @else
                        <span class="text-xl font-bold text-white">{{ $siteName }}</span>
                    @endif
                </a>
                @if($siteDescription)
                    <p class="text-sm text-gray-400 leading-relaxed">{{ $siteDescription }}</p>
                @endif

                @if(array_filter(array_map(fn($k) => \App\Models\Setting::get($k), array_keys($socials))))
                    <div class="flex items-center gap-3 mt-5">
                        @foreach($socials as $key => $icon)
                            @php $url = \App\Models\Setting::get($key); @endphp
                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-800 hover:bg-indigo-600 text-gray-300 hover:text-white transition" aria-label="{{ $key }}">
                                    <i class="fa-brands {{ $icon }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-white transition">Home</a>
                    </li>
                    @foreach($footerPages as $page)
                        <li>
                            <a href="{{ url($page->slug) }}" class="text-sm text-gray-400 hover:text-white transition">{{ $page->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Contact</h3>
                <ul class="space-y-3">
                    @if($siteAddress)
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fa-solid fa-location-dot mt-1 text-indigo-500"></i>
                            <span>{{ $siteAddress }}</span>
                        </li>
                    @endif
                    @if($sitePhone)
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fa-solid fa-phone mt-1 text-indigo-500"></i>
                            <a href="tel:{{ $sitePhone }}" class="hover:text-white transition">{{ $sitePhone }}</a>
                        </li>
                    @endif
                    @if($siteEmail)
                        <li class="flex items-start gap-3 text-sm text-gray-400">
                            <i class="fa-solid fa-envelope mt-1 text-indigo-500"></i>
                            <a href="mailto:{{ $siteEmail }}" class="hover:text-white transition">{{ $siteEmail }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Newsletter</h3>
                <p class="text-sm text-gray-400 mb-3">Subscribe for updates and offers.</p>
                <form method="POST" action="#" class="flex">
                    @csrf
                    <input type="email" name="email" placeholder="Your email" required
                           class="flex-1 min-w-0 px-3 py-2 bg-gray-800 border border-gray-700 rounded-l-lg text-sm text-white placeholder-gray-500 focus:outline-none focus:border-indigo-500">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 rounded-r-lg transition" aria-label="Subscribe">
                        <i class="fa-solid fa-paper-plane text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.
            </p>
            <p class="text-xs text-gray-500">
                Powered by RJ Shop
            </p>
        </div>
    </div>
</footer>

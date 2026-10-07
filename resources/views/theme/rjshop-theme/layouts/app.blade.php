<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('meta_title', \App\Models\Setting::get('meta_title', \App\Models\Setting::get('site_name', 'RJ Shop')))</title>
    <meta name="description" content="@yield('meta_description', \App\Models\Setting::get('meta_description', \App\Models\Setting::get('site_description')))">
    <meta name="keywords" content="@yield('meta_keywords', \App\Models\Setting::get('meta_keywords'))">

    @if(\App\Models\Setting::get('robots_index') === '0')
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
    @endif

    <meta property="og:title" content="@yield('meta_title', \App\Models\Setting::get('meta_title', \App\Models\Setting::get('site_name')))">
    <meta property="og:description" content="@yield('meta_description', \App\Models\Setting::get('meta_description'))">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @php
        $ogImage = \App\Models\Setting::get('og_image');
        $ogImageUrl = $ogImage ? (str_starts_with($ogImage, 'http') ? $ogImage : asset('storage/' . $ogImage)) : null;
    @endphp
    @if($ogImageUrl)
        <meta property="og:image" content="{{ $ogImageUrl }}">
    @endif

    @php
        $favicon = \App\Models\Setting::get('site_favicon');
        $faviconUrl = $favicon ? (str_starts_with($favicon, 'http') ? $favicon : asset('storage/' . $favicon)) : null;
    @endphp
    @if($faviconUrl)
        <link rel="icon" href="{{ $faviconUrl }}">
    @endif

    <style>
        [x-cloak] { display: none !important; }
        html { background: #05030f; }
        body {
            background: #05030f;
            margin: 0;
            visibility: hidden;
            opacity: 0;
        }
        body.rj-ready {
            visibility: visible;
            opacity: 1;
            transition: opacity .15s ease-in;
        }
        #rj-boot {
            position: fixed;
            inset: 0;
            background: #05030f;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .25s ease-out;
        }
        #rj-boot.rj-hide {
            opacity: 0;
            pointer-events: none;
        }
        #rj-boot-inner {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid rgba(99,102,241,0.25);
            border-top-color: #6366f1;
            animation: rj-spin .8s linear infinite;
        }
        @keyframes rj-spin { to { transform: rotate(360deg); } }
    </style>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('theme/rjshop-theme/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/rjshop-theme/css/widgets.css') }}">

    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800 antialiased min-h-screen flex flex-col">

    <div id="rj-boot"><div id="rj-boot-inner"></div></div>

    @include('theme.rjshop-theme.partials.header')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('theme.rjshop-theme.partials.footer')

    <script src="{{ asset('theme/rjshop-theme/js/quantum-field.js') }}"></script>

    <script>
        (function () {
            function ready() {
                requestAnimationFrame(function () {
                    document.body.classList.add('rj-ready');
                    var boot = document.getElementById('rj-boot');
                    if (boot) {
                        boot.classList.add('rj-hide');
                        setTimeout(function () { boot.remove(); }, 300);
                    }
                });
            }

            if (document.readyState === 'complete') {
                ready();
            } else {
                window.addEventListener('load', ready);
                setTimeout(ready, 1500);
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>

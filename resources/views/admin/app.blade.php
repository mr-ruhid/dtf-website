@php
    // ───────────────────────── Loqo (toxunulmaz) ─────────────────────────
    $rjLogo = 'https://raw.githubusercontent.com/mr-ruhid/rare-logos-vault/main/logo/cms/rj-shop/rj_shop_lite.svg';

    // ───────────────────────── Favicon ─────────────────────────
    // Ayarlarda seçilibsə onu götürür; yoxdursa / fayl tapılmırsa public/icon/favicon.png
    $rjFavicon = asset('icon/favicon.png');
    $rjFaviconPath = public_path('icon/favicon.png');

    try {
        $faviconSetting = \App\Models\Setting::where('key', 'favicon')->value('value');

        if ($faviconSetting) {
            if (\Illuminate\Support\Str::startsWith($faviconSetting, ['http://', 'https://', '//'])) {
                $rjFavicon = $faviconSetting;
                $rjFaviconPath = null;
            } else {
                $clean = preg_replace('#^(public/|storage/)#', '', ltrim($faviconSetting, '/'));
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($clean)) {
                    $rjFavicon = asset('storage/' . $clean);
                    $rjFaviconPath = \Illuminate\Support\Facades\Storage::disk('public')->path($clean);
                }
            }
        }
    } catch (\Throwable $e) {
        // Setting cədvəli/modeli problemi layout-u dağıtmasın
    }

    // Cache-buster: favicon dəyişəndə brauzer yenisini götürsün
    $rjFaviconUrl = $rjFavicon;
    if ($rjFaviconPath && is_file($rjFaviconPath)) {
        $rjFaviconUrl .= (str_contains($rjFavicon, '?') ? '&' : '?') . 'v=' . filemtime($rjFaviconPath);
    }

    // Faylın formatına görə düzgün type
    $rjFaviconExt = strtolower(pathinfo(parse_url($rjFavicon, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
    $rjFaviconType = [
        'ico' => 'image/x-icon', 'png' => 'image/png', 'svg' => 'image/svg+xml',
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp', 'gif' => 'image/gif',
    ][$rjFaviconExt] ?? 'image/png';

    // ───────────────────────── Sidebar menyusu (bir yerdə idarə olunur) ─────────────────────────
    // Gözləyən sifarişlərin sayı. "status" sütunu fərqlidirsə, aşağıdakı sətri öz sütununa uyğunlaşdır.
    $ordersPending = 0;
    try {
        if (class_exists(\App\Models\Order::class)) {
            $ordersPending = (int) \App\Models\Order::where('status', 'pending')->count();
        }
    } catch (\Throwable $e) {
        $ordersPending = 0;
    }

    $nav = [
        ['title' => 'Main', 'tone' => 'slate', 'items' => [
            ['key' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fa-gauge-high', 'route' => 'admin.dashboard', 'match' => ['admin.dashboard']],
            ['key' => 'products', 'label' => 'Products', 'icon' => 'fa-cube', 'route' => 'admin.products.index',
                'match' => ['admin.products*', 'admin.categories*', 'admin.models*', 'admin.attributes*', 'admin.print-zones*'],
                'children' => [
                    ['label' => 'All Products', 'route' => 'admin.products.index', 'match' => ['admin.products*']],
                    ['label' => 'Models', 'route' => 'admin.models.index', 'match' => ['admin.models*']],
                    ['label' => 'Categories', 'route' => 'admin.categories.index', 'match' => ['admin.categories*']],
                    ['label' => 'Attributes', 'route' => 'admin.attributes.index', 'match' => ['admin.attributes*']],
                    ['label' => 'Print Zones', 'route' => 'admin.print-zones.index', 'match' => ['admin.print-zones*']],
                ]],
            ['key' => 'orders', 'label' => 'Orders', 'icon' => 'fa-receipt', 'route' => 'admin.orders.index', 'match' => ['admin.orders*'], 'count' => $ordersPending],
        ]],
        ['title' => 'Content', 'tone' => 'slate', 'items' => [
            ['key' => 'blog', 'label' => 'Blog', 'icon' => 'fa-newspaper', 'route' => 'admin.blog.index', 'match' => ['admin.blog*']],
            ['key' => 'pages', 'label' => 'Pages', 'icon' => 'fa-file-lines', 'route' => 'admin.pages.index', 'match' => ['admin.pages*']],
            ['key' => 'widgets', 'label' => 'Widgets', 'icon' => 'fa-puzzle-piece', 'route' => 'admin.widgets.index', 'match' => ['admin.widgets*']],
            ['key' => 'menus', 'label' => 'Menus', 'icon' => 'fa-bars-staggered', 'route' => 'admin.menus.index', 'match' => ['admin.menus*']],
            ['key' => 'gallery', 'label' => 'Gallery', 'icon' => 'fa-images', 'route' => 'admin.gallery.index', 'match' => ['admin.gallery*']],
            ['key' => 'faqs', 'label' => 'FAQ', 'icon' => 'fa-circle-question', 'route' => 'admin.faqs.index', 'match' => ['admin.faqs*']],
            ['key' => 'sliders', 'label' => 'Sliders', 'icon' => 'fa-panorama', 'route' => 'admin.sliders.index', 'match' => ['admin.sliders*']],
        ]],
        ['title' => 'Shipping', 'tone' => 'slate', 'items' => [
            ['key' => 'branches', 'label' => 'Branches', 'icon' => 'fa-store', 'route' => 'admin.branches.index', 'match' => ['admin.branches*']],
            ['key' => 'delivery-zones', 'label' => 'Delivery Zones', 'icon' => 'fa-map-location-dot', 'route' => 'admin.delivery-zones.index', 'match' => ['admin.delivery-zones*']],
            ['key' => 'delivery-rates', 'label' => 'Delivery Rates', 'icon' => 'fa-truck-fast', 'route' => 'admin.delivery-rates.index', 'match' => ['admin.delivery-rates*']],
        ]],
        ['title' => 'System', 'tone' => 'slate', 'items' => [
            ['key' => 'support', 'label' => 'Technical Support', 'icon' => 'fa-headset', 'route' => 'admin.support.index', 'match' => ['admin.support*'], 'dot' => true],
            ['key' => 'settings', 'label' => 'Settings', 'icon' => 'fa-sliders', 'route' => 'admin.settings.index', 'match' => ['admin.settings.index']],
            ['key' => 'storage', 'label' => 'Storage', 'icon' => 'fa-database', 'route' => 'admin.storage.index', 'match' => ['admin.storage*']],
            ['key' => 'design-pricing', 'label' => 'Design Pricing', 'icon' => 'fa-calculator', 'route' => 'admin.settings.design-pricing', 'match' => ['admin.settings.design-pricing*'], 'badge' => 'NEW'],
        ]],
        ['title' => 'Finance', 'tone' => 'emerald', 'items' => [
            ['key' => 'payments', 'label' => 'Payments', 'icon' => 'fa-credit-card', 'route' => 'admin.payments.index', 'match' => ['admin.payments*'], 'variant' => 'payments'],
        ]],
        ['title' => 'Extra', 'tone' => 'amber', 'items' => [
            ['key' => 'services', 'label' => 'Services', 'icon' => 'fa-rocket', 'route' => 'admin.services.index', 'match' => ['admin.services*'], 'variant' => 'services'],
        ]],
    ];

    // url / active / labels hesabla + Alpine üçün düz siyahı çıxar
    $navFlat = [];
    foreach ($nav as $gi => $group) {
        $groupLabels = [];
        foreach ($group['items'] as $ii => $item) {
            $item['url'] = route($item['route']);
            $item['active'] = request()->routeIs(...$item['match']);
            $labels = [$item['label']];

            if (!empty($item['children'])) {
                foreach ($item['children'] as $ci => $child) {
                    $child['url'] = route($child['route']);
                    $child['active'] = request()->routeIs(...$child['match']);
                    $item['children'][$ci] = $child;
                    $labels[] = $child['label'];
                }
            }

            $item['labels'] = $labels;
            $groupLabels = array_merge($groupLabels, $labels);
            $nav[$gi]['items'][$ii] = $item;

            $navFlat[] = [
                'key' => $item['key'], 'label' => $item['label'], 'icon' => $item['icon'],
                'url' => $item['url'], 'active' => $item['active'],
            ];
        }
        $nav[$gi]['labels'] = $groupLabels;
        $nav[$gi]['hasActive'] = collect($nav[$gi]['items'])->contains('active', true);
    }

    $toneText = ['slate' => 'text-slate-600', 'emerald' => 'text-emerald-500', 'amber' => 'text-amber-500'];
    $toneLine = ['slate' => 'glow-line', 'emerald' => 'glow-line glow-line--emerald', 'amber' => 'glow-line glow-line--amber'];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - RJ SHOP lite</title>
    <link rel="icon" type="{{ $rjFaviconType }}" href="{{ $rjFaviconUrl }}">
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- x-collapse işləməsi üçün Collapse plugin-i Alpine-dən ƏVVƏL yüklənməlidir --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak]{display:none!important;}
        .scroll-thin::-webkit-scrollbar{width:5px;}
        .scroll-thin::-webkit-scrollbar-track{background:transparent;}
        .scroll-thin::-webkit-scrollbar-thumb{background:#1e293b;border-radius:3px;}
        .scroll-thin::-webkit-scrollbar-thumb:hover{background:#334155;}
        .sidebar-bg{
            background:
                radial-gradient(circle at 0% 0%, rgba(99,102,241,0.08), transparent 45%),
                radial-gradient(circle at 100% 100%, rgba(168,85,247,0.06), transparent 45%),
                #0a0f1c;
        }
        .nav-item{position:relative;transition:all .2s cubic-bezier(.4,0,.2,1);}
        .nav-item::before{
            content:'';position:absolute;left:0;top:50%;transform:translateY(-50%) scaleY(0);
            width:3px;height:60%;background:linear-gradient(180deg,#6366f1,#a855f7);
            border-radius:0 3px 3px 0;transition:transform .25s cubic-bezier(.4,0,.2,1);
        }
        .nav-item.active::before{transform:translateY(-50%) scaleY(1);}
        .nav-item.active{
            background:linear-gradient(90deg, rgba(99,102,241,0.12), rgba(99,102,241,0.02) 60%, transparent);
            color:#fff;
        }
        .nav-item:not(.active):hover{background:rgba(30,41,59,0.5);color:#e2e8f0;}
        .icon-box{
            width:34px;height:34px;display:flex;align-items:center;justify-content:center;flex-shrink:0;
            border-radius:9px;background:rgba(30,41,59,0.6);color:#64748b;
            transition:all .2s cubic-bezier(.4,0,.2,1);font-size:13px;
        }
        .nav-item:hover .icon-box{background:rgba(99,102,241,0.15);color:#a5b4fc;transform:translateX(1px);}
        .nav-item.active .icon-box{
            background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;
            box-shadow:0 4px 12px -2px rgba(99,102,241,0.5);
        }
        .glow-line{height:1px;background:linear-gradient(90deg,transparent,#6366f1 50%,transparent);opacity:.4;}
        .glow-line--emerald{background:linear-gradient(90deg,rgba(16,185,129,0.6),transparent);opacity:1;}
        .glow-line--amber{background:linear-gradient(90deg,rgba(245,158,11,0.6),transparent);opacity:1;}

        /* ===== Sidebar əlavələri ===== */
        .nav-row{position:relative;}
        .pin-btn{
            position:absolute;right:8px;top:50%;transform:translateY(-50%);
            width:22px;height:22px;border-radius:7px;display:flex;align-items:center;justify-content:center;
            font-size:10px;color:#64748b;background:transparent;border:0;cursor:pointer;
            opacity:0;transition:all .18s ease;z-index:2;
        }
        .pin-btn--shift{right:26px;}
        .nav-row:hover .pin-btn,.pin-btn.pinned{opacity:1;}
        .pin-btn:hover{background:rgba(99,102,241,.2);color:#c7d2fe;}
        .pin-btn.pinned{color:#fbbf24;transform:translateY(-50%) rotate(35deg);}
        .pin-btn.pinned:hover{background:rgba(251,191,36,.15);color:#fcd34d;}
        .group-head{transition:opacity .2s;}
        .group-head:hover .group-title{color:#cbd5e1;}
        .kbd{
            font-family:ui-monospace,monospace;font-size:9px;font-weight:700;color:#64748b;
            background:rgba(30,41,59,.8);border:1px solid rgba(51,65,85,.7);
            padding:1px 5px;border-radius:5px;line-height:14px;
        }
        .sb-handle{
            position:absolute;top:76px;right:-12px;width:24px;height:24px;border-radius:50%;z-index:50;
            display:none;align-items:center;justify-content:center;font-size:9px;color:#94a3b8;
            background:#0f172a;border:1px solid rgba(51,65,85,.9);cursor:pointer;
            box-shadow:0 4px 12px -2px rgba(0,0,0,.4);transition:all .2s ease;
        }
        .sb-handle:hover{color:#fff;background:#6366f1;border-color:#6366f1;transform:scale(1.1);}
        @media (min-width:768px){ .sb-handle{display:flex;} }

        .nav-services{
            position:relative;
            background: linear-gradient(135deg, rgba(245,158,11,0.08) 0%, rgba(236,72,153,0.06) 100%);
            border: 1px solid rgba(245,158,11,0.2);
            border-radius: 12px;
            margin-top: 6px;
            transition: all .3s cubic-bezier(.4,0,.2,1);
        }
        .nav-services:hover{
            background: linear-gradient(135deg, rgba(245,158,11,0.15) 0%, rgba(236,72,153,0.12) 100%);
            border-color: rgba(245,158,11,0.4);
            box-shadow: 0 0 20px -4px rgba(245,158,11,0.35);
        }
        .nav-services.active{
            background: linear-gradient(135deg, rgba(245,158,11,0.18) 0%, rgba(236,72,153,0.15) 100%);
            border-color: rgba(245,158,11,0.5);
            box-shadow: 0 0 24px -4px rgba(245,158,11,0.4);
        }
        .nav-services .icon-box{
            background: linear-gradient(135deg,#f59e0b,#ec4899);
            color:#fff;
            box-shadow: 0 4px 12px -2px rgba(245,158,11,0.5);
        }
        .nav-services.active .icon-box,
        .nav-services:hover .icon-box{
            background: linear-gradient(135deg,#fbbf24,#f472b6);
            transform: scale(1.05);
        }
        .nav-services::after{
            content:'';
            position:absolute;
            top:6px;right:6px;
            width:6px;height:6px;
            border-radius:50%;
            background:#fbbf24;
            box-shadow:0 0 8px 2px rgba(251,191,36,0.8);
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot{
            0%,100%{opacity:.6;transform:scale(1);}
            50%{opacity:1;transform:scale(1.2);}
        }
        .nav-services .badge-extra{
            font-family: ui-monospace, monospace;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #fbbf24;
            background: rgba(251,191,36,0.15);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(251,191,36,0.3);
        }

        .nav-payments{
            position:relative;
            background: linear-gradient(135deg, rgba(16,185,129,0.08) 0%, rgba(59,130,246,0.06) 100%);
            border: 1px solid rgba(16,185,129,0.2);
            border-radius: 12px;
            margin-top: 6px;
            transition: all .3s cubic-bezier(.4,0,.2,1);
        }
        .nav-payments:hover{
            background: linear-gradient(135deg, rgba(16,185,129,0.15) 0%, rgba(59,130,246,0.12) 100%);
            border-color: rgba(16,185,129,0.4);
            box-shadow: 0 0 20px -4px rgba(16,185,129,0.35);
        }
        .nav-payments.active{
            background: linear-gradient(135deg, rgba(16,185,129,0.18) 0%, rgba(59,130,246,0.15) 100%);
            border-color: rgba(16,185,129,0.5);
            box-shadow: 0 0 24px -4px rgba(16,185,129,0.4);
        }
        .nav-payments .icon-box{
            background: linear-gradient(135deg,#10b981,#3b82f6);
            color:#fff;
            box-shadow: 0 4px 12px -2px rgba(16,185,129,0.5);
        }
        .nav-payments.active .icon-box,
        .nav-payments:hover .icon-box{
            background: linear-gradient(135deg,#34d399,#60a5fa);
            transform: scale(1.05);
        }
        .nav-payments .badge-pay{
            font-family: ui-monospace, monospace;
            font-size: 8px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #6ee7b7;
            background: rgba(16,185,129,0.15);
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid rgba(16,185,129,0.3);
        }

        /* ===== RJ AI Tab (ekranın kənarına yapışan AI Chat düyməsi) ===== */
        .rj-ai{
            position:fixed; top:32vh; z-index:9999;
            width:48px; padding:16px 0 14px;
            display:flex; flex-direction:column; align-items:center; gap:14px;
            border:0; cursor:pointer; color:#fff; isolation:isolate;
            background:linear-gradient(160deg,#6366f1 0%,#8b5cf6 35%,#d946ef 65%,#6366f1 100%);
            background-size:100% 300%;
            box-shadow:-8px 10px 30px -8px rgba(99,102,241,.8), inset 0 1px 0 rgba(255,255,255,.35);
            transition:width .35s cubic-bezier(.34,1.56,.64,1), box-shadow .3s ease, transform .35s cubic-bezier(.34,1.56,.64,1);
        }
        .rj-ai--right{
            right:0; border-radius:20px 0 0 20px;
            animation:rj-ai-bg 6s ease-in-out infinite, rj-ai-nudge-r 6s ease-in-out infinite 2s;
        }
        .rj-ai--left{
            left:0; border-radius:0 20px 20px 0;
            box-shadow:8px 10px 30px -8px rgba(99,102,241,.8), inset 0 1px 0 rgba(255,255,255,.35);
            animation:rj-ai-bg 6s ease-in-out infinite, rj-ai-nudge-l 6s ease-in-out infinite 2s;
        }
        .rj-ai:hover{
            width:58px; transform:translateX(0);
            animation:rj-ai-bg 3s ease-in-out infinite;
            box-shadow:-10px 14px 40px -6px rgba(217,70,239,.85), inset 0 1px 0 rgba(255,255,255,.45);
        }
        .rj-ai--left:hover{ box-shadow:10px 14px 40px -6px rgba(217,70,239,.85), inset 0 1px 0 rgba(255,255,255,.45); }
        .rj-ai:active{ filter:brightness(.92); }

        .rj-ai__shine{ position:absolute; inset:0; border-radius:inherit; overflow:hidden; z-index:-1; pointer-events:none; }
        .rj-ai__shine::before{
            content:''; position:absolute; left:-20%; width:140%; height:38%; top:-45%;
            background:linear-gradient(180deg,transparent,rgba(255,255,255,.38),transparent);
            transform:skewY(-14deg);
            animation:rj-ai-sweep 3.8s ease-in-out infinite;
        }

        .rj-ai__orb{
            position:relative; width:32px; height:32px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            background:rgba(255,255,255,.2); border:1px solid rgba(255,255,255,.45);
            backdrop-filter:blur(6px);
            font-size:14px;
        }
        .rj-ai__orb i{ animation:rj-ai-twinkle 2.6s ease-in-out infinite; }
        .rj-ai__orb::before,
        .rj-ai__orb::after{
            content:''; position:absolute; inset:-1px; border-radius:50%;
            border:1.5px solid rgba(255,255,255,.7);
            animation:rj-ai-ripple 2.4s ease-out infinite;
        }
        .rj-ai__orb::after{ animation-delay:1.2s; }

        .rj-ai__dot{
            position:absolute; top:-3px; right:-3px; width:10px; height:10px; border-radius:50%;
            background:#34d399; border:2px solid #6d28d9;
            animation:rj-ai-dot 2s ease-in-out infinite;
        }

        .rj-ai__label{
            writing-mode:vertical-rl; transform:rotate(180deg);
            font-size:11px; font-weight:800; letter-spacing:.3em; text-transform:uppercase;
            text-shadow:0 1px 8px rgba(0,0,0,.25);
        }

        @keyframes rj-ai-bg{ 0%,100%{ background-position:0 0; } 50%{ background-position:0 100%; } }
        @keyframes rj-ai-sweep{ 0%{ top:-45%; } 55%,100%{ top:120%; } }
        @keyframes rj-ai-ripple{ 0%{ transform:scale(1); opacity:.7; } 100%{ transform:scale(2); opacity:0; } }
        @keyframes rj-ai-twinkle{ 0%,100%{ transform:scale(1) rotate(0); } 50%{ transform:scale(1.18) rotate(12deg); } }
        @keyframes rj-ai-dot{ 0%,100%{ box-shadow:0 0 0 0 rgba(52,211,153,.8); } 50%{ box-shadow:0 0 0 7px rgba(52,211,153,0); } }
        @keyframes rj-ai-nudge-r{ 0%,82%,100%{ transform:translateX(5px); } 88%{ transform:translateX(-4px); } 94%{ transform:translateX(2px); } }
        @keyframes rj-ai-nudge-l{ 0%,82%,100%{ transform:translateX(-5px); } 88%{ transform:translateX(4px); } 94%{ transform:translateX(-2px); } }

        @media (max-width:640px){
            .rj-ai{ width:40px; top:40vh; padding:12px 0; }
            .rj-ai__label{ display:none; }
            .rj-ai__orb{ width:28px; height:28px; font-size:12px; }
        }
        @media (prefers-reduced-motion:reduce){
            .rj-ai, .rj-ai *, .rj-ai::before, .rj-ai__orb::before, .rj-ai__orb::after, .rj-ai__shine::before{ animation:none !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-gray-800 antialiased"
      x-data="rjSidebar(@js(['items' => $navFlat]))">

    <div class="flex h-screen overflow-hidden">

        {{-- ═════════════════ SIDEBAR (desktop + mobil eyni element) ═════════════════ --}}
        <aside id="rj-sidebar"
               :class="[
                   mobileOpen ? 'translate-x-0' : '-translate-x-full',
                   sidebarOpen ? 'md:w-[272px]' : 'md:w-[80px]'
               ]"
               class="fixed inset-y-0 left-0 z-50 w-[272px] flex flex-col sidebar-bg text-slate-400 border-r border-slate-800/50
                      transition-all duration-300 md:relative md:z-40 md:translate-x-0 md:inset-auto shrink-0">

            {{-- kənardakı yığ/aç düyməsi (yalnız desktop) --}}
            <button type="button" class="sb-handle" @click="sidebarOpen = !sidebarOpen"
                    :title="sidebarOpen ? 'Collapse (Ctrl+B)' : 'Expand (Ctrl+B)'">
                <i class="fa-solid fa-chevron-left transition-transform duration-300" :class="sidebarOpen ? '' : 'rotate-180'"></i>
            </button>

            {{-- Loqo --}}
            <div class="h-16 flex items-center px-5 border-b border-slate-800/50 shrink-0 relative">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center w-full min-w-0" title="RJ SHOP">
                    <img src="{{ $rjLogo }}" alt="RJ SHOP"
                         class="h-9 w-auto max-w-full object-contain object-left transition-transform duration-300 group-hover:scale-[1.04]">
                </a>
                <button type="button" @click="mobileOpen = false"
                        class="md:hidden ml-2 w-8 h-8 shrink-0 rounded-lg flex items-center justify-center text-slate-500 hover:text-white hover:bg-slate-800 transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            {{-- Axtarış --}}
            <div class="px-3 pt-3 shrink-0">
                <div x-show="wide" class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[11px] text-slate-500 pointer-events-none"></i>
                    <input x-ref="search" x-model="q" type="text" autocomplete="off" spellcheck="false"
                           @keydown.enter.prevent="goFirst()"
                           @keydown.escape="q = ''; $el.blur()"
                           placeholder="Search menu…"
                           class="w-full bg-slate-800/40 border border-slate-700/40 rounded-xl pl-9 pr-16 py-2 text-[12px] text-slate-200 placeholder-slate-500
                                  focus:outline-none focus:border-indigo-500/60 focus:ring-2 focus:ring-indigo-500/20 transition">
                    <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        <button type="button" x-show="q !== ''" x-cloak @click="q = ''; $refs.search.focus()"
                                class="w-5 h-5 rounded-md flex items-center justify-center text-slate-500 hover:text-white hover:bg-slate-700/60 transition">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                        </button>
                        <span x-show="q === ''" class="kbd">Ctrl K</span>
                    </div>
                </div>
                <button type="button" x-show="!wide" x-cloak
                        @click="focusSearch()"
                        @mouseenter="showTip($event, 'Search (Ctrl+K)')" @mouseleave="hideTip()"
                        class="nav-item w-full flex items-center justify-center py-2 rounded-xl">
                    <div class="icon-box"><i class="fa-solid fa-magnifying-glass"></i></div>
                </button>
            </div>

            {{-- Menyu --}}
            <nav id="rj-nav" class="flex-1 overflow-y-auto overflow-x-hidden py-2 px-3 scroll-thin" @scroll.passive="hideTip()">

                {{-- Pinned --}}
                <div x-show="pinnedItems.length && q === ''" x-cloak>
                    <div x-show="wide" class="flex items-center gap-2 px-3 mt-2 mb-1.5">
                        <i class="fa-solid fa-thumbtack text-[8px] text-amber-400 rotate-45"></i>
                        <span class="text-[9px] uppercase tracking-[0.15em] text-amber-400/90 font-bold">Pinned</span>
                        <div class="flex-1 glow-line glow-line--amber"></div>
                    </div>
                    <div x-show="!wide" class="mx-3 my-2 glow-line glow-line--amber"></div>

                    <div class="space-y-0.5">
                        <template x-for="p in pinnedItems" :key="p.key">
                            <div class="nav-row">
                                <a :href="p.url" data-nav-link
                                   @mouseenter="showTip($event, p.label)" @mouseleave="hideTip()"
                                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl"
                                   :class="[p.active ? 'active' : '', wide ? 'pr-9' : '']">
                                    <div class="icon-box"><i class="fa-solid" :class="p.icon"></i></div>
                                    <span x-show="wide" class="text-[13px] font-medium flex-1 truncate" x-text="p.label"></span>
                                </a>
                                <button type="button" x-show="wide" @click.stop.prevent="togglePin(p.key)"
                                        class="pin-btn pinned" title="Unpin">
                                    <i class="fa-solid fa-thumbtack"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Qruplar --}}
                @foreach($nav as $group)
                    <div x-data="{ gl: @js($group['labels']) }" x-show="match(gl)">

                        <button type="button" x-show="wide" @click="toggleGroup(@js($group['title']))"
                                class="group-head w-full flex items-center gap-2 px-3 mt-4 mb-1.5">
                            <span class="group-title text-[9px] uppercase tracking-[0.15em] {{ $toneText[$group['tone']] }} font-bold transition-colors">{{ $group['title'] }}</span>
                            <div class="flex-1 {{ $toneLine[$group['tone']] }}"></div>
                            <i class="fa-solid fa-chevron-down text-[8px] text-slate-600 transition-transform duration-300"
                               :class="isOpen(@js($group['title']), @js($group['hasActive'])) ? '' : '-rotate-90'"></i>
                        </button>
                        <div x-show="!wide" class="mx-3 my-3 {{ $toneLine[$group['tone']] }}"></div>

                        <div x-show="isOpen(@js($group['title']), @js($group['hasActive']))" x-collapse class="space-y-0.5">
                            @foreach($group['items'] as $item)
                                @php
                                    $variant = $item['variant'] ?? null;
                                    $rowClass = match($variant) {
                                        'payments' => 'nav-payments nav-item flex items-center gap-3 px-3 py-2.5',
                                        'services' => 'nav-services nav-item flex items-center gap-3 px-3 py-2.5',
                                        default => 'nav-item flex items-center gap-3 px-3 py-2 rounded-xl',
                                    };
                                    $labelClass = match($variant) {
                                        'payments' => 'text-[13px] font-semibold text-emerald-100',
                                        'services' => 'text-[13px] font-semibold text-amber-100',
                                        default => 'text-[13px] font-medium',
                                    };
                                @endphp

                                @if(!empty($item['children']))
                                    {{-- Alt menyulu element --}}
                                    <div x-data="{ sub: @js($item['active']), labels: @js($item['labels']) }"
                                         x-effect="if (q !== '') sub = true"
                                         x-show="match(labels)">
                                        <div class="nav-row">
                                            <button type="button"
                                                    @click="if (!wide) { sidebarOpen = true; sub = true } else { sub = !sub }"
                                                    @mouseenter="showTip($event, @js($item['label']))" @mouseleave="hideTip()"
                                                    class="{{ $rowClass }} w-full {{ $item['active'] ? 'active' : '' }}"
                                                    :class="wide ? 'pr-14' : ''"
                                                    {!! $item['active'] ? 'data-nav-active' : '' !!}>
                                                <div class="icon-box"><i class="fa-solid {{ $item['icon'] }}"></i></div>
                                                <span x-show="wide" class="{{ $labelClass }} flex-1 text-left">{{ $item['label'] }}</span>
                                                <i x-show="wide" :class="sub ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-[9px] transition-transform"></i>
                                            </button>
                                            <button type="button" x-show="wide" @click.stop.prevent="togglePin(@js($item['key']))"
                                                    class="pin-btn pin-btn--shift" :class="isPinned(@js($item['key'])) && 'pinned'"
                                                    :title="isPinned(@js($item['key'])) ? 'Unpin' : 'Pin to top'">
                                                <i class="fa-solid fa-thumbtack"></i>
                                            </button>
                                        </div>

                                        <div x-show="sub && wide" x-collapse class="mt-1 ml-5 pl-5 border-l border-slate-800/60 space-y-0.5">
                                            @foreach($item['children'] as $child)
                                                <a href="{{ $child['url'] }}" data-nav-link
                                                   x-show="match(@js([$child['label']]))"
                                                   class="block py-1.5 px-3 text-[12px] rounded-lg transition {{ $child['active'] ? 'text-white bg-slate-800/60' : 'text-slate-500 hover:text-white hover:bg-slate-800/50' }}">{{ $child['label'] }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    {{-- Adi element --}}
                                    <div class="nav-row" x-show="match(@js($item['labels']))">
                                        <a href="{{ $item['url'] }}" data-nav-link
                                           @mouseenter="showTip($event, @js($item['label']))" @mouseleave="hideTip()"
                                           class="{{ $rowClass }} {{ $item['active'] ? 'active' : '' }}"
                                           :class="wide ? 'pr-9' : ''"
                                           {!! $item['active'] ? 'data-nav-active' : '' !!}>
                                            <div class="icon-box"><i class="fa-solid {{ $item['icon'] }}"></i></div>

                                            @if($variant)
                                                <div x-show="wide" class="flex-1 flex items-center justify-between min-w-0">
                                                    <span class="{{ $labelClass }}">{{ $item['label'] }}</span>
                                                    <span class="{{ $variant === 'payments' ? 'badge-pay' : 'badge-extra' }}">New</span>
                                                </div>
                                            @else
                                                <span x-show="wide" class="{{ $labelClass }} flex-1 truncate">{{ $item['label'] }}</span>

                                                @if(array_key_exists('count', $item))
                                                    <span x-show="wide"
                                                          class="text-[10px] font-semibold px-2 py-0.5 rounded-full {{ ($item['count'] ?? 0) > 0 ? 'bg-indigo-500/25 text-indigo-200' : 'bg-indigo-500/15 text-indigo-300/70' }}">{{ $item['count'] }}</span>
                                                @endif
                                                @if(!empty($item['badge']))
                                                    <span x-show="wide" class="text-[9px] bg-pink-500/20 text-pink-300 font-bold px-1.5 py-0.5 rounded">{{ $item['badge'] }}</span>
                                                @endif
                                                @if(!empty($item['dot']))
                                                    <span x-show="wide" class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                                                @endif
                                            @endif
                                        </a>
                                        <button type="button" x-show="wide" @click.stop.prevent="togglePin(@js($item['key']))"
                                                class="pin-btn" :class="isPinned(@js($item['key'])) && 'pinned'"
                                                :title="isPinned(@js($item['key'])) ? 'Unpin' : 'Pin to top'">
                                            <i class="fa-solid fa-thumbtack"></i>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Nəticə yoxdur --}}
                <div x-show="q !== '' && noResults" x-cloak class="px-3 py-10 text-center">
                    <div class="w-10 h-10 mx-auto mb-3 rounded-xl bg-slate-800/60 flex items-center justify-center text-slate-500">
                        <i class="fa-solid fa-magnifying-glass-minus"></i>
                    </div>
                    <p class="text-xs text-slate-400 font-medium">Nothing found</p>
                    <p class="text-[11px] text-slate-600 mt-1">Try another keyword</p>
                </div>
            </nav>

            {{-- İstifadəçi kartı --}}
            <div class="border-t border-slate-800/50 p-3 shrink-0">
                <div class="flex items-center gap-2 px-2 py-2 rounded-xl bg-slate-800/30 border border-slate-800/60"
                     :class="wide ? '' : 'justify-center px-0'">
                    <a href="{{ route('admin.profile.edit') }}" class="relative shrink-0"
                       @mouseenter="showTip($event, @js(auth()->user()->name ?? 'Admin'))" @mouseleave="hideTip()">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                    </a>
                    <a x-show="wide" href="{{ route('admin.profile.edit') }}" class="min-w-0 flex-1 ml-1">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </a>
                    <form x-show="wide" method="POST" action="{{ route('admin.logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" title="Logout"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:text-red-400 hover:bg-red-500/10 transition">
                            <i class="fa-solid fa-power-off text-xs"></i>
                        </button>
                    </form>
                </div>

                <div x-show="wide" class="mt-2 flex items-center justify-between px-1">
                    <span class="text-[10px] text-slate-600 font-mono">RJ SHOP Lite</span>
                    <span class="flex items-center gap-1"><span class="kbd">Ctrl K</span><span class="kbd">Ctrl B</span></span>
                </div>
            </div>

        </aside>

        {{-- ═════════════════ ƏSAS HİSSƏ ═════════════════ --}}
        <div class="flex-1 flex flex-col overflow-hidden min-w-0">

            <header class="h-16 bg-white/70 backdrop-blur-xl border-b border-slate-200/70 flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">

                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex w-9 h-9 items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600 transition">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600 transition">
                        <i class="fa-solid fa-bars text-sm"></i>
                    </button>
                    <div>
                        <h1 class="text-base font-semibold text-slate-800 leading-tight">@yield('title', 'Dashboard')</h1>
                        <p class="text-[11px] text-slate-400 hidden sm:block">{{ now()->format('d M Y, l') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/" target="_blank" class="hidden md:flex w-9 h-9 items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 transition" title="View site">
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-lg hover:bg-slate-100 transition">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fa-solid fa-chevron-down text-[9px] text-slate-400"></i>
                        </button>
                        <div x-show="open" x-cloak @click.outside="open = false" x-collapse
                             class="absolute right-0 mt-2 w-56 bg-white border border-slate-200 rounded-xl shadow-xl shadow-slate-200/60 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-br from-indigo-50/50 to-purple-50/30">
                                <p class="text-xs font-semibold text-slate-800 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 transition">
                                <i class="fa-solid fa-user text-xs w-4 text-slate-400"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition border-t border-slate-100">
                                    <i class="fa-solid fa-arrow-right-from-bracket text-xs w-4"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </header>

            <main class="flex-1 overflow-y-auto p-4 sm:p-6 scroll-thin">
                @yield('content')
            </main>

        </div>

    </div>

    {{-- Mobil overlay --}}
    <div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 md:hidden"></div>

    {{-- Yığılmış sidebar üçün tooltip --}}
    <div x-show="tip.show" x-cloak x-transition.opacity.duration.100ms
         class="fixed z-[10000] pointer-events-none hidden md:block"
         :style="`left:${tip.x}px;top:${tip.y}px`">
        <div class="-translate-y-1/2 px-2.5 py-1.5 rounded-lg bg-slate-900 text-white text-[11px] font-medium shadow-xl border border-slate-700/60 whitespace-nowrap" x-text="tip.text"></div>
    </div>

    @php
        $aiChatWidget = \App\Models\Widget::where('key', 'admin_ai_chat')
            ->where('is_active', 1)
            ->first();

        $aiChatSettings = $aiChatWidget ? ($aiChatWidget->settings ?? []) : [];
        $aiChatEnabledRaw = $aiChatSettings['enabled'] ?? false;
        $aiChatEnabled = $aiChatEnabledRaw === true || $aiChatEnabledRaw === 1 || $aiChatEnabledRaw === '1';
        $aiChatUrl = $aiChatSettings['ai_url'] ?? 'https://chat.openai.com';
        $aiChatWidth = (int) ($aiChatSettings['popup_width'] ?? 460);
        $aiChatHeight = (int) ($aiChatSettings['popup_height'] ?? 780);
        $aiChatPosition = $aiChatSettings['position'] ?? 'bottom-right';
        $aiChatMode = $aiChatSettings['mode'] ?? 'iframe';
        $aiChatLabel = $aiChatSettings['button_label'] ?? 'Chat';
        $aiChatIsLeft = $aiChatPosition === 'bottom-left';
    @endphp

    @if($aiChatWidget && $aiChatEnabled && !request()->routeIs('admin.widgets*'))
        <div x-data="adminAiChatPanel({
                url: @js($aiChatUrl),
                panelWidth: {{ $aiChatWidth }},
                popupHeight: {{ $aiChatHeight }},
                mode: @js($aiChatMode),
                label: @js($aiChatLabel),
                proxyBase: @js(route('admin.ai-proxy'))
             })"
             x-cloak>

            <button type="button"
                    @click="handleButtonClick()"
                    :title="label"
                    x-show="!open"
                    x-transition.opacity
                    class="rj-ai {{ $aiChatIsLeft ? 'rj-ai--left' : 'rj-ai--right' }}">
                <span class="rj-ai__shine"></span>
                <span class="rj-ai__orb">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span class="rj-ai__dot"></span>
                </span>
                <span class="rj-ai__label" x-text="label"></span>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="fixed top-0 right-0 h-full bg-[#0f172a] border-l border-slate-800/60 shadow-[-16px_0_48px_rgba(0,0,0,0.5)] z-[9999] flex flex-col"
                 :style="'width: ' + panelWidth + 'px; max-width: 100vw;'">

                <div class="h-14 flex items-center justify-between px-4 border-b border-slate-800/60 shrink-0">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-wand-magic-sparkles text-white text-xs"></i>
                        </div>
                        <div class="leading-tight">
                            <p class="text-white text-sm font-bold" x-text="label"></p>
                            <p class="text-[10px] text-slate-500 font-mono" x-text="mode === 'iframe' ? 'iframe view' : 'popup mode'"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button type="button"
                                @click="reload()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition"
                                title="Reload">
                            <i class="fa-solid fa-rotate text-xs"></i>
                        </button>
                        <button type="button"
                                @click="openPopup()"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition"
                                title="Open in new window">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </button>
                        <button type="button"
                                @click="open = false"
                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-white hover:bg-slate-800 transition"
                                title="Close">
                            <i class="fa-solid fa-xmark text-sm"></i>
                        </button>
                    </div>
                </div>

                <div class="flex-1 relative bg-slate-950">
                    <iframe :src="iframeUrl"
                            class="w-full h-full border-0"
                            referrerpolicy="no-referrer-when-downgrade"
                            sandbox="allow-scripts allow-same-origin allow-forms allow-popups allow-modals allow-downloads allow-popups-to-escape-sandbox"
                            allow="clipboard-write; clipboard-read; microphone; camera"
                            x-ref="frame"></iframe>

                    <div x-show="blocked"
                         x-cloak
                         class="absolute inset-0 flex flex-col items-center justify-center bg-slate-950/95 p-6 text-center z-10">
                        <div class="w-14 h-14 rounded-full bg-amber-500/15 border border-amber-500/30 flex items-center justify-center mb-4">
                            <i class="fa-solid fa-shield-halved text-amber-400 text-xl"></i>
                        </div>
                        <p class="text-white font-semibold mb-2">This AI blocks embedding</p>
                        <p class="text-slate-400 text-xs leading-relaxed mb-5 max-w-xs">
                            <strong x-text="currentHost"></strong> doesn't allow itself to be shown in an iframe. Open it in a separate window instead.
                        </p>
                        <button type="button"
                                @click="openPopup()"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white text-sm font-bold hover:brightness-110 transition">
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            Open in new window
                        </button>
                    </div>
                </div>

                <div class="px-4 py-2.5 border-t border-slate-800/60 bg-slate-900/50 shrink-0">
                    <p class="text-[10px] text-slate-500 font-mono truncate" x-text="iframeUrl"></p>
                </div>
            </div>
        </div>
    @endif

    @stack('scripts')

    <script>
        document.addEventListener('alpine:init', () => {

            /* ───────────── Sidebar məntiqi ───────────── */
            Alpine.data('rjSidebar', (cfg) => ({
                items: cfg.items || [],
                sidebarOpen: true,
                mobileOpen: false,
                q: '',
                pins: [],
                closed: {},
                tip: { show: false, text: '', x: 0, y: 0 },

                init() {
                    // yadda saxlanmış vəziyyəti oxu
                    try {
                        const open = localStorage.getItem('rj.sidebar.open');
                        if (open !== null) this.sidebarOpen = open === '1';
                        this.pins = JSON.parse(localStorage.getItem('rj.sidebar.pins') || '[]');
                        this.closed = JSON.parse(localStorage.getItem('rj.sidebar.closed') || '{}');
                    } catch (e) {}

                    this.$watch('sidebarOpen', (v) => { this.hideTip(); this.store('rj.sidebar.open', v ? '1' : '0'); });
                    this.$watch('pins', (v) => this.store('rj.sidebar.pins', JSON.stringify(v)));
                    this.$watch('closed', (v) => this.store('rj.sidebar.closed', JSON.stringify(v)));

                    // klaviatura qısayolları
                    window.addEventListener('keydown', (e) => {
                        const mod = e.ctrlKey || e.metaKey;
                        const key = (e.key || '').toLowerCase();
                        const tag = (e.target && e.target.tagName) || '';
                        const typing = ['INPUT', 'TEXTAREA', 'SELECT'].includes(tag) || (e.target && e.target.isContentEditable);

                        if (mod && key === 'k') { e.preventDefault(); this.focusSearch(); }
                        else if (mod && key === 'b') { e.preventDefault(); this.sidebarOpen = !this.sidebarOpen; }
                        else if (key === '/' && !mod && !typing) { e.preventDefault(); this.focusSearch(); }
                        else if (key === 'escape' && this.mobileOpen) { this.mobileOpen = false; }
                    });

                    // aktiv səhifəni görünən sahəyə gətir
                    this.$nextTick(() => {
                        const a = document.querySelector('#rj-nav [data-nav-active]');
                        if (a && a.scrollIntoView) a.scrollIntoView({ block: 'center' });
                    });
                },

                store(k, v) { try { localStorage.setItem(k, v); } catch (e) {} },

                // geniş görünüş: desktop açıqdırsa və ya mobil drawer açıqdırsa
                get wide() { return this.sidebarOpen || this.mobileOpen; },

                get pinnedItems() {
                    return this.pins.map(k => this.items.find(i => i.key === k)).filter(Boolean);
                },

                get noResults() {
                    const s = this.q.trim().toLowerCase();
                    if (!s) return false;
                    return !this.items.some(i => i.label.toLowerCase().includes(s)) &&
                           !Array.from(document.querySelectorAll('#rj-nav [data-nav-link]'))
                                .some(a => a.textContent.toLowerCase().includes(s));
                },

                match(labels) {
                    const s = this.q.trim().toLowerCase();
                    if (!s) return true;
                    return (Array.isArray(labels) ? labels : [labels]).some(l => String(l).toLowerCase().includes(s));
                },

                isPinned(k) { return this.pins.includes(k); },
                togglePin(k) {
                    this.pins = this.isPinned(k) ? this.pins.filter(x => x !== k) : [...this.pins, k];
                },

                isOpen(title, hasActive) {
                    if (!this.wide || this.q !== '' || hasActive) return true;
                    return !this.closed[title];
                },
                toggleGroup(title) {
                    this.closed = { ...this.closed, [title]: !this.closed[title] };
                },

                focusSearch() {
                    if (window.innerWidth < 768) this.mobileOpen = true; else this.sidebarOpen = true;
                    this.$nextTick(() => setTimeout(() => { this.$refs.search && this.$refs.search.focus(); }, 60));
                },

                goFirst() {
                    const link = Array.from(document.querySelectorAll('#rj-nav [data-nav-link]'))
                        .find(a => a.offsetParent !== null);
                    if (link) window.location.href = link.href;
                },

                showTip(e, text) {
                    if (this.wide) return;
                    const r = e.currentTarget.getBoundingClientRect();
                    this.tip = { show: true, text, x: r.right + 10, y: r.top + r.height / 2 };
                },
                hideTip() { this.tip.show = false; },
            }));

            /* ───────────── AI Chat paneli ───────────── */
            Alpine.data('adminAiChatPanel', (config) => ({
                url: config.url || 'https://chat.openai.com',
                panelWidth: config.panelWidth || 460,
                popupHeight: config.popupHeight || 780,
                mode: config.mode || 'iframe',
                label: config.label || 'Chat',
                proxyBase: config.proxyBase || '/admin/ai-proxy',
                open: false,
                blocked: false,
                iframeUrl: '',

                get currentHost() {
                    try {
                        return new URL(this.url).hostname;
                    } catch (e) {
                        return this.url;
                    }
                },

                buildProxyUrl(target) {
                    return this.proxyBase + '?url=' + encodeURIComponent(target);
                },

                init() {
                    this.iframeUrl = this.buildProxyUrl(this.url);
                    this.$watch('open', (v) => {
                        if (v) {
                            this.blocked = false;
                            setTimeout(() => this.detectBlocked(), 4000);
                        }
                    });
                },

                handleButtonClick() {
                    if (this.mode === 'popup') {
                        this.openPopup();
                    } else {
                        this.open = true;
                    }
                },

                reload() {
                    this.blocked = false;
                    const base = this.url;
                    const sep = base.indexOf('?') > -1 ? '&' : '?';
                    const target = base + sep + '_r=' + Date.now();
                    this.iframeUrl = this.buildProxyUrl(target);
                    setTimeout(() => this.detectBlocked(), 4000);
                },

                detectBlocked() {
                    const frame = this.$refs.frame;
                    if (!frame) return;

                    try {
                        const doc = frame.contentDocument || frame.contentWindow.document;
                        if (!doc || doc.body === null) {
                            this.blocked = true;
                            return;
                        }
                        if (doc.body.innerHTML.trim() === '' || doc.body.children.length === 0) {
                            this.blocked = true;
                            return;
                        }
                        this.blocked = false;
                    } catch (e) {
                        this.blocked = false;
                    }
                },

                openPopup() {
                    const w = this.panelWidth;
                    const h = Math.min(this.popupHeight, window.screen.height - 80);
                    const left = (window.screen.width - w) / 2;
                    const top = (window.screen.height - h) / 2;

                    window.open(this.url, 'admin_ai_chat_popup',
                        'width=' + w + ',height=' + h + ',left=' + Math.max(0, left) + ',top=' + Math.max(0, top) + ',resizable=yes,scrollbars=yes');
                }
            }));
        });
    </script>

</body>
</html>

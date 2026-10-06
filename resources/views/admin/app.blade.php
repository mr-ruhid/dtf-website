<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - RJ SHOP lite</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            width:34px;height:34px;display:flex;align-items:center;justify-content:center;
            border-radius:9px;background:rgba(30,41,59,0.6);color:#64748b;
            transition:all .2s cubic-bezier(.4,0,.2,1);font-size:13px;
        }
        .nav-item:hover .icon-box{background:rgba(99,102,241,0.15);color:#a5b4fc;}
        .nav-item.active .icon-box{
            background:linear-gradient(135deg,#6366f1,#8b5cf6);color:#fff;
            box-shadow:0 4px 12px -2px rgba(99,102,241,0.5);
        }
        .glow-line{height:1px;background:linear-gradient(90deg,transparent,#6366f1 50%,transparent);opacity:.4;}

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
            top:8px;right:8px;
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
    </style>
    @stack('styles')
</head>
<body class="bg-slate-100 text-gray-800 antialiased" x-data="{ sidebarOpen: true, mobileOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'w-[260px]' : 'w-[78px]'"
               class="hidden md:flex flex-col sidebar-bg text-slate-400 transition-all duration-300 shrink-0 border-r border-slate-800/50">

            <div class="h-16 flex items-center px-5 border-b border-slate-800/50 shrink-0 relative">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition">
                        RJ
                    </div>
                    <div x-show="sidebarOpen" x-transition class="leading-tight">
                        <p class="text-white font-bold text-sm tracking-tight">RJ SHOP</p>
                        <p class="text-[10px] text-indigo-400 font-medium tracking-widest uppercase">Admin Lite</p>
                    </div>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5 scroll-thin">

                <div x-show="sidebarOpen" class="flex items-center gap-2 px-3 mb-2">
                    <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">Main</span>
                    <div class="flex-1 glow-line"></div>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-gauge-high"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Dashboard</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('admin.products*', 'admin.categories*', 'admin.models*', 'admin.attributes*', 'admin.print-zones*') ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.products*', 'admin.categories*', 'admin.models*', 'admin.attributes*', 'admin.print-zones*') ? 'active' : '' }}">
                        <div class="icon-box"><i class="fa-solid fa-cube"></i></div>
                        <span x-show="sidebarOpen" class="text-[13px] font-medium flex-1 text-left">Products</span>
                        <i x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-[9px] transition-transform"></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="mt-1 ml-5 pl-5 border-l border-slate-800/60 space-y-0.5">
                        <a href="{{ route('admin.products.index') }}" class="block py-1.5 px-3 text-[12px] rounded-lg transition {{ request()->routeIs('admin.products*') ? 'text-white bg-slate-800/60' : 'text-slate-500 hover:text-white hover:bg-slate-800/50' }}">All Products</a>
                        <a href="{{ route('admin.models.index') }}" class="block py-1.5 px-3 text-[12px] rounded-lg transition {{ request()->routeIs('admin.models*') ? 'text-white bg-slate-800/60' : 'text-slate-500 hover:text-white hover:bg-slate-800/50' }}">Models</a>
                        <a href="{{ route('admin.categories.index') }}" class="block py-1.5 px-3 text-[12px] rounded-lg transition {{ request()->routeIs('admin.categories*') ? 'text-white bg-slate-800/60' : 'text-slate-500 hover:text-white hover:bg-slate-800/50' }}">Categories</a>
                        <a href="{{ route('admin.attributes.index') }}" class="block py-1.5 px-3 text-[12px] rounded-lg transition {{ request()->routeIs('admin.attributes*') ? 'text-white bg-slate-800/60' : 'text-slate-500 hover:text-white hover:bg-slate-800/50' }}">Attributes</a>
                        <a href="{{ route('admin.print-zones.index') }}" class="block py-1.5 px-3 text-[12px] rounded-lg transition {{ request()->routeIs('admin.print-zones*') ? 'text-white bg-slate-800/60' : 'text-slate-500 hover:text-white hover:bg-slate-800/50' }}">Print Zones</a>
                    </div>
                </div>

                <a href="{{ route('admin.orders.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-receipt"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium flex-1">Orders</span>
                    <span x-show="sidebarOpen" class="text-[10px] bg-indigo-500/20 text-indigo-300 font-semibold px-2 py-0.5 rounded-full">0</span>
                </a>

                <div x-show="sidebarOpen" class="flex items-center gap-2 px-3 mt-5 mb-2">
                    <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">Content</span>
                    <div class="flex-1 glow-line"></div>
                </div>

                <a href="{{ route('admin.blog.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-newspaper"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Blog</span>
                </a>

                <a href="{{ route('admin.pages.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-file-lines"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Pages</span>
                </a>

                <a href="{{ route('admin.widgets.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.widgets*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-puzzle-piece"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Widgets</span>
                </a>

                <a href="{{ route('admin.menus.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-bars-staggered"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Menus</span>
                </a>

                <a href="{{ route('admin.gallery.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-images"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Gallery</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-circle-question"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">FAQ</span>
                </a>

                <a href="{{ route('admin.sliders.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-panorama"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Sliders</span>
                </a>

                <div x-show="sidebarOpen" class="flex items-center gap-2 px-3 mt-5 mb-2">
                    <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">Shipping</span>
                    <div class="flex-1 glow-line"></div>
                </div>

                <a href="{{ route('admin.branches.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.branches*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-store"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Branches</span>
                </a>

                <a href="{{ route('admin.delivery-zones.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.delivery-zones*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-map-location-dot"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Delivery Zones</span>
                </a>

                <a href="{{ route('admin.delivery-rates.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.delivery-rates*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-truck-fast"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Delivery Rates</span>
                </a>

                <div x-show="sidebarOpen" class="flex items-center gap-2 px-3 mt-5 mb-2">
                    <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">System</span>
                    <div class="flex-1 glow-line"></div>
                </div>

                <a href="{{ route('admin.support.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-headset"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium flex-1">Technical Support</span>
                    <span x-show="sidebarOpen" class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                </a>

                <a href="{{ route('admin.settings.index') }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-sliders"></i></div>
                    <span x-show="sidebarOpen" class="text-[13px] font-medium">Settings</span>
                </a>

                <div x-show="sidebarOpen" class="flex items-center gap-2 px-3 mt-5 mb-2">
                    <span class="text-[9px] uppercase tracking-[0.15em] text-amber-500 font-bold">Extra</span>
                    <div class="flex-1" style="height:1px;background:linear-gradient(90deg,rgba(245,158,11,0.5),transparent);"></div>
                </div>

                <a href="{{ route('admin.services.index') }}"
                   class="nav-services nav-item flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                    <div class="icon-box"><i class="fa-solid fa-rocket"></i></div>
                    <div x-show="sidebarOpen" class="flex-1 flex items-center justify-between min-w-0">
                        <span class="text-[13px] font-semibold text-amber-100">Services</span>
                        <span class="badge-extra">New</span>
                    </div>
                </a>

            </nav>

            <div class="border-t border-slate-800/50 p-3 shrink-0">
                <div class="flex items-center gap-3 px-2 py-2 rounded-xl bg-slate-800/30 border border-slate-800/60">
                    <div class="relative shrink-0">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-slate-900"></span>
                    </div>
                    <div x-show="sidebarOpen" class="min-w-0 flex-1">
                        <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>

        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

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
                        <div x-show="open" @click.outside="open = false" x-collapse
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

    <div x-show="mobileOpen" x-cloak @click="mobileOpen = false"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 md:hidden"></div>

    <aside x-show="mobileOpen" x-cloak
           x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
           class="fixed top-0 left-0 h-full w-[260px] sidebar-bg text-slate-400 z-50 md:hidden overflow-y-auto scroll-thin">

        <div class="h-16 flex items-center px-5 border-b border-slate-800/50">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/30">RJ</div>
                <div class="leading-tight">
                    <p class="text-white font-bold text-sm">RJ SHOP</p>
                    <p class="text-[10px] text-indigo-400 font-medium tracking-widest uppercase">Admin Lite</p>
                </div>
            </a>
        </div>

        <nav class="py-4 px-3 space-y-0.5">
            <div class="flex items-center gap-2 px-3 mb-2">
                <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">Main</span>
                <div class="flex-1 glow-line"></div>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-gauge-high"></i></div>
                <span class="text-[13px] font-medium">Dashboard</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-cube"></i></div>
                <span class="text-[13px] font-medium">Products</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-receipt"></i></div>
                <span class="text-[13px] font-medium">Orders</span>
            </a>

            <div class="flex items-center gap-2 px-3 mt-5 mb-2">
                <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">Content</span>
                <div class="flex-1 glow-line"></div>
            </div>
            <a href="{{ route('admin.blog.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-newspaper"></i></div>
                <span class="text-[13px] font-medium">Blog</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.pages*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-file-lines"></i></div>
                <span class="text-[13px] font-medium">Pages</span>
            </a>
            <a href="{{ route('admin.widgets.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.widgets*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-puzzle-piece"></i></div>
                <span class="text-[13px] font-medium">Widgets</span>
            </a>
            <a href="{{ route('admin.menus.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.menus*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-bars-staggered"></i></div>
                <span class="text-[13px] font-medium">Menus</span>
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.gallery*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-images"></i></div>
                <span class="text-[13px] font-medium">Gallery</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-circle-question"></i></div>
                <span class="text-[13px] font-medium">FAQ</span>
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-panorama"></i></div>
                <span class="text-[13px] font-medium">Sliders</span>
            </a>

            <div class="flex items-center gap-2 px-3 mt-5 mb-2">
                <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">Shipping</span>
                <div class="flex-1 glow-line"></div>
            </div>
            <a href="{{ route('admin.branches.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.branches*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-store"></i></div>
                <span class="text-[13px] font-medium">Branches</span>
            </a>
            <a href="{{ route('admin.delivery-zones.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.delivery-zones*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-map-location-dot"></i></div>
                <span class="text-[13px] font-medium">Delivery Zones</span>
            </a>
            <a href="{{ route('admin.delivery-rates.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.delivery-rates*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-truck-fast"></i></div>
                <span class="text-[13px] font-medium">Delivery Rates</span>
            </a>

            <div class="flex items-center gap-2 px-3 mt-5 mb-2">
                <span class="text-[9px] uppercase tracking-[0.15em] text-slate-600 font-bold">System</span>
                <div class="flex-1 glow-line"></div>
            </div>
            <a href="{{ route('admin.support.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.support*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-headset"></i></div>
                <span class="text-[13px] font-medium">Technical Support</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item flex items-center gap-3 px-3 py-2 rounded-xl {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-sliders"></i></div>
                <span class="text-[13px] font-medium">Settings</span>
            </a>

            <div class="flex items-center gap-2 px-3 mt-5 mb-2">
                <span class="text-[9px] uppercase tracking-[0.15em] text-amber-500 font-bold">Extra</span>
                <div class="flex-1" style="height:1px;background:linear-gradient(90deg,rgba(245,158,11,0.5),transparent);"></div>
            </div>
            <a href="{{ route('admin.services.index') }}" class="nav-services nav-item flex items-center gap-3 px-3 py-2.5 {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                <div class="icon-box"><i class="fa-solid fa-rocket"></i></div>
                <div class="flex-1 flex items-center justify-between min-w-0">
                    <span class="text-[13px] font-semibold text-amber-100">Services</span>
                    <span class="badge-extra">New</span>
                </div>
            </a>
        </nav>

    </aside>

    @stack('scripts')

</body>
</html>

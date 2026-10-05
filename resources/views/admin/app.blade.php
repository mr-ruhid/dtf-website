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
        .scroll-thin::-webkit-scrollbar{width:6px;}
        .scroll-thin::-webkit-scrollbar-track{background:transparent;}
        .scroll-thin::-webkit-scrollbar-thumb{background:#334155;border-radius:3px;}
        .scroll-thin::-webkit-scrollbar-thumb:hover{background:#475569;}
    </style>
</head>
<body class="bg-slate-100 text-gray-800 antialiased" x-data="{ sidebarOpen: true, mobileOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'w-64' : 'w-[72px]'"
               class="hidden md:flex flex-col bg-gradient-to-b from-slate-900 to-slate-950 text-slate-400 transition-all duration-300 shrink-0 border-r border-slate-800">

            <div class="h-16 flex items-center justify-center border-b border-slate-800/70 shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-lg shadow-indigo-500/30">RJ</div>
                    <span x-show="sidebarOpen" x-transition class="text-white font-bold text-lg tracking-tight">SHOP <span class="text-indigo-400">lite</span></span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scroll-thin">

                <p x-show="sidebarOpen" class="px-3 text-[10px] uppercase tracking-wider text-slate-600 font-semibold mb-2">Main</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all relative
                   {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-white' : 'hover:bg-slate-800/60 hover:text-white' }}">
                    @if(request()->routeIs('admin.dashboard'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-indigo-500 rounded-r"></span>
                    @endif
                    <i class="fa-solid fa-gauge-high w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Dashboard</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('admin.product*', 'admin.category*', 'admin.brand*', 'admin.attribute*') ? 'true' : 'false' }} }">
                    <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-box w-5 text-center text-base"></i>
                            <span x-show="sidebarOpen" class="text-sm font-medium">Products</span>
                        </div>
                        <i x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-[10px] transition-transform"></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="mt-1 ml-4 pl-4 border-l border-slate-800 space-y-1">
                        <a href="#" class="block py-2 px-3 text-sm rounded-md hover:text-white hover:bg-slate-800/60 transition">All Products</a>
                        <a href="#" class="block py-2 px-3 text-sm rounded-md hover:text-white hover:bg-slate-800/60 transition">Categories</a>
                        <a href="#" class="block py-2 px-3 text-sm rounded-md hover:text-white hover:bg-slate-800/60 transition">Brands</a>
                        <a href="#" class="block py-2 px-3 text-sm rounded-md hover:text-white hover:bg-slate-800/60 transition">Attributes</a>
                    </div>
                </div>

                <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                    <i class="fa-solid fa-cart-shopping w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Orders</span>
                    <span x-show="sidebarOpen" class="ml-auto text-[10px] bg-slate-800 text-slate-400 px-2 py-0.5 rounded-full">0</span>
                </a>

                <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                    <i class="fa-solid fa-users w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Customers</span>
                </a>

                <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                    <i class="fa-solid fa-ticket w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Coupons</span>
                </a>

                <p x-show="sidebarOpen" class="px-3 text-[10px] uppercase tracking-wider text-slate-600 font-semibold mt-6 mb-2">Content</p>

                <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                    <i class="fa-solid fa-file-lines w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Pages</span>
                </a>

                <a href="{{ route('admin.faqs.index') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all relative
                   {{ request()->routeIs('admin.faqs*') ? 'bg-indigo-500/10 text-white' : 'hover:bg-slate-800/60 hover:text-white' }}">
                    @if(request()->routeIs('admin.faqs*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-indigo-500 rounded-r"></span>
                    @endif
                    <i class="fa-solid fa-circle-question w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">FAQ</span>
                </a>

                <a href="{{ route('admin.sliders.index') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all relative
                   {{ request()->routeIs('admin.sliders*') ? 'bg-indigo-500/10 text-white' : 'hover:bg-slate-800/60 hover:text-white' }}">
                    @if(request()->routeIs('admin.sliders*'))
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-indigo-500 rounded-r"></span>
                    @endif
                    <i class="fa-solid fa-image w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Sliders</span>
                </a>

                <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                    <i class="fa-solid fa-envelope w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Messages</span>
                </a>

                <p x-show="sidebarOpen" class="px-3 text-[10px] uppercase tracking-wider text-slate-600 font-semibold mt-6 mb-2">System</p>

                <a href="#" class="group flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition-all">
                    <i class="fa-solid fa-gear w-5 text-center text-base"></i>
                    <span x-show="sidebarOpen" class="text-sm font-medium">Settings</span>
                </a>

            </nav>

            <div class="border-t border-slate-800/70 p-3 shrink-0">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div x-show="sidebarOpen" class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
            </div>

        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="h-16 bg-white/80 backdrop-blur border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 shrink-0 sticky top-0 z-30">

                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex w-9 h-9 items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <h1 class="text-base font-semibold text-slate-800 leading-tight">@yield('title', 'Dashboard')</h1>
                        <p class="text-[11px] text-slate-400 hidden sm:block">{{ now()->format('d M Y, l') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a href="/" target="_blank" class="hidden md:flex w-9 h-9 items-center justify-center rounded-lg hover:bg-slate-100 text-slate-600 transition" title="View site">
                        <i class="fa-solid fa-eye text-sm"></i>
                    </a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 pl-1 pr-2 py-1 rounded-lg hover:bg-slate-100 transition">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center text-xs font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm font-medium text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-collapse
                             class="absolute right-0 mt-2 w-52 bg-white border border-slate-200 rounded-xl shadow-xl shadow-slate-200/50 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-slate-100">
                                <p class="text-xs font-medium text-slate-800 truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
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
           class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-slate-900 to-slate-950 text-slate-400 z-50 md:hidden overflow-y-auto scroll-thin">

        <div class="h-16 flex items-center justify-center border-b border-slate-800/70">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm">RJ</div>
                <span class="text-white font-bold text-lg">SHOP <span class="text-indigo-400">lite</span></span>
            </a>
        </div>

        <nav class="py-4 px-3 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500/10 text-white' : 'hover:bg-slate-800/60 hover:text-white' }} transition">
                <i class="fa-solid fa-gauge-high w-5"></i><span class="text-sm font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-box w-5"></i><span class="text-sm font-medium">Products</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-cart-shopping w-5"></i><span class="text-sm font-medium">Orders</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-users w-5"></i><span class="text-sm font-medium">Customers</span>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.faqs*') ? 'bg-indigo-500/10 text-white' : 'hover:bg-slate-800/60 hover:text-white' }} transition">
                <i class="fa-solid fa-circle-question w-5"></i><span class="text-sm font-medium">FAQ</span>
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.sliders*') ? 'bg-indigo-500/10 text-white' : 'hover:bg-slate-800/60 hover:text-white' }} transition">
                <i class="fa-solid fa-image w-5"></i><span class="text-sm font-medium">Sliders</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800/60 hover:text-white transition">
                <i class="fa-solid fa-gear w-5"></i><span class="text-sm font-medium">Settings</span>
            </a>
        </nav>

    </aside>

</body>
</html>

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
    <style>[x-cloak]{display:none!important;}</style>
</head>
<body class="bg-gray-100 text-gray-800" x-data="{ sidebarOpen: true, mobileOpen: false }">

    <div class="flex h-screen overflow-hidden">

        <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
               class="hidden md:flex flex-col bg-slate-900 text-slate-300 transition-all duration-300 shrink-0">

            <div class="h-16 flex items-center justify-center border-b border-slate-800">
                <span x-show="sidebarOpen" class="text-white font-bold text-lg tracking-wide">RJ SHOP <span class="text-indigo-400">lite</span></span>
                <span x-show="!sidebarOpen" class="text-white font-bold text-lg">RJ</span>
            </div>

            <nav class="flex-1 overflow-y-auto py-4 space-y-1">

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-4 py-3 transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white border-l-4 border-indigo-500' : 'hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-gauge-high w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Dashboard</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('admin.product*', 'admin.category*', 'admin.brand*', 'admin.attribute*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-box w-5 text-center"></i>
                            <span x-show="sidebarOpen" class="text-sm">Products</span>
                        </div>
                        <i x-show="sidebarOpen" :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-xs transition"></i>
                    </button>
                    <div x-show="open && sidebarOpen" x-collapse class="bg-slate-950/40">
                        <a href="#" class="block pl-12 pr-4 py-2 text-sm hover:text-white hover:bg-slate-800 transition">All Products</a>
                        <a href="#" class="block pl-12 pr-4 py-2 text-sm hover:text-white hover:bg-slate-800 transition">Categories</a>
                        <a href="#" class="block pl-12 pr-4 py-2 text-sm hover:text-white hover:bg-slate-800 transition">Brands</a>
                        <a href="#" class="block pl-12 pr-4 py-2 text-sm hover:text-white hover:bg-slate-800 transition">Attributes</a>
                    </div>
                </div>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-cart-shopping w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Orders</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-users w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Customers</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-ticket w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Coupons</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-file-lines w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Pages</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-circle-question w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">FAQ</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-image w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Sliders</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-envelope w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Messages</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white transition">
                    <i class="fa-solid fa-gear w-5 text-center"></i>
                    <span x-show="sidebarOpen" class="text-sm">Settings</span>
                </a>

            </nav>

        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 shrink-0">

                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = !sidebarOpen" class="hidden md:flex w-9 h-9 items-center justify-center rounded hover:bg-gray-100 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden w-9 h-9 flex items-center justify-center rounded hover:bg-gray-100 transition">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1 class="text-base font-semibold text-gray-700">@yield('title', 'Dashboard')</h1>
                </div>

                <div class="flex items-center gap-3">
                    <a href="/" target="_blank" class="hidden md:flex w-9 h-9 items-center justify-center rounded hover:bg-gray-100 transition">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 px-2 py-1 rounded hover:bg-gray-100 transition">
                            <div class="w-8 h-8 rounded-full bg-indigo-500 text-white flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="hidden md:block text-sm">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-collapse class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden z-50">
                            <a href="{{ route('admin.profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-gray-50">Profile</a>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>

            </header>

            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>

        </div>

    </div>

    <div x-show="mobileOpen" @click="mobileOpen = false" class="fixed inset-0 bg-black/50 z-40 md:hidden"></div>
    <aside x-show="mobileOpen"
           x-transition:enter="transition ease-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed top-0 left-0 h-full w-64 bg-slate-900 text-slate-300 z-50 md:hidden overflow-y-auto">
        <div class="h-16 flex items-center justify-center border-b border-slate-800">
            <span class="text-white font-bold text-lg">RJ SHOP <span class="text-indigo-400">lite</span></span>
        </div>
        <nav class="py-4 space-y-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-gauge-high w-5"></i><span class="text-sm">Dashboard</span></a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-box w-5"></i><span class="text-sm">Products</span></a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-cart-shopping w-5"></i><span class="text-sm">Orders</span></a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-users w-5"></i><span class="text-sm">Customers</span></a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-800 hover:text-white"><i class="fa-solid fa-gear w-5"></i><span class="text-sm">Settings</span></a>
        </nav>
    </aside>

</body>
</html>

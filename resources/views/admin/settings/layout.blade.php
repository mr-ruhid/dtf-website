@extends('admin.app')

@section('title', $title ?? 'Settings')

@section('content')

@hasSection('settings-content')

    <div class="mb-6">
        <a href="{{ route('admin.settings.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition">
            <i class="fa-solid fa-arrow-left text-xs mr-1.5"></i> Back to Settings
        </a>
        <h2 class="text-xl font-semibold text-gray-800 mt-2">{{ $title ?? 'Settings' }}</h2>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    @yield('settings-content')

@else

    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your site configuration</p>
    </div>

    @if (session('status'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $maintenanceActive = \App\Models\Setting::get('maintenance_mode') === '1';

        $cards = [
            [
                'route' => 'admin.settings.general',
                'label' => 'General',
                'desc' => 'Site name, logo, favicon',
                'icon' => 'fa-gear',
                'bg' => 'rgba(100,116,139,0.1)',
                'fg' => 'rgb(71,85,105)',
            ],
            [
                'route' => 'admin.settings.contact',
                'label' => 'Contact & Social',
                'desc' => 'Phone, address, social media',
                'icon' => 'fa-address-book',
                'bg' => 'rgba(16,185,129,0.1)',
                'fg' => 'rgb(5,150,105)',
            ],
            [
                'route' => 'admin.settings.seo',
                'label' => 'SEO',
                'desc' => 'Meta tags, analytics, robots',
                'icon' => 'fa-magnifying-glass',
                'bg' => 'rgba(59,130,246,0.1)',
                'fg' => 'rgb(37,99,235)',
            ],
            [
                'route' => 'admin.settings.homepage',
                'label' => 'Homepage',
                'desc' => 'Hero section, statistics',
                'icon' => 'fa-house',
                'bg' => 'rgba(245,158,11,0.1)',
                'fg' => 'rgb(217,119,6)',
            ],
            [
                'route' => 'admin.settings.smtp',
                'label' => 'SMTP',
                'desc' => 'Email delivery settings',
                'icon' => 'fa-envelope',
                'bg' => 'rgba(244,63,94,0.1)',
                'fg' => 'rgb(225,29,72)',
            ],
            [
                'route' => 'admin.settings.design-pricing',
                'label' => 'Design Pricing',
                'desc' => 'Custom size formula',
                'icon' => 'fa-calculator',
                'bg' => 'rgba(236,72,153,0.1)',
                'fg' => 'rgb(219,39,119)',
                'badge' => 'NEW',
            ],
            [
                'route' => 'admin.settings.system',
                'label' => 'System Info',
                'desc' => 'PHP, Laravel, server status',
                'icon' => 'fa-server',
                'bg' => 'rgba(6,182,212,0.1)',
                'fg' => 'rgb(8,145,178)',
            ],
            [
                'route' => 'admin.settings.maintenance',
                'label' => 'Maintenance',
                'desc' => 'Temporarily close the site',
                'icon' => 'fa-triangle-exclamation',
                'bg' => 'rgba(245,158,11,0.1)',
                'fg' => 'rgb(217,119,6)',
                'badge' => $maintenanceActive ? 'ACTIVE' : null,
            ],
            [
                'route' => 'admin.settings.security',
                'label' => 'Security',
                'desc' => 'Login logs, blocked IPs',
                'icon' => 'fa-shield-halved',
                'bg' => 'rgba(139,92,246,0.1)',
                'fg' => 'rgb(124,58,237)',
            ],
            [
                'route' => 'admin.profile.edit',
                'label' => 'Profile',
                'desc' => 'Password and 2FA',
                'icon' => 'fa-user',
                'bg' => 'rgba(99,102,241,0.1)',
                'fg' => 'rgb(79,70,229)',
            ],
            [
                'route' => 'admin.settings.cache',
                'label' => 'Cache',
                'desc' => 'Cache management',
                'icon' => 'fa-bolt',
                'bg' => 'rgba(249,115,22,0.1)',
                'fg' => 'rgb(234,88,12)',
            ],
            [
                'route' => 'admin.settings.backup',
                'label' => 'Backup',
                'desc' => 'Database and files backup',
                'icon' => 'fa-database',
                'bg' => 'rgba(20,184,166,0.1)',
                'fg' => 'rgb(13,148,136)',
            ],
            [
                'route' => 'admin.settings.update',
                'label' => 'Update',
                'desc' => 'System updates',
                'icon' => 'fa-cloud-arrow-down',
                'bg' => 'rgba(168,85,247,0.1)',
                'fg' => 'rgb(147,51,234)',
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($cards as $card)
            <a href="{{ route($card['route']) }}"
               class="group bg-white rounded-2xl border border-gray-200 p-6 hover:shadow-lg hover:-translate-y-0.5 hover:border-gray-300 transition-all duration-200 relative overflow-hidden">

                @if (!empty($card['badge']))
                    <span class="absolute top-4 right-4 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold tracking-wider
                        {{ $card['badge'] === 'ACTIVE' ? 'bg-amber-100 text-amber-700' : ($card['badge'] === 'NEW' ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-600') }}">
                        <span class="w-1 h-1 rounded-full {{ $card['badge'] === 'ACTIVE' ? 'bg-amber-500 animate-pulse' : ($card['badge'] === 'NEW' ? 'bg-pink-500' : 'bg-gray-400') }}"></span>
                        {{ $card['badge'] }}
                    </span>
                @endif

                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5 transition-transform group-hover:scale-105"
                     style="background-color: {{ $card['bg'] }}; color: {{ $card['fg'] }}">
                    <i class="fa-solid {{ $card['icon'] }} text-xl"></i>
                </div>

                <h3 class="font-semibold text-gray-800 text-base mb-1">{{ $card['label'] }}</h3>
                <p class="text-sm text-gray-500 leading-snug">{{ $card['desc'] }}</p>
            </a>
        @endforeach
    </div>

@endif

@endsection

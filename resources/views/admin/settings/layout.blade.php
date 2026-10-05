@extends('admin.app')

@section('title', $title ?? 'Settings')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Settings</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your site configuration</p>
    </div>
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

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-2 sticky top-24">

            @php
                $tabs = [
                    ['route' => 'admin.settings.general', 'label' => 'General', 'icon' => 'fa-sliders', 'pattern' => 'admin.settings.general'],
                    ['route' => 'admin.settings.about', 'label' => 'About', 'icon' => 'fa-circle-info', 'pattern' => 'admin.settings.about'],
                    ['route' => 'admin.settings.cache', 'label' => 'Cache', 'icon' => 'fa-broom', 'pattern' => 'admin.settings.cache'],
                    ['route' => 'admin.settings.backup', 'label' => 'Backup', 'icon' => 'fa-database', 'pattern' => 'admin.settings.backup'],
                    ['route' => 'admin.settings.update', 'label' => 'Update', 'icon' => 'fa-cloud-arrow-down', 'pattern' => 'admin.settings.update'],
                ];
            @endphp

            <nav class="space-y-1">
                @foreach ($tabs as $tab)
                    <a href="{{ route($tab['route']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition
                       {{ request()->routeIs($tab['pattern']) ? 'bg-indigo-50 text-indigo-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-800' }}">
                        <i class="fa-solid {{ $tab['icon'] }} w-4 text-center {{ request()->routeIs($tab['pattern']) ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </nav>

        </div>
    </div>

    <div class="lg:col-span-3">
        @yield('settings-content')
    </div>

</div>

@endsection

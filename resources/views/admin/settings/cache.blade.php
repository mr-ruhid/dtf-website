@extends('admin.settings.layout')

@section('title', 'Cache')
@section('settings-content')

<div class="space-y-6">

    <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl p-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h3 class="text-lg font-semibold mb-1">Cache Management</h3>
                <p class="text-sm text-indigo-100">Clear temporary files to improve site performance</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center">
                <i class="fa-solid fa-broom text-xl"></i>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-4 text-xs">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-300"></span>
                <span>Driver: <span class="font-mono font-medium">{{ $cacheDriver }}</span></span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800">Clear Specific Cache</h3>
                <p class="text-xs text-gray-500 mt-0.5">Choose what you want to clear</p>
            </div>
            <form method="POST" action="{{ route('admin.settings.cache.clear', 'all') }}"
                  onsubmit="return confirm('Clear ALL caches? This may slow the site briefly.')">
                @csrf
                <button class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fa-solid fa-trash-can text-xs"></i> Clear All
                </button>
            </form>
        </div>

        <div class="divide-y divide-gray-100">

            @php
                $items = [
                    [
                        'key' => 'application',
                        'title' => 'Application Cache',
                        'desc' => 'Cached data, queries and settings',
                        'icon' => 'fa-layer-group',
                        'color' => 'indigo',
                        'size' => $sizes['application'] ?? 0,
                    ],
                    [
                        'key' => 'views',
                        'title' => 'Compiled Views',
                        'desc' => 'Blade template cache files',
                        'icon' => 'fa-file-code',
                        'color' => 'purple',
                        'size' => $sizes['views'] ?? 0,
                    ],
                    [
                        'key' => 'config',
                        'title' => 'Configuration Cache',
                        'desc' => 'Cached config files',
                        'icon' => 'fa-gear',
                        'color' => 'blue',
                        'size' => 0,
                    ],
                    [
                        'key' => 'routes',
                        'title' => 'Route Cache',
                        'desc' => 'Compiled route definitions',
                        'icon' => 'fa-route',
                        'color' => 'amber',
                        'size' => $sizes['routes'] ?? 0,
                    ],
                    [
                        'key' => 'sessions',
                        'title' => 'Sessions',
                        'desc' => 'Active user session files',
                        'icon' => 'fa-user-clock',
                        'color' => 'emerald',
                        'size' => $sizes['sessions'] ?? 0,
                    ],
                    [
                        'key' => 'logs',
                        'title' => 'Log Files',
                        'desc' => 'Application log files',
                        'icon' => 'fa-file-lines',
                        'color' => 'rose',
                        'size' => $sizes['logs'] ?? 0,
                    ],
                ];
            @endphp

            @foreach ($items as $item)
                <div class="px-6 py-4 flex items-center gap-4 hover:bg-gray-50/50 transition">
                    <div class="w-11 h-11 rounded-lg bg-{{ $item['color'] }}-50 text-{{ $item['color'] }}-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid {{ $item['icon'] }}"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-800">{{ $item['title'] }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $item['desc'] }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-mono text-gray-500">{{ number_format($item['size'] / 1024, 2) }} KB</p>
                    </div>
                    <form method="POST" action="{{ route('admin.settings.cache.clear', $item['key']) }}" class="shrink-0">
                        @csrf
                        <button class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-3 py-1.5 rounded-lg transition">
                            Clear
                        </button>
                    </form>
                </div>
            @endforeach

        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">System Status</h3>
            <p class="text-xs text-gray-500 mt-0.5">Current cache and optimization state</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 divide-gray-100">

            @php
                $statuses = [
                    ['label' => 'Config Cached', 'value' => $checks['config_cached']],
                    ['label' => 'Routes Cached', 'value' => $checks['routes_cached']],
                    ['label' => 'Events Cached', 'value' => $checks['events_cached']],
                    ['label' => 'Views Compiled', 'value' => $checks['views_cached']],
                ];
            @endphp

            @foreach ($statuses as $status)
                <div class="px-6 py-4 flex items-center justify-between {{ $loop->index % 2 === 0 ? 'sm:border-r sm:border-gray-100' : '' }}">
                    <span class="text-sm text-gray-600">{{ $status['label'] }}</span>
                    @if ($status['value'])
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Optimized
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-gray-100 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Not Cached
                        </span>
                    @endif
                </div>
            @endforeach

        </div>
    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
        <div class="text-sm text-amber-800">
            <p class="font-medium">Note</p>
            <p class="text-amber-700 text-xs mt-1">Clearing cache may briefly slow down the site while new cache is generated. Sessions clearing will log out all users.</p>
        </div>
    </div>

</div>

@endsection

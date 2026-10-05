@extends('admin.settings.layout')

@section('title', 'System Info')
@section('settings-content')

<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="bg-gradient-to-br from-indigo-500 via-indigo-600 to-purple-600 rounded-xl p-5 text-white relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-8 -right-2 w-20 h-20 rounded-full bg-white/5"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-indigo-100">Theme</span>
                    <i class="fa-solid fa-palette text-sm text-white/70"></i>
                </div>
                <p class="text-xl font-bold">{{ $data['theme']['name'] }}</p>
                <p class="text-xs text-indigo-100 mt-0.5">v{{ $data['theme']['version'] }}</p>
                <p class="text-[10px] text-indigo-200 mt-3 pt-3 border-t border-white/20">{{ $data['theme']['description'] }}</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl p-5 text-white relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-8 -right-2 w-20 h-20 rounded-full bg-white/5"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-emerald-100">Application</span>
                    <i class="fa-solid fa-robot text-sm text-white/70"></i>
                </div>
                <p class="text-xl font-bold">{{ $data['app']['name'] }}</p>
                <p class="text-xs text-emerald-100 mt-0.5">v{{ $data['app']['version'] }}</p>
                <p class="text-[10px] text-emerald-200 mt-3 pt-3 border-t border-white/20">Powered by RJ Shop AI</p>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-700 to-slate-900 rounded-xl p-5 text-white relative overflow-hidden">
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-8 -right-2 w-20 h-20 rounded-full bg-white/5"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] uppercase tracking-widest font-bold text-slate-300">Environment</span>
                    <i class="fa-solid fa-circle-check text-sm text-emerald-400"></i>
                </div>
                <p class="text-xl font-bold uppercase">{{ $data['system']['environment'] }}</p>
                <p class="text-xs text-slate-300 mt-0.5">Debug: {{ $data['system']['debug_mode'] }}</p>
                <p class="text-[10px] text-slate-400 mt-3 pt-3 border-t border-white/20 truncate">{{ $data['system']['url'] }}</p>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i class="fa-brands fa-php text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800 text-sm">Server & Runtime</h3>
                    <p class="text-[10px] text-gray-400">PHP, server, database versions</p>
                </div>
            </div>

            <div class="divide-y divide-gray-100">
                @php
                    $serverItems = [
                        ['label' => 'PHP Version', 'value' => $data['system']['php_version'], 'icon' => 'fa-brands fa-php'],
                        ['label' => 'Laravel Version', 'value' => $data['system']['laravel_version'], 'icon' => 'fa-brands fa-laravel'],
                        ['label' => 'Server Software', 'value' => $data['system']['server_software'], 'icon' => 'fa-server'],
                        ['label' => 'Server OS', 'value' => $data['system']['server_os'], 'icon' => 'fa-microchip'],
                        ['label' => 'Database Driver', 'value' => strtoupper($data['system']['database_driver']), 'icon' => 'fa-database'],
                        ['label' => 'Database Version', 'value' => $data['system']['database_version'], 'icon' => 'fa-database'],
                        ['label' => 'Cache Driver', 'value' => $data['system']['cache_driver'], 'icon' => 'fa-bolt'],
                        ['label' => 'Session Driver', 'value' => $data['system']['session_driver'], 'icon' => 'fa-clock'],
                        ['label' => 'Queue Driver', 'value' => $data['system']['queue_driver'], 'icon' => 'fa-list'],
                        ['label' => 'Timezone', 'value' => $data['system']['timezone'], 'icon' => 'fa-globe'],
                        ['label' => 'Locale', 'value' => $data['system']['locale'], 'icon' => 'fa-language'],
                    ];
                @endphp

                @foreach ($serverItems as $item)
                    <div class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-50/60 transition">
                        <i class="{{ $item['icon'] }} text-gray-400 text-sm w-4 text-center"></i>
                        <span class="text-xs text-gray-600 flex-1">{{ $item['label'] }}</span>
                        <span class="text-xs font-mono font-semibold text-gray-800 text-right">{{ $item['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="fa-solid fa-gauge-high text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm">Limits & Resources</h3>
                        <p class="text-[10px] text-gray-400">PHP execution limits</p>
                    </div>
                </div>

                <div class="divide-y divide-gray-100">
                    @php
                        $limitItems = [
                            ['label' => 'Max Upload Size', 'value' => $data['system']['max_upload_size'], 'icon' => 'fa-upload'],
                            ['label' => 'Max Post Size', 'value' => $data['system']['max_post_size'], 'icon' => 'fa-file-arrow-up'],
                            ['label' => 'Memory Limit', 'value' => $data['system']['memory_limit'], 'icon' => 'fa-memory'],
                            ['label' => 'Max Execution Time', 'value' => $data['system']['max_execution_time'], 'icon' => 'fa-stopwatch'],
                        ];
                    @endphp

                    @foreach ($limitItems as $item)
                        <div class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-50/60 transition">
                            <i class="fa-solid {{ $item['icon'] }} text-gray-400 text-sm w-4 text-center"></i>
                            <span class="text-xs text-gray-600 flex-1">{{ $item['label'] }}</span>
                            <span class="text-xs font-mono font-semibold text-gray-800">{{ $item['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-puzzle-piece text-sm"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">PHP Extensions</h3>
                            <p class="text-[10px] text-gray-400">Required & recommended extensions</p>
                        </div>
                    </div>

                    @php
                        $loadedCount = collect($data['extensions'])->filter()->count();
                        $totalCount = count($data['extensions']);
                    @endphp
                    <span class="text-[10px] font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">
                        {{ $loadedCount }}/{{ $totalCount }} loaded
                    </span>
                </div>

                <div class="p-4 grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach ($data['extensions'] as $name => $loaded)
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg border {{ $loaded ? 'bg-emerald-50/50 border-emerald-200' : 'bg-rose-50/50 border-rose-200' }}">
                            <i class="fa-solid {{ $loaded ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-rose-600' }} text-xs"></i>
                            <span class="text-xs font-medium {{ $loaded ? 'text-emerald-800' : 'text-rose-800' }}">{{ $name }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>

    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
        <i class="fa-solid fa-lightbulb text-amber-600 mt-0.5"></i>
        <div class="text-xs text-amber-800">
            <p class="font-medium mb-1">Performance Tip</p>
            <p>If cache, routes or config are not cached in production, enable them with <code class="bg-amber-100 px-1 rounded font-mono">php artisan optimize</code> for better performance.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                <i class="fa-solid fa-book text-sm"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800 text-sm">Documentation</h3>
                <p class="text-[10px] text-gray-400">Official documentation and resources</p>
            </div>
        </div>

        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

            <a href="https://ruhidjavadoff.blogspot.com/2021/03/rj-cms-lite.html" target="_blank"
               class="group flex items-start gap-3 p-4 rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <i class="fa-solid fa-cube"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-indigo-700">RJ CMS Lite</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Core CMS system documentation</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400 group-hover:text-indigo-500 mt-1"></i>
            </a>

            <a href="https://ruhidjavadoff.blogspot.com/2026/10/rj-theme.html" target="_blank"
               class="group flex items-start gap-3 p-4 rounded-lg border border-gray-200 hover:border-purple-300 hover:bg-purple-50/30 transition">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <i class="fa-solid fa-palette"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-purple-700">RJ Theme</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Theme system and customization</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400 group-hover:text-purple-500 mt-1"></i>
            </a>

            <a href="https://ruhidjavadoff.blogspot.com/2026/12/rj-ai-agent-agsaggal-ai.html" target="_blank"
               class="group flex items-start gap-3 p-4 rounded-lg border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50/30 transition">
                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-emerald-700">RJ AI Agent</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">Agsaggal AI — App support agent</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400 group-hover:text-emerald-500 mt-1"></i>
            </a>

            <a href="https://ruhidjavadoff.blogspot.com/2026/07/rj-cms-sistemlri.html" target="_blank"
               class="group flex items-start gap-3 p-4 rounded-lg border border-gray-200 hover:border-amber-300 hover:bg-amber-50/30 transition">
                <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <i class="fa-solid fa-server"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-amber-700">RJ CMS Systems</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">All RJ CMS systems overview</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400 group-hover:text-amber-500 mt-1"></i>
            </a>

            <a href="https://ruhidjavadoff.blogspot.com/2025/10/rj-shop-ai-1x-rj-shop-lite.html" target="_blank"
               class="group flex items-start gap-3 p-4 rounded-lg border border-gray-200 hover:border-rose-300 hover:bg-rose-50/30 transition">
                <div class="w-10 h-10 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 group-hover:scale-105 transition">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-rose-700">RJ SHOP AI 1.x</p>
                    <p class="text-[11px] text-gray-500 mt-0.5">RJ SHOP Lite — App documentation</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-gray-400 group-hover:text-rose-500 mt-1"></i>
            </a>

        </div>
    </div>

</div>

@endsection

@extends('admin.app')

@section('title', 'Widgets')

@section('content')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Homepage Widgets</h2>
            <p class="text-sm text-gray-500 mt-1">Manage and reorder homepage sections</p>
        </div>
    </div>
</div>

<div class="mb-6 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-6 md:p-8 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.4), transparent 40%), radial-gradient(circle at 80% 70%, rgba(255,255,255,0.3), transparent 40%);"></div>

    <div class="relative flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div class="flex-1">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-xs font-medium mb-4">
                <i class="fa-solid fa-cubes"></i>
                <span>{{ $widgets->count() }} Standard Widgets</span>
            </div>
            <h3 class="text-2xl md:text-3xl font-bold mb-2">Drag, drop, activate</h3>
            <p class="text-white/80 text-sm max-w-lg leading-relaxed">
                Widgets are predefined sections of your homepage. Reorder them by dragging the handle, toggle active state, and click edit to customize content.
            </p>
        </div>

        <div class="flex items-center gap-3 md:gap-4">
            <div class="text-center bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 min-w-[80px]">
                <div class="text-2xl font-bold">{{ $widgets->where('is_active', true)->count() }}</div>
                <div class="text-[10px] uppercase tracking-wider text-white/70 mt-0.5">Active</div>
            </div>
            <div class="text-center bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-3 min-w-[80px]">
                <div class="text-2xl font-bold">{{ $widgets->where('is_active', false)->count() }}</div>
                <div class="text-[10px] uppercase tracking-wider text-white/70 mt-0.5">Inactive</div>
            </div>
        </div>
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

@php
    $lockedWidgets = [
        'hero' => ['label' => 'Managed via Sliders', 'url' => route('admin.sliders.index')],
        'slider_mid' => ['label' => 'Managed via Sliders', 'url' => route('admin.sliders.index')],
        'faq_preview' => ['label' => 'Managed via FAQs', 'url' => route('admin.faqs.index')],
        'blog_preview' => ['label' => 'Managed via Blog', 'url' => route('admin.blog.index')],
    ];
@endphp

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left font-medium w-12"></th>
                <th class="px-4 py-3 text-left font-medium">Widget</th>
                <th class="px-4 py-3 text-left font-medium">Key</th>
                <th class="px-4 py-3 text-left font-medium w-20">Order</th>
                <th class="px-4 py-3 text-left font-medium w-32">Status</th>
                <th class="px-4 py-3 text-right font-medium w-40">Actions</th>
            </tr>
        </thead>
        <tbody id="widgetsList" class="divide-y divide-gray-100">
            @foreach ($widgets as $widget)
                @php
                    $locked = $lockedWidgets[$widget->key] ?? null;
                @endphp
                <tr class="hover:bg-gray-50 transition" data-id="{{ $widget->id }}">
                    <td class="px-4 py-3 text-gray-300 cursor-grab drag-handle select-none">
                        <i class="fa-solid fa-grip-vertical"></i>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg {{ $locked ? 'bg-amber-50 text-amber-600' : 'bg-indigo-50 text-indigo-600' }} flex items-center justify-center shrink-0">
                                <i class="fa-solid {{ $locked ? 'fa-lock' : 'fa-puzzle-piece' }} text-xs"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $widget->name }}</p>
                                @if($locked)
                                    <p class="text-[10px] text-amber-600 font-medium mt-0.5">
                                        <i class="fa-solid fa-link text-[8px]"></i> {{ $locked['label'] }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $widget->key }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">{{ $widget->sort_order }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.widgets.toggle', $widget) }}">
                            @csrf
                            @method('PUT')
                            <button class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded text-xs font-medium transition {{ $widget->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
                                <i class="fa-solid {{ $widget->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                {{ $widget->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($locked)
                            <a href="{{ $locked['url'] }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 transition"
                               title="{{ $locked['label'] }}">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                {{ $locked['label'] }}
                            </a>
                        @else
                            <a href="{{ route('admin.widgets.edit', $widget) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 transition">
                                <i class="fa-solid fa-pen text-[10px]"></i> Edit
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('widgetsList');
    if (!list) return;

    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function () {
            const order = Array.from(list.children).map(tr => tr.dataset.id);
            fetch('{{ route('admin.widgets.reorder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order: order })
            });
        }
    });
});
</script>

@endsection

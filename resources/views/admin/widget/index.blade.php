@extends('admin.app')

@section('title', 'Widgets')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Widgets</h2>
        <p class="text-sm text-gray-500 mt-1">Manage homepage sections</p>
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

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left font-medium w-12"></th>
                <th class="px-4 py-3 text-left font-medium">Widget</th>
                <th class="px-4 py-3 text-left font-medium">Key</th>
                <th class="px-4 py-3 text-left font-medium w-20">Order</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-right font-medium w-32">Actions</th>
            </tr>
        </thead>
        <tbody id="widgetsList" class="divide-y divide-gray-100">
            @foreach ($widgets as $widget)
                <tr class="hover:bg-gray-50" data-id="{{ $widget->id }}">
                    <td class="px-4 py-3 text-gray-300 cursor-grab drag-handle">
                        <i class="fa-solid fa-grip-vertical"></i>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800">{{ $widget->name }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $widget->key }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $widget->sort_order }}</td>
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
                        <a href="{{ route('admin.widgets.edit', $widget) }}"
                           class="w-8 h-8 inline-flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                            <i class="fa-solid fa-pen text-xs"></i>
                        </a>
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

@extends('admin.app')

@section('title', 'Sliders & Banners')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Sliders & Banners</h2>
        <p class="text-sm text-gray-500 mt-1">Manage sliders and banners for different sections</p>
    </div>
    <a href="{{ route('admin.sliders.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
        <i class="fa-solid fa-plus text-xs"></i> New Slider
    </a>
</div>

@if (session('status'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        {{ session('status') }}
    </div>
@endif

<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Name</th>
                <th class="px-4 py-3 text-left font-medium">Location</th>
                <th class="px-4 py-3 text-left font-medium">Type</th>
                <th class="px-4 py-3 text-left font-medium">Items</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-right font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($sliders as $slider)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium text-gray-800">{{ $slider->name }}</td>
                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $slider->location }}</td>
                    <td class="px-4 py-3">
                        @if ($slider->type === 'slider')
                            <span class="px-2 py-0.5 rounded text-xs bg-indigo-100 text-indigo-700">Slider</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs bg-amber-100 text-amber-700">Banner</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $slider->items_count }}</td>
                    <td class="px-4 py-3">
                        @if ($slider->status)
                            <span class="px-2 py-0.5 rounded text-xs bg-emerald-100 text-emerald-700">Active</span>
                        @else
                            <span class="px-2 py-0.5 rounded text-xs bg-gray-200 text-gray-600">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.sliders.edit', $slider) }}"
                               class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}"
                                  onsubmit="return confirm('Delete this slider and all its items?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                        No sliders yet. Create your first one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

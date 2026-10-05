@extends('admin.app')

@section('title', 'Print Zones')

@section('content')

<div x-data="{
    showModal: false,
    editing: null,
    formAction: '{{ route('admin.print-zones.store') }}',
    formMethod: 'POST'
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Print Zones</h2>
            <p class="text-sm text-gray-500 mt-1">Manage design placement areas (Full Front, Left Chest, etc.)</p>
        </div>
        <button @click="showModal = true; editing = null; formAction = '{{ route('admin.print-zones.store') }}'; formMethod = 'POST'"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> New Zone
        </button>
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

    @if ($zones->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($zones as $zone)
                <div class="bg-white rounded-xl border border-gray-200 p-5 hover:shadow-md transition group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white">
                            <i class="fa-solid fa-vector-square"></i>
                        </div>
                        <div class="flex items-center gap-1">
                            <form method="POST" action="{{ route('admin.print-zones.toggle', $zone) }}">
                                @csrf
                                @method('PUT')
                                <button class="w-8 h-8 flex items-center justify-center rounded {{ $zone->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                    <i class="fa-solid {{ $zone->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                </button>
                            </form>
                            <button @click='showModal = true; editing = @json($zone); formAction = "{{ route('admin.print-zones.update', $zone) }}"; formMethod = "PUT"'
                                    class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                            <form method="POST" action="{{ route('admin.print-zones.destroy', $zone) }}"
                                  onsubmit="return confirm('Delete this print zone?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <h3 class="font-semibold text-gray-800">{{ $zone->name }}</h3>
                    <p class="text-xs text-gray-400 font-mono truncate mt-0.5">{{ $zone->slug }}</p>

                    <div class="mt-3 space-y-1.5 text-xs">
                        @if ($zone->max_width_inch || $zone->max_height_inch)
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fa-solid fa-ruler-combined text-gray-400 w-4"></i>
                                <span>Max: {{ $zone->max_width_inch ?? '?' }}" × {{ $zone->max_height_inch ?? '?' }}"</span>
                            </div>
                        @endif
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-dollar-sign text-gray-400 w-4"></i>
                            <span>Addon: <span class="font-semibold text-indigo-600">+${{ number_format($zone->price_addon, 2) }}</span></span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600">
                            <i class="fa-solid fa-list-ol text-gray-400 w-4"></i>
                            <span>Sort: #{{ $zone->sort_order }}</span>
                        </div>
                    </div>

                    @if (!$zone->status)
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <span class="px-2 py-0.5 rounded text-[10px] bg-gray-200 text-gray-600 font-medium">Inactive</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-vector-square text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-400 text-sm">No print zones yet. Create your first one.</p>
        </div>
    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-md">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Print Zone' : 'New Print Zone'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" class="p-6 space-y-4">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :value="editing?.name || ''" required
                           placeholder="e.g. Full Front"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" :value="editing?.slug || ''"
                           placeholder="full-front"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from name.</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Width (inch)</label>
                        <input type="number" name="max_width_inch" step="0.01" min="0" :value="editing?.max_width_inch ?? ''"
                               placeholder="12.00"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Height (inch)</label>
                        <input type="number" name="max_height_inch" step="0.01" min="0" :value="editing?.max_height_inch ?? ''"
                               placeholder="16.00"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Addon</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm font-medium pointer-events-none">$</span>
                        <input type="number" name="price_addon" step="0.01" min="0" :value="editing?.price_addon ?? 0"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Extra cost for this print zone</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editing?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editing ? editing.status : true"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editing ? 'Update' : 'Create'"></span>
                    </button>
                    <button type="button" @click="showModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

@extends('admin.app')

@section('title', 'Delivery Zones')

@section('content')

@php
    $zonesData = $zones->map(fn($z) => [
        'id' => $z->id,
        'name' => $z->name,
        'slug' => $z->slug,
        'description' => $z->description,
        'color' => $z->color,
        'sort_order' => $z->sort_order,
        'status' => (bool) $z->status,
        'regions' => $z->regions->map(fn($r) => [
            'type' => $r->type,
            'value' => $r->value,
        ])->values(),
    ])->values();
@endphp

<div x-data="{
    zones: @js($zonesData),
    showModal: false,
    editing: null,
    formAction: '',
    formMethod: 'POST',
    formRegions: [],

    openModal(zone, action, method) {
        this.editing = zone;
        this.formAction = action;
        this.formMethod = method;
        this.formRegions = zone ? JSON.parse(JSON.stringify(zone.regions)) : [{type: 'state', value: ''}];
        this.showModal = true;
    },

    addRegion() {
        this.formRegions.push({type: 'state', value: ''});
    },

    removeRegion(index) {
        this.formRegions.splice(index, 1);
    }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Delivery Zones</h2>
            <p class="text-sm text-gray-500 mt-1">Group regions for delivery pricing (e.g. West Coast, East Coast)</p>
        </div>
        <button @click="openModal(null, '{{ route('admin.delivery-zones.store') }}', 'POST')"
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($zones as $zone)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="h-1.5" style="background-color: {{ $zone->color }}"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $zone->name }}</h3>
                                <p class="text-[10px] text-gray-400 font-mono truncate mt-0.5">{{ $zone->slug }}</p>
                            </div>
                            <span class="text-[10px] font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded shrink-0">
                                #{{ $zone->sort_order }}
                            </span>
                        </div>

                        @if ($zone->description)
                            <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $zone->description }}</p>
                        @endif

                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">
                                    Regions ({{ $zone->regions->count() }})
                                </p>
                                @if (!$zone->status)
                                    <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-medium">Inactive</span>
                                @endif
                            </div>

                            @if ($zone->regions->count())
                                <div class="flex flex-wrap gap-1 max-h-20 overflow-hidden">
                                    @foreach ($zone->regions->take(8) as $region)
                                        <span class="text-[10px] font-mono px-1.5 py-0.5 rounded
                                            @if($region->type === 'state') bg-blue-50 text-blue-700
                                            @elseif($region->type === 'city') bg-purple-50 text-purple-700
                                            @else bg-amber-50 text-amber-700 @endif">
                                            {{ strtoupper($region->type) }}:{{ $region->value }}
                                        </span>
                                    @endforeach
                                    @if ($zone->regions->count() > 8)
                                        <span class="text-[10px] text-gray-400 px-1.5 py-0.5">+{{ $zone->regions->count() - 8 }} more</span>
                                    @endif
                                </div>
                            @else
                                <p class="text-[10px] text-gray-400">No regions assigned</p>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-gray-100 flex items-center gap-1.5">
                            <form method="POST" action="{{ route('admin.delivery-zones.toggle', $zone) }}">
                                @csrf
                                @method('PUT')
                                <button class="w-8 h-8 flex items-center justify-center rounded {{ $zone->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                    <i class="fa-solid {{ $zone->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                                </button>
                            </form>

                            <button @click="openModal(zones.find(z => z.id === {{ $zone->id }}), '{{ route('admin.delivery-zones.update', $zone) }}', 'PUT')"
                                    class="flex-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg transition">
                                <i class="fa-solid fa-pen text-xs mr-1"></i> Edit
                            </button>

                            <form method="POST" action="{{ route('admin.delivery-zones.destroy', $zone) }}"
                                  onsubmit="return confirm('Delete this zone and all its rates?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-map-location-dot text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-400 text-sm mb-1">No delivery zones yet.</p>
            <p class="text-gray-400 text-xs">Create zones to group regions for pricing.</p>
        </div>
    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Zone' : 'New Zone'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" class="p-6 space-y-4">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" :value="editing?.name || ''" required
                               placeholder="e.g. West Coast"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="color" name="color" :value="editing?.color || '#6366f1'"
                               class="w-full h-11 rounded-lg border border-gray-300 cursor-pointer">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" :value="editing?.slug || ''"
                           placeholder="west-coast"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" maxlength="500" x-text="editing?.description || ''"
                              placeholder="Optional note"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div class="pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Regions</label>
                            <p class="text-[10px] text-gray-500 mt-0.5">Add states, cities or ZIP codes covered by this zone</p>
                        </div>
                        <button type="button" @click="addRegion()"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Region
                        </button>
                    </div>

                    <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <template x-for="(region, index) in formRegions" :key="index">
                            <div class="flex items-center gap-2">
                                <select :name="`regions[${index}][type]`" x-model="region.type"
                                        class="w-28 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="state">State</option>
                                    <option value="city">City</option>
                                    <option value="zip">ZIP</option>
                                </select>
                                <input type="text" :name="`regions[${index}][value]`" x-model="region.value"
                                       :placeholder="region.type === 'state' ? 'CA' : region.type === 'city' ? 'Los Angeles' : '90001'"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <button type="button" @click="removeRegion(index)"
                                        class="w-9 h-9 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </template>

                        <template x-if="formRegions.length === 0">
                            <div class="text-center py-4 text-xs text-gray-400 border-2 border-dashed border-gray-200 rounded-lg">
                                No regions added yet
                            </div>
                        </template>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2 border-t border-gray-100">
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

<style>[x-cloak]{display:none!important;}.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}</style>

@endsection

@extends('admin.app')

@section('title', 'Delivery Rates')

@section('content')

@php
    $ratesData = $rates->map(fn($r) => [
        'id' => $r->id,
        'zone_id' => $r->zone_id,
        'price' => (float) $r->price,
        'min_days' => $r->min_days,
        'max_days' => $r->max_days,
        'free_enabled' => (bool) $r->free_enabled,
        'free_type' => $r->free_type,
        'free_min_price' => $r->free_min_price ? (float) $r->free_min_price : null,
        'free_min_qty' => $r->free_min_qty,
        'cod_enabled' => (bool) $r->cod_enabled,
        'status' => (bool) $r->status,
    ])->values();

    $zonesData = $zones->map(fn($z) => [
        'id' => $z->id,
        'name' => $z->name,
        'slug' => $z->slug,
        'color' => $z->color,
        'regions_count' => $z->regions->count(),
    ])->values();
@endphp

<div x-data="{
    rates: @js($ratesData),
    zones: @js($zonesData),
    selectedBranchId: {{ $selectedBranchId ?? 'null' }},
    showModal: false,
    editing: null,
    editingZoneId: null,
    formAction: '',
    formMethod: 'POST',

    getRate(zoneId) {
        return this.rates.find(r => r.zone_id === zoneId);
    },

    openCreate(zoneId) {
        this.editing = null;
        this.editingZoneId = zoneId;
        this.formAction = '{{ route('admin.delivery-rates.store') }}';
        this.formMethod = 'POST';
        this.showModal = true;
    },

    openEdit(zoneId) {
        const rate = this.getRate(zoneId);
        if (!rate) return;
        this.editing = rate;
        this.editingZoneId = zoneId;
        this.formAction = `/admin/delivery-rates/${rate.id}`;
        this.formMethod = 'PUT';
        this.showModal = true;
    },

    getZone(zoneId) {
        return this.zones.find(z => z.id === zoneId);
    }
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Delivery Rates</h2>
            <p class="text-sm text-gray-500 mt-1">Set delivery price per branch and zone</p>
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

    @if ($branches->isEmpty() || $zones->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-truck-fast text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm mb-1">Setup required</p>
            <p class="text-gray-400 text-xs mb-4">You need at least one branch and one delivery zone.</p>
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('admin.branches.index') }}" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-store text-[10px] mr-1"></i> Manage Branches
                </a>
                <a href="{{ route('admin.delivery-zones.index') }}" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-map-location-dot text-[10px] mr-1"></i> Manage Zones
                </a>
            </div>
        </div>
    @else

        <div class="bg-white rounded-xl border border-gray-200 p-2 mb-6 inline-flex flex-wrap gap-1">
            @foreach ($branches as $branch)
                <a href="{{ route('admin.delivery-rates.index', ['branch_id' => $branch->id]) }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm transition
                   {{ $selectedBranchId == $branch->id ? 'bg-indigo-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fa-solid fa-store text-xs"></i>
                    <span class="font-medium">{{ $branch->name }}</span>
                    @if ($branch->is_default)
                        <i class="fa-solid fa-star text-[10px] {{ $selectedBranchId == $branch->id ? 'text-amber-300' : 'text-amber-500' }}"></i>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($zones as $zone)
                @php
                    $rate = $rates->get($zone->id);
                @endphp
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition">
                    <div class="h-1.5" style="background-color: {{ $zone->color }}"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-2 mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-800 truncate">{{ $zone->name }}</h3>
                                <p class="text-[10px] text-gray-400">{{ $zone->regions->count() }} regions</p>
                            </div>
                            @if ($rate)
                                @if (!$rate->status)
                                    <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-medium shrink-0">Off</span>
                                @else
                                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-medium shrink-0">Active</span>
                                @endif
                            @endif
                        </div>

                        @if ($rate)
                            <div class="space-y-2 mb-4">
                                <div class="flex items-baseline gap-1">
                                    <span class="text-2xl font-bold text-gray-800">${{ number_format($rate->price, 2) }}</span>
                                    <span class="text-xs text-gray-400">/ order</span>
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-600">
                                    <i class="fa-solid fa-clock text-gray-400 w-4"></i>
                                    <span>{{ $rate->delivery_time }}</span>
                                </div>

                                @if ($rate->free_enabled && $rate->free_label)
                                    <div class="flex items-center gap-2 text-xs">
                                        <i class="fa-solid fa-gift text-emerald-500 w-4"></i>
                                        <span class="text-emerald-700 font-medium">{{ $rate->free_label }}</span>
                                    </div>
                                @endif

                                @if ($rate->cod_enabled)
                                    <div class="flex items-center gap-2 text-xs text-gray-600">
                                        <i class="fa-solid fa-money-bill-wave text-gray-400 w-4"></i>
                                        <span>Cash on delivery available</span>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-gray-100 flex items-center gap-1.5">
                                <form method="POST" action="{{ route('admin.delivery-rates.toggle', $rate) }}" class="shrink-0">
                                    @csrf
                                    @method('PUT')
                                    <button class="w-8 h-8 flex items-center justify-center rounded {{ $rate->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition">
                                        <i class="fa-solid {{ $rate->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                                    </button>
                                </form>
                                <button @click="openEdit({{ $zone->id }})"
                                        class="flex-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg transition">
                                    <i class="fa-solid fa-pen text-xs mr-1"></i> Edit
                                </button>
                                <form method="POST" action="{{ route('admin.delivery-rates.destroy', $rate) }}"
                                      onsubmit="return confirm('Remove this rate?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="py-6 text-center border-2 border-dashed border-gray-200 rounded-lg mb-4">
                                <i class="fa-solid fa-circle-plus text-2xl text-gray-300 mb-1"></i>
                                <p class="text-[11px] text-gray-400">No rate set</p>
                            </div>

                            <button @click="openCreate({{ $zone->id }})"
                                    class="w-full text-xs bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition">
                                <i class="fa-solid fa-plus text-[10px] mr-1"></i> Set Rate
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white"
                         :style="`background-color: ${getZone(editingZoneId)?.color || '#6366f1'}`">
                        <i class="fa-solid fa-truck-fast text-sm"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm" x-text="editing ? 'Edit Rate' : 'Set Rate'"></h3>
                        <p class="text-[11px] text-gray-500" x-text="getZone(editingZoneId)?.name"></p>
                    </div>
                </div>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" class="p-6 space-y-4">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                <input type="hidden" name="zone_id" :value="editingZoneId">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">$</span>
                            <input type="number" name="price" step="0.01" min="0" required
                                   :value="editing?.price ?? 0"
                                   class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Min Days</label>
                            <input type="number" name="min_days" min="0" required
                                   :value="editing?.min_days ?? 1"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Days</label>
                            <input type="number" name="max_days" min="0" required
                                   :value="editing?.max_days ?? 5"
                                   class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-3" x-data="{ freeOn: false }" x-init="$watch('editing', v => freeOn = v?.free_enabled || false); freeOn = editing?.free_enabled || false">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="free_enabled" value="1" x-model="freeOn"
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-5 h-5">
                        <div>
                            <p class="text-sm font-medium text-gray-800">Free Delivery Offer</p>
                            <p class="text-[11px] text-gray-500">Enable free delivery for certain orders</p>
                        </div>
                    </label>

                    <div x-show="freeOn" x-collapse class="pl-8 space-y-3">
                        <div class="flex items-center gap-2">
                            <label class="flex items-center gap-2 text-xs cursor-pointer">
                                <input type="radio" name="free_type" value="price"
                                       :checked="(editing?.free_type || 'price') === 'price'"
                                       class="text-indigo-600 focus:ring-indigo-500">
                                By price
                            </label>
                            <label class="flex items-center gap-2 text-xs cursor-pointer">
                                <input type="radio" name="free_type" value="quantity"
                                       :checked="editing?.free_type === 'quantity'"
                                       class="text-indigo-600 focus:ring-indigo-500">
                                By quantity
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Minimum order amount ($)</label>
                            <input type="number" name="free_min_price" step="0.01" min="0"
                                   :value="editing?.free_min_price ?? ''"
                                   placeholder="e.g. 100"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Or minimum quantity (items)</label>
                            <input type="number" name="free_min_qty" min="0"
                                   :value="editing?.free_min_qty ?? ''"
                                   placeholder="e.g. 10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <p class="text-[10px] text-gray-500 mt-1">Leave empty to ignore</p>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 space-y-2">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="cod_enabled" value="1"
                               :checked="editing ? editing.cod_enabled : true"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Cash on Delivery available
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="status" value="1"
                               :checked="editing ? editing.status : true"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Active
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editing ? 'Update Rate' : 'Save Rate'"></span>
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

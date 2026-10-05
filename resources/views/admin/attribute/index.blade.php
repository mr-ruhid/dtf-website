@extends('admin.app')

@section('title', 'Attributes')

@section('content')

<div x-data="{
    selectedId: {{ $attributes->first()?->id ?? 'null' }},
    showAttrModal: false,
    editingAttr: null,
    attrFormAction: '{{ route('admin.attributes.store') }}',
    attrFormMethod: 'POST',
    showValueModal: false,
    editingValue: null,
    valueFormAction: '',
    valueFormMethod: 'POST'
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Attributes</h2>
            <p class="text-sm text-gray-500 mt-1">Manage product attributes like Size, Color and custom ones</p>
        </div>
        <button @click="showAttrModal = true; editingAttr = null; attrFormAction = '{{ route('admin.attributes.store') }}'; attrFormMethod = 'POST'"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> New Attribute
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-24">
                <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-2">
                    <i class="fa-solid fa-lock text-amber-500 text-xs"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Locked Attributes</h3>
                </div>

                <div class="divide-y divide-gray-100">
                    @foreach ($attributes->where('is_locked', true) as $attr)
                        <button @click="selectedId = {{ $attr->id }}"
                                :class="selectedId === {{ $attr->id }} ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'border-l-4 border-transparent hover:bg-gray-50'"
                                class="w-full text-left px-5 py-3 transition flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0
                                {{ $attr->type === 'color' ? 'bg-gradient-to-br from-pink-500 to-red-500' : 'bg-gradient-to-br from-indigo-500 to-purple-600' }} text-white text-xs">
                                <i class="fa-solid {{ $attr->type === 'color' ? 'fa-palette' : 'fa-ruler' }}"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $attr->name }}</p>
                                <p class="text-xs text-gray-400">{{ $attr->values->count() }} values</p>
                            </div>
                            @if (!$attr->status)
                                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                            @endif
                        </button>
                    @endforeach
                </div>

                @if ($attributes->where('is_locked', false)->count())
                    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                        <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">Custom</p>
                    </div>
                    <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                        @foreach ($attributes->where('is_locked', false) as $attr)
                            <div class="group flex items-center transition">
                                <button @click="selectedId = {{ $attr->id }}"
                                        :class="selectedId === {{ $attr->id }} ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'border-l-4 border-transparent hover:bg-gray-50'"
                                        class="flex-1 text-left px-5 py-3 transition flex items-center gap-3 min-w-0">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 bg-slate-100 text-slate-600">
                                        <i class="fa-solid fa-tag text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $attr->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $attr->values->count() }} values</p>
                                    </div>
                                </button>
                                <div class="flex items-center gap-1 pr-2 opacity-0 group-hover:opacity-100 transition">
                                    <button @click="showAttrModal = true; editingAttr = @json($attr); attrFormAction = '{{ route('admin.attributes.update', $attr) }}'; attrFormMethod = 'PUT'"
                                            class="w-7 h-7 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600">
                                        <i class="fa-solid fa-pen text-[10px]"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.attributes.destroy', $attr) }}"
                                          onsubmit="return confirm('Delete this attribute and all its values?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-600">
                                            <i class="fa-solid fa-trash text-[10px]"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2">
            @foreach ($attributes as $attr)
                <div x-show="selectedId === {{ $attr->id }}" x-cloak
                     class="bg-white rounded-xl border border-gray-200 overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                {{ $attr->type === 'color' ? 'bg-gradient-to-br from-pink-500 to-red-500' : 'bg-gradient-to-br from-indigo-500 to-purple-600' }} text-white">
                                <i class="fa-solid {{ $attr->type === 'color' ? 'fa-palette' : ($attr->type === 'text' ? 'fa-font' : 'fa-ruler') }}"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-gray-800">{{ $attr->name }}</h3>
                                    @if ($attr->is_locked)
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-amber-100 text-amber-700 font-medium">
                                            <i class="fa-solid fa-lock text-[8px]"></i> Locked
                                        </span>
                                    @endif
                                    @if (!$attr->status)
                                        <span class="px-2 py-0.5 rounded text-[10px] bg-gray-200 text-gray-600 font-medium">Inactive</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 font-mono">{{ $attr->slug }} · {{ $attr->type }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <form method="POST" action="{{ route('admin.attributes.toggle', $attr) }}">
                                @csrf
                                @method('PUT')
                                <button class="w-8 h-8 flex items-center justify-center rounded {{ $attr->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                    <i class="fa-solid {{ $attr->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                </button>
                            </form>
                            <button @click="showAttrModal = true; editingAttr = @json($attr); attrFormAction = '{{ route('admin.attributes.update', $attr) }}'; attrFormMethod = 'PUT'"
                                    class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-sm font-medium text-gray-700">Values ({{ $attr->values->count() }})</h4>
                            <button @click="showValueModal = true; editingValue = null; valueFormAction = '{{ route('admin.attributes.values.store', $attr) }}'; valueFormMethod = 'POST'"
                                    class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                                <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Value
                            </button>
                        </div>

                        @if ($attr->values->count())
                            @if ($attr->type === 'color')
                                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                    @foreach ($attr->values as $value)
                                        <div class="group border border-gray-200 rounded-lg p-3 hover:shadow-md transition">
                                            <div class="aspect-square rounded-md mb-2 border border-gray-100" style="background-color: {{ $value->color_code ?? '#ccc' }}"></div>
                                            <div class="flex items-center justify-between gap-1">
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs font-medium text-gray-800 truncate">{{ $value->value }}</p>
                                                    <p class="text-[10px] text-gray-400 font-mono">{{ $value->color_code }}</p>
                                                </div>
                                                @if (!$value->status)
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></span>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-indigo-600 font-semibold mt-1.5 bg-indigo-50 px-2 py-0.5 rounded inline-block">+${{ number_format($value->price_adjustment, 2) }}</p>
                                            <div class="flex items-center gap-1 mt-2 opacity-0 group-hover:opacity-100 transition">
                                                <form method="POST" action="{{ route('admin.attributes.values.toggle', [$attr, $value]) }}" class="flex-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="w-full text-[10px] {{ $value->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-500 hover:bg-gray-100' }} py-1 rounded">
                                                        <i class="fa-solid {{ $value->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                    </button>
                                                </form>
                                                <button @click="showValueModal = true; editingValue = @json($value); valueFormAction = '{{ route('admin.attributes.values.update', [$attr, $value]) }}'; valueFormMethod = 'PUT'"
                                                        class="flex-1 text-[10px] text-indigo-600 hover:bg-indigo-50 py-1 rounded">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <form method="POST" action="{{ route('admin.attributes.values.destroy', [$attr, $value]) }}"
                                                      onsubmit="return confirm('Delete this value?')" class="flex-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="w-full text-[10px] text-red-600 hover:bg-red-50 py-1 rounded">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="border border-gray-200 rounded-lg divide-y divide-gray-100">
                                    @foreach ($attr->values as $value)
                                        <div class="group flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50/60 transition">
                                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded font-mono w-10 text-center">{{ $value->sort_order }}</span>
                                            <p class="flex-1 text-sm text-gray-800 {{ !$value->status ? 'line-through text-gray-400' : '' }}">{{ $value->value }}</p>
                                            <span class="text-[10px] text-indigo-600 font-semibold bg-indigo-50 px-2 py-0.5 rounded">+${{ number_format($value->price_adjustment, 2) }}</span>
                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                                <form method="POST" action="{{ route('admin.attributes.values.toggle', [$attr, $value]) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="w-7 h-7 flex items-center justify-center rounded {{ $value->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                                        <i class="fa-solid {{ $value->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                                                    </button>
                                                </form>
                                                <button @click="showValueModal = true; editingValue = @json($value); valueFormAction = '{{ route('admin.attributes.values.update', [$attr, $value]) }}'; valueFormMethod = 'PUT'"
                                                        class="w-7 h-7 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600">
                                                    <i class="fa-solid fa-pen text-[10px]"></i>
                                                </button>
                                                <form method="POST" action="{{ route('admin.attributes.values.destroy', [$attr, $value]) }}"
                                                      onsubmit="return confirm('Delete this value?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-600">
                                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @else
                            <div class="border-2 border-dashed border-gray-200 rounded-lg py-10 text-center">
                                <i class="fa-solid fa-tag text-2xl text-gray-300 mb-2"></i>
                                <p class="text-gray-400 text-sm">No values yet. Add the first one.</p>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6">
        <div class="flex items-center gap-2 mb-4">
            <i class="fa-solid fa-list text-indigo-600 text-sm"></i>
            <h3 class="font-semibold text-gray-800 text-sm">All Attributes Overview</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
            @foreach ($attributes as $attr)
                <button @click="selectedId = {{ $attr->id }}; window.scrollTo({top: 0, behavior: 'smooth'})"
                        :class="selectedId === {{ $attr->id }} ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 bg-white hover:border-gray-300'"
                        class="border-2 rounded-xl p-4 text-left transition">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center
                            {{ $attr->type === 'color' ? 'bg-gradient-to-br from-pink-500 to-red-500' : 'bg-gradient-to-br from-indigo-500 to-purple-600' }} text-white text-[10px]">
                            <i class="fa-solid {{ $attr->type === 'color' ? 'fa-palette' : ($attr->type === 'text' ? 'fa-font' : 'fa-ruler') }}"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 truncate flex-1">{{ $attr->name }}</p>
                        @if ($attr->is_locked)
                            <i class="fa-solid fa-lock text-amber-500 text-[10px]"></i>
                        @endif
                    </div>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span>{{ $attr->values->count() }} values</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] bg-gray-100 font-mono">{{ $attr->type }}</span>
                    </div>
                </button>
            @endforeach
        </div>
    </div>

    <div x-show="showAttrModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showAttrModal = false">
        <div class="bg-white rounded-xl w-full max-w-md">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800" x-text="editingAttr ? 'Edit Attribute' : 'New Attribute'"></h3>
                <button @click="showAttrModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="attrFormAction" class="p-6 space-y-4">
                @csrf
                <template x-if="attrFormMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :value="editingAttr?.name || ''" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" :value="editingAttr?.slug || ''"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                    <select name="type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="select" :selected="editingAttr?.type === 'select'">Select (dropdown)</option>
                        <option value="color" :selected="editingAttr?.type === 'color'">Color (color picker)</option>
                        <option value="text" :selected="editingAttr?.type === 'text'">Text (free input)</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editingAttr?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editingAttr ? editingAttr.status : true"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editingAttr ? 'Update' : 'Create'"></span>
                    </button>
                    <button type="button" @click="showAttrModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

    <div x-show="showValueModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showValueModal = false">
        <div class="bg-white rounded-xl w-full max-w-md">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800" x-text="editingValue ? 'Edit Value' : 'Add Value'"></h3>
                <button @click="showValueModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="valueFormAction" class="p-6 space-y-4" x-data="{ colorCode: '' }" x-init="$watch('editingValue', v => colorCode = v?.color_code || '')">
                @csrf
                <template x-if="valueFormMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Value <span class="text-red-500">*</span></label>
                    <input type="text" name="value" :value="editingValue?.value || ''" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div x-show="['color'].includes(editingAttr?.type) || @json($attributes->pluck('type', 'id'))[selectedId] === 'color'">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <div class="flex items-center gap-3">
                        <input type="color" x-model="colorCode" @input="$el.nextElementSibling.value = colorCode"
                               class="w-14 h-11 rounded-lg border border-gray-300 cursor-pointer">
                        <input type="text" name="color_code" x-model="colorCode" maxlength="20" placeholder="#000000"
                               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pick from palette or enter hex code</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Adjustment</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm font-medium pointer-events-none">$</span>
                        <input type="number" name="price_adjustment" step="0.01" min="0" :value="editingValue?.price_adjustment ?? 0"
                               class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Extra amount added to base price (default for all products)</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editingValue?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editingValue ? editingValue.status : true"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editingValue ? 'Update' : 'Add'"></span>
                    </button>
                    <button type="button" @click="showValueModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

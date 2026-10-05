@extends('admin.app')

@section('title', 'New Product')

@section('content')

<div x-data="productForm()">

    <div class="mb-6">
        <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to products
        </a>
        <h2 class="text-xl font-semibold text-gray-800 mt-2">New Product</h2>
    </div>

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-indigo-500"></i> Basic Information
                    </h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="name" @input="generateSlug()" required
                               value="{{ old('name') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" x-model="slug" value="{{ old('slug') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}"
                                   placeholder="e.g. DTF-001"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <select name="model_id" x-model="modelId" @change="filterCategories()"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select model</option>
                                @foreach ($models as $model)
                                    <option value="{{ $model->id }}" data-model="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" data-model="{{ $cat->model_id }}" :disabled="modelId && modelId != '{{ $cat->model_id }}'" :class="modelId && modelId != '{{ $cat->model_id }}' ? 'hidden' : ''">
                                        {{ $cat->model->name ?? '' }} → {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Print Type <span class="text-red-500">*</span></label>
                        <select name="print_type" x-model="printType" required
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="none">None (Supplies, Films — no design upload)</option>
                            <option value="apparel">Apparel (Mockup + Zone selection)</option>
                            <option value="custom_size">Custom Size (Customer enters W×H)</option>
                            <option value="fixed_area">Fixed Area (Simple design upload)</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Determines how design upload works for this product</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="short_description" rows="2" maxlength="500"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('short_description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                        <textarea name="description" id="editor" rows="10"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-images text-indigo-500"></i> Product Images
                    </h3>
                    <input type="file" name="images[]" multiple accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500">Multiple images · JPG, PNG, WEBP · Max 5MB each</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-layer-group text-indigo-500"></i> Quantity Pricing (Tiers)
                        </h3>
                        <button type="button" @click="addTier"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Tier
                        </button>
                    </div>
                    <p class="text-xs text-gray-500">Alibaba-style pricing — different price per quantity</p>

                    <div class="space-y-2">
                        <template x-for="(tier, index) in tiers" :key="index">
                            <div class="flex items-center gap-2">
                                <div class="relative flex-1">
                                    <input type="number" :name="`tiers[${index}][min_qty]`" x-model="tier.min_qty" min="1" placeholder="Min"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400">pcs</span>
                                </div>
                                <span class="text-gray-400 text-xs">—</span>
                                <div class="relative flex-1">
                                    <input type="number" :name="`tiers[${index}][max_qty]`" x-model="tier.max_qty" min="1" placeholder="Max (empty = +)"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-[10px] text-gray-400">pcs</span>
                                </div>
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">$</span>
                                    <input type="number" :name="`tiers[${index}][price]`" x-model="tier.price" step="0.01" min="0" placeholder="Price"
                                           class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <button type="button" @click="tiers.splice(index, 1)"
                                        class="w-9 h-9 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-tags text-indigo-500"></i> Attributes
                    </h3>
                    <p class="text-xs text-gray-500">Select which values from each attribute this product supports</p>

                    @foreach ($attributes as $attribute)
                        <div class="border border-gray-200 rounded-lg overflow-hidden" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                            <button type="button" @click="open = !open"
                                    class="w-full px-4 py-3 bg-gray-50 hover:bg-gray-100 flex items-center justify-between transition">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-lg {{ $attribute->type === 'color' ? 'bg-gradient-to-br from-pink-500 to-red-500' : 'bg-gradient-to-br from-indigo-500 to-purple-600' }} flex items-center justify-center text-white text-[10px]">
                                        <i class="fa-solid {{ $attribute->type === 'color' ? 'fa-palette' : 'fa-ruler' }}"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-medium text-gray-800">{{ $attribute->name }}</p>
                                        <p class="text-[10px] text-gray-400">
                                            <span x-text="Object.keys(selectedAttributeValues['{{ $attribute->id }}'] || {}).length"></span> selected
                                        </p>
                                    </div>
                                </div>
                                <i :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-xs text-gray-400 transition"></i>
                            </button>

                            <div x-show="open" x-collapse class="p-4 border-t border-gray-100">
                                @if ($attribute->type === 'color')
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                                        @foreach ($attribute->activeValues as $value)
                                            <div class="border rounded-lg p-3 transition"
                                                 :class="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}'] ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200'">
                                                <label class="cursor-pointer block">
                                                    <input type="checkbox" class="sr-only"
                                                           @change="toggleAttributeValue({{ $attribute->id }}, {{ $value->id }}, '{{ $value->value }}')"
                                                           :checked="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">

                                                    <div class="aspect-square rounded-md mb-2 border border-gray-100 relative" style="background-color: {{ $value->color_code }}">
                                                        <div x-show="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']"
                                                             class="absolute top-1 right-1 w-5 h-5 bg-indigo-500 rounded-full flex items-center justify-center">
                                                            <i class="fa-solid fa-check text-white text-[10px]"></i>
                                                        </div>
                                                    </div>
                                                    <p class="text-xs font-medium text-gray-800 truncate">{{ $value->value }}</p>
                                                </label>

                                                <template x-if="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">
                                                    <div class="mt-2 space-y-2">
                                                        <div>
                                                            <label class="block text-[10px] text-gray-500 mb-0.5">Price (+$)</label>
                                                            <input type="number" step="0.01" min="0"
                                                                   :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][price_override]`"
                                                                   x-model="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override"
                                                                   :placeholder="{{ $value->price_adjustment }}"
                                                                   class="w-full px-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] text-gray-500 mb-0.5">Image</label>
                                                            <input type="file" accept="image/*"
                                                                   :name="`attribute_images[{{ $attribute->id }}][{{ $value->id }}]`"
                                                                   class="w-full text-[10px] file:mr-1 file:py-1 file:px-2 file:border-0 file:bg-indigo-100 file:text-indigo-700 file:text-[10px] file:rounded">
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        @foreach ($attribute->activeValues as $value)
                                            <label class="flex items-center gap-3 p-3 rounded-lg border transition cursor-pointer"
                                                   :class="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}'] ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200 hover:bg-gray-50'">
                                                <input type="checkbox"
                                                       @change="toggleAttributeValue({{ $attribute->id }}, {{ $value->id }}, '{{ $value->value }}')"
                                                       :checked="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']"
                                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                <span class="flex-1 text-sm text-gray-800">{{ $value->value }}</span>
                                                <span class="text-[10px] text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded">default +${{ number_format($value->price_adjustment, 2) }}</span>

                                                <template x-if="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">
                                                    <div class="relative" @click.stop>
                                                        <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 text-xs">+$</span>
                                                        <input type="number" step="0.01" min="0"
                                                               :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][price_override]`"
                                                               x-model="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override"
                                                               :placeholder="{{ $value->price_adjustment }}"
                                                               class="w-20 pl-6 pr-2 py-1 border border-gray-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                    </div>
                                                </template>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4" x-show="printType === 'apparel'">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-vector-square text-indigo-500"></i> Print Zones
                    </h3>
                    <p class="text-xs text-gray-500">Select which placement areas are available for this product</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($printZones as $zone)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="print_zones[]" value="{{ $zone->id }}"
                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ $zone->name }}</p>
                                    @if ($zone->max_width_inch || $zone->max_height_inch)
                                        <p class="text-[10px] text-gray-500">{{ $zone->max_width_inch ?? '?' }}" × {{ $zone->max_height_inch ?? '?' }}"</p>
                                    @endif
                                </div>
                                @if ($zone->price_addon > 0)
                                    <span class="text-[10px] text-indigo-600 font-medium bg-indigo-50 px-2 py-0.5 rounded">+${{ number_format($zone->price_addon, 2) }}</span>
                                @endif
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-gray-700 text-sm">SEO Settings</h3>
                                <p class="text-xs text-gray-400">Search engine optimization</p>
                            </div>
                        </div>
                        <i :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform"></i>
                    </button>

                    <div x-show="open" x-collapse class="space-y-4 pt-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="200"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="300"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('meta_description') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" maxlength="300"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Pricing</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Base Price <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">$</span>
                            <input type="number" name="base_price" step="0.01" min="0" required
                                   value="{{ old('base_price', '0.00') }}"
                                   class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">$</span>
                            <input type="number" name="sale_price" step="0.01" min="0"
                                   value="{{ old('sale_price') }}"
                                   class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Optional — shown crossed out</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" name="stock" min="0"
                               value="{{ old('stock', 0) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Publish</h3>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Featured
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="has_variants" value="1" {{ old('has_variants') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Has Variants (Size × Color)
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Create Product
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                           class="block text-center text-sm text-gray-600 hover:text-gray-800 py-2">Cancel</a>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
function productForm() {
    return {
        name: '{{ old('name') }}',
        slug: '{{ old('slug') }}',
        modelId: '{{ old('model_id') }}',
        printType: '{{ old('print_type', 'none') }}',
        tiers: [],
        selectedAttributeValues: {},

        init() {
            const oldTiers = @json(old('tiers', []));
            if (oldTiers.length) {
                this.tiers = oldTiers.map(t => ({min_qty: t.min_qty || '', max_qty: t.max_qty || '', price: t.price || ''}));
            } else {
                this.tiers = [{min_qty: 1, max_qty: 11, price: ''}];
            }

            const oldAttrValues = @json(old('attribute_values', []));
            Object.keys(oldAttrValues).forEach(attrId => {
                this.selectedAttributeValues[attrId] = {};
                oldAttrValues[attrId].forEach((v, idx) => {
                    if (v.value_id) {
                        this.selectedAttributeValues[attrId][v.value_id] = {
                            index: idx,
                            price_override: v.price_override || ''
                        };
                    }
                });
            });
        },

        generateSlug() {
            this.slug = this.name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
        },

        filterCategories() {
            // Handled by :disabled on options
        },

        addTier() {
            const last = this.tiers[this.tiers.length - 1];
            let nextMin = 1;
            if (last && last.max_qty) nextMin = parseInt(last.max_qty) + 1;
            else if (last && last.min_qty) nextMin = parseInt(last.min_qty) + 11;
            this.tiers.push({min_qty: nextMin, max_qty: '', price: ''});
        },

        toggleAttributeValue(attrId, valueId, valueName) {
            if (!this.selectedAttributeValues[attrId]) {
                this.selectedAttributeValues[attrId] = {};
            }

            if (this.selectedAttributeValues[attrId][valueId]) {
                delete this.selectedAttributeValues[attrId][valueId];
            } else {
                const index = Object.keys(this.selectedAttributeValues[attrId]).length;
                this.selectedAttributeValues[attrId][valueId] = {
                    index: index,
                    value_id: valueId,
                    value_name: valueName,
                    price_override: ''
                };
            }
        }
    }
}

tinymce.init({
    selector: '#editor',
    height: 400,
    menubar: false,
    plugins: 'lists link image code table preview',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code preview',
    branding: false,
    promotion: false,
});
</script>

@endsection

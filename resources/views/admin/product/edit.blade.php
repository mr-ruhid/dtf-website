@extends('admin.app')

@section('title', 'Edit Product')

@php
    $dbTiers = $product->prices->map(function($p) {
        return [
            'min_qty' => $p->min_qty,
            'max_qty' => $p->max_qty,
            'price' => $p->price,
        ];
    })->values();

    $dbAttributeValues = $product->attributeValues->map(function($pav) {
        return [
            'attribute_id' => $pav->attribute_id,
            'value_id' => $pav->attribute_value_id,
            'price_override' => $pav->price_override,
            'product_image_id' => $pav->product_image_id,
        ];
    })->values();

    $defaultPrices = [];
    foreach ($attributes as $a) {
        foreach ($a->activeValues as $v) {
            $defaultPrices[$v->id] = (float) $v->price_adjustment;
        }
    }

    $dbOptions = $product->options->map(function($opt) {
        return [
            'id' => $opt->id,
            'name' => $opt->name,
            'type' => $opt->type,
            'price_addon' => $opt->price_addon,
            'measurement_unit' => $opt->measurement_unit ?? 'inch',
            'is_required' => (bool) $opt->is_required,
            'sort_order' => $opt->sort_order,
            'status' => (bool) $opt->status,
            'values' => $opt->values->map(function($v) {
                return [
                    'id' => $v->id,
                    'value' => $v->value,
                    'price_addon' => $v->price_addon,
                    'sort_order' => $v->sort_order,
                    'status' => (bool) $v->status,
                ];
            })->values(),
            'measurements' => $opt->measurements->map(function($m) {
                return [
                    'id' => $m->id,
                    'width_value' => (float) $m->width_value,
                    'height_value' => (float) $m->height_value,
                    'price' => (float) $m->price,
                    'is_default' => (bool) $m->is_default,
                    'sort_order' => $m->sort_order,
                    'status' => (bool) $m->status,
                ];
            })->values(),
        ];
    })->values();

    $productImagesList = $product->images->map(function($img) {
        return [
            'id' => $img->id,
            'url' => $img->url,
            'sort_order' => $img->sort_order,
        ];
    })->values();
@endphp

@section('content')

<div x-data="productForm()">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to products
            </a>
            <h2 class="text-xl font-semibold text-gray-800 mt-2">Edit Product</h2>
            <p class="text-xs text-gray-400 font-mono mt-1">{{ $product->slug }}</p>
        </div>
        <a href="{{ url('product/' . $product->slug) }}" target="_blank" class="text-sm text-indigo-600 hover:underline">
            <i class="fa-solid fa-eye text-xs mr-1"></i> Preview
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="tiers_submitted" value="1">
        <input type="hidden" name="attributes_submitted" value="1">
        <input type="hidden" name="options_submitted" value="1">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-info-circle text-indigo-500"></i> Basic Information
                    </h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" x-model="name" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                            <input type="text" name="slug" x-model="slug"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku', $product->sku) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Model</label>
                            <select name="model_id" x-model="modelId"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select model</option>
                                @foreach ($models as $model)
                                    <option value="{{ $model->id }}">{{ $model->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select name="category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Select category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="short_description" rows="2" maxlength="500"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Description</label>
                        <textarea name="description" id="editor" rows="10"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-images text-indigo-500"></i> Product Images
                    </h3>

                    @if ($product->images->count())
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach ($product->images as $image)
                                <div class="group relative rounded-lg overflow-hidden border border-gray-200 aspect-square">
                                    <img src="{{ $image->url }}" class="w-full h-full object-cover">
                                    <button type="submit" form="delete-image-{{ $image->id }}"
                                            onclick="return confirm('Delete this image?')"
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition w-7 h-7 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                    <span class="absolute bottom-2 left-2 px-2 py-0.5 rounded text-[10px] bg-gray-900/80 text-white font-mono">#{{ $image->id }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <input type="file" name="images[]" multiple accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500">Add more images · JPG, PNG, WEBP · Max 5MB each</p>
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
                                    <input type="number" :name="`tiers[${index}][max_qty]`" x-model="tier.max_qty" min="1" placeholder="Max (+)"
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
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-indigo-500"></i> Product Options
                            </h3>
                            <p class="text-xs text-gray-500 mt-1">Select dropdowns, text inputs, or measurements — with optional price add-ons</p>
                        </div>
                        <button type="button" @click="addOption"
                                class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Option
                        </button>
                    </div>

                    <div x-show="options.length === 0" class="bg-gray-50 rounded-lg p-6 text-center">
                        <i class="fa-solid fa-cube text-2xl text-gray-300 mb-2"></i>
                        <p class="text-xs text-gray-500">No options yet</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">Examples: Length, Width, Custom Size, Finish</p>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(option, optIndex) in options" :key="optIndex">
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex items-center gap-3">
                                    <span class="w-6 h-6 rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center" x-text="optIndex + 1"></span>
                                    <span class="text-sm font-medium text-gray-700 flex-1" x-text="option.name || 'Untitled Option'"></span>

                                    <label class="flex items-center gap-1.5 text-[11px] text-gray-600 cursor-pointer">
                                        <input type="hidden" :name="`options[${optIndex}][status]`" value="0">
                                        <input type="checkbox" :name="`options[${optIndex}][status]`" value="1" x-model="option.status"
                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                        Active
                                    </label>

                                    <button type="button" @click="removeOption(optIndex)"
                                            class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition" title="Remove option">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="p-4 space-y-4">
                                    <input type="hidden" :name="`options[${optIndex}][id]`" :value="option.id || ''">

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-medium text-gray-600 mb-1">Option Name <span class="text-red-500">*</span></label>
                                            <input type="text" :name="`options[${optIndex}][name]`" x-model="option.name" maxlength="150"
                                                   placeholder="e.g. Length"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-medium text-gray-600 mb-1">Type</label>
                                            <select :name="`options[${optIndex}][type]`" x-model="option.type"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                <option value="select">Dropdown (Select)</option>
                                                <option value="text">Text Input</option>
                                                <option value="number">Number Input</option>
                                                <option value="measurement">Measurement (W × H Matrix)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-[11px] font-medium text-gray-600 mb-1">Base Price Add-on ($)</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-xs">$</span>
                                                <input type="number" :name="`options[${optIndex}][price_addon]`" x-model="option.price_addon" step="0.01" min="0"
                                                       class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-medium text-gray-600 mb-1">Sort Order</label>
                                            <input type="number" :name="`options[${optIndex}][sort_order]`" x-model="option.sort_order" min="0"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>

                                        <div class="flex items-end pb-1">
                                            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                                                <input type="hidden" :name="`options[${optIndex}][is_required]`" value="0">
                                                <input type="checkbox" :name="`options[${optIndex}][is_required]`" value="1" x-model="option.is_required"
                                                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                Required
                                            </label>
                                        </div>
                                    </div>

                                    {{-- ===================== MEASUREMENT MATRIX ===================== --}}
                                    <div x-show="option.type === 'measurement'" x-cloak class="border border-purple-200 bg-purple-50/40 rounded-lg p-4 space-y-4">
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-purple-500 text-white flex items-center justify-center">
                                                    <i class="fa-solid fa-table-cells text-xs"></i>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-purple-900">Size Matrix</h4>
                                                    <p class="text-[11px] text-purple-700">Each (W × H) combination has its own price.</p>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <select :name="`options[${optIndex}][measurement_unit]`" x-model="option.measurement_unit"
                                                        class="text-xs border border-purple-200 rounded-lg px-2 py-1.5 bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                                                    <option value="inch">Inch (in)</option>
                                                    <option value="feet">Feet (ft)</option>
                                                    <option value="cm">Centimeter (cm)</option>
                                                </select>

                                                <button type="button" @click="addMeasurement(optIndex)"
                                                        class="text-[11px] bg-purple-600 hover:bg-purple-700 text-white font-medium px-3 py-1.5 rounded-lg transition">
                                                    <i class="fa-solid fa-plus text-[10px] mr-1"></i> Add Size
                                                </button>

                                                <button type="button" @click="quickFillMeasurements(optIndex)"
                                                        class="text-[11px] bg-white hover:bg-purple-50 border border-purple-300 text-purple-700 font-medium px-3 py-1.5 rounded-lg transition"
                                                        title="Add a quick grid of common sizes">
                                                    <i class="fa-solid fa-wand-magic-sparkles text-[10px] mr-1"></i> Quick Grid
                                                </button>
                                            </div>
                                        </div>

                                        <div class="overflow-x-auto bg-white rounded-lg border border-purple-100">
                                            <table class="w-full text-sm">
                                                <thead class="bg-purple-50/50 text-purple-900">
                                                    <tr>
                                                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider w-10">#</th>
                                                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider">Width</th>
                                                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider">Height</th>
                                                        <th class="px-3 py-2 text-left text-[10px] font-semibold uppercase tracking-wider">Price ($)</th>
                                                        <th class="px-3 py-2 text-center text-[10px] font-semibold uppercase tracking-wider w-20">Default</th>
                                                        <th class="px-3 py-2 text-center text-[10px] font-semibold uppercase tracking-wider w-16">On</th>
                                                        <th class="px-3 py-2 text-center text-[10px] font-semibold uppercase tracking-wider w-16"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(row, mIndex) in option.measurements" :key="mIndex">
                                                        <tr class="border-t border-purple-100 hover:bg-purple-50/30">
                                                            <td class="px-3 py-2 text-[11px] font-mono text-purple-400" x-text="mIndex + 1"></td>

                                                            <td class="px-3 py-2">
                                                                <input type="hidden" :name="`options[${optIndex}][measurements][${mIndex}][id]`" :value="row.id || ''">
                                                                <input type="number" step="0.01" min="0.01"
                                                                       :name="`options[${optIndex}][measurements][${mIndex}][width_value]`"
                                                                       x-model="row.width_value"
                                                                       placeholder="0.00"
                                                                       class="w-24 px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-purple-500">
                                                            </td>

                                                            <td class="px-3 py-2">
                                                                <input type="number" step="0.01" min="0.01"
                                                                       :name="`options[${optIndex}][measurements][${mIndex}][height_value]`"
                                                                       x-model="row.height_value"
                                                                       placeholder="0.00"
                                                                       class="w-24 px-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-purple-500">
                                                            </td>

                                                            <td class="px-3 py-2">
                                                                <div class="relative w-32">
                                                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 text-xs">$</span>
                                                                    <input type="number" step="0.01" min="0"
                                                                           :name="`options[${optIndex}][measurements][${mIndex}][price]`"
                                                                           x-model="row.price"
                                                                           placeholder="0.00"
                                                                           class="w-full pl-6 pr-2 py-1.5 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-purple-500">
                                                                </div>
                                                            </td>

                                                            <td class="px-3 py-2 text-center">
                                                                <input type="radio"
                                                                       :name="`options[${optIndex}][default_measurement]`"
                                                                       :value="mIndex"
                                                                       :checked="row.is_default"
                                                                       @change="setDefaultMeasurement(optIndex, mIndex)"
                                                                       class="text-purple-600 focus:ring-purple-500">
                                                                <input type="hidden"
                                                                       :name="`options[${optIndex}][measurements][${mIndex}][is_default]`"
                                                                       :value="row.is_default ? 1 : 0">
                                                            </td>

                                                            <td class="px-3 py-2 text-center">
                                                                <input type="hidden" :name="`options[${optIndex}][measurements][${mIndex}][status]`" value="0">
                                                                <input type="checkbox" value="1"
                                                                       :name="`options[${optIndex}][measurements][${mIndex}][status]`"
                                                                       x-model="row.status"
                                                                       class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                                            </td>

                                                            <td class="px-3 py-2 text-center">
                                                                <button type="button" @click="option.measurements.splice(mIndex, 1)"
                                                                        class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                                                    <i class="fa-solid fa-xmark text-xs"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>

                                                    <tr x-show="option.measurements.length === 0">
                                                        <td colspan="7" class="px-3 py-8 text-center">
                                                            <i class="fa-solid fa-table-cells-large text-2xl text-purple-200 mb-2 block"></i>
                                                            <p class="text-[12px] text-gray-500">No sizes yet</p>
                                                            <p class="text-[11px] text-gray-400 mt-0.5">Click "Add Size" or "Quick Grid"</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="text-[11px] text-purple-800 bg-purple-100/60 border border-purple-200 rounded px-3 py-2 flex items-start gap-2">
                                            <i class="fa-solid fa-circle-info mt-0.5"></i>
                                            <span>Customer must select one size from the matrix. The chosen price is added to the product base price.</span>
                                        </div>
                                    </div>

                                    {{-- ===================== DROPDOWN VALUES ===================== --}}
                                    <div x-show="option.type === 'select'" x-cloak class="border-t border-gray-100 pt-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="text-[11px] font-medium text-gray-600">Dropdown Values</label>
                                            <button type="button" @click="addOptionValue(optIndex)"
                                                    class="text-[10px] bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-2 py-1 rounded transition">
                                                <i class="fa-solid fa-plus text-[9px] mr-0.5"></i> Add Value
                                            </button>
                                        </div>

                                        <div class="space-y-2">
                                            <template x-for="(val, valIndex) in option.values" :key="valIndex">
                                                <div class="flex items-center gap-2">
                                                    <input type="hidden" :name="`options[${optIndex}][values][${valIndex}][id]`" :value="val.id || ''">

                                                    <input type="text" :name="`options[${optIndex}][values][${valIndex}][value]`" x-model="val.value" maxlength="100"
                                                           placeholder="e.g. 12 in"
                                                           class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                                                    <div class="relative w-28">
                                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-500 text-[10px]">+$</span>
                                                        <input type="number" :name="`options[${optIndex}][values][${valIndex}][price_addon]`" x-model="val.price_addon" step="0.01" min="0"
                                                               class="w-full pl-8 pr-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                                    </div>

                                                    <input type="number" :name="`options[${optIndex}][values][${valIndex}][sort_order]`" x-model="val.sort_order" min="0"
                                                           class="w-16 px-2 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                                           placeholder="Sort">

                                                    <label class="flex items-center gap-1 text-[10px] text-gray-600 cursor-pointer">
                                                        <input type="hidden" :name="`options[${optIndex}][values][${valIndex}][status]`" value="0">
                                                        <input type="checkbox" :name="`options[${optIndex}][values][${valIndex}][status]`" value="1" x-model="val.status"
                                                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                                        On
                                                    </label>

                                                    <button type="button" @click="option.values.splice(valIndex, 1)"
                                                            class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-500 transition">
                                                        <i class="fa-solid fa-xmark text-xs"></i>
                                                    </button>
                                                </div>
                                            </template>

                                            <div x-show="option.values.length === 0" class="text-[11px] text-gray-400 py-2 text-center bg-gray-50 rounded">
                                                No values yet — click "Add Value"
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-tags text-indigo-500"></i> Attributes
                    </h3>
                    <p class="text-xs text-gray-500">Select values for this product. Prices come from attribute defaults unless customized. Rəng üçün şəkil product galereyasından seçilir.</p>

                    @foreach ($attributes as $attribute)
                        @php
                            $selectedIds = $product->attributeValues->where('attribute_id', $attribute->id)->pluck('attribute_value_id')->toArray();
                        @endphp
                        <div class="border border-gray-200 rounded-lg overflow-hidden" x-data="{ open: {{ count($selectedIds) ? 'true' : 'false' }} }">
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
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach ($attribute->activeValues as $value)
                                            <div class="border rounded-lg p-3 transition"
                                                 :class="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}'] ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200'">
                                                <label class="cursor-pointer flex items-center gap-3 mb-2">
                                                    <input type="checkbox"
                                                           @change="toggleAttributeValue({{ $attribute->id }}, {{ $value->id }}, @js($value->value), {{ $value->price_adjustment }})"
                                                           :checked="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']"
                                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">

                                                    <div class="w-8 h-8 rounded-md border border-gray-200 shrink-0" style="background-color: {{ $value->color_code }}"></div>
                                                    <span class="text-sm font-medium text-gray-800 truncate flex-1">{{ $value->value }}</span>
                                                </label>

                                                <template x-if="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">
                                                    <div class="space-y-2 pt-2 border-t border-indigo-100">
                                                        <div x-show="!selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].editing" class="flex items-center justify-between gap-1">
                                                            <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded"
                                                                  :class="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override !== null ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-50 text-indigo-600'">
                                                                +$<span x-text="formatPrice(selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override !== null ? selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override : {{ $value->price_adjustment }})"></span>
                                                            </span>
                                                            <button type="button" @click.prevent="startEditPrice({{ $attribute->id }}, {{ $value->id }})"
                                                                    class="w-6 h-6 flex items-center justify-center rounded hover:bg-white text-gray-500 transition" title="Customize price">
                                                                <i class="fa-solid fa-pen text-[9px]"></i>
                                                            </button>
                                                        </div>

                                                        <div x-show="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].editing" class="space-y-1">
                                                            <div class="relative">
                                                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 text-[10px]">$</span>
                                                                <input type="number" step="0.01" min="0"
                                                                       x-model="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].temp_override"
                                                                       class="w-full pl-6 pr-2 py-1 border border-indigo-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                                       @keydown.enter.prevent="savePrice({{ $attribute->id }}, {{ $value->id }})">
                                                            </div>
                                                            <div class="flex items-center gap-1">
                                                                <button type="button" @click.prevent="savePrice({{ $attribute->id }}, {{ $value->id }})"
                                                                        class="flex-1 text-[10px] bg-indigo-600 text-white py-1 rounded hover:bg-indigo-700 transition">
                                                                    <i class="fa-solid fa-check"></i>
                                                                </button>
                                                                <button type="button" @click.prevent="cancelEditPrice({{ $attribute->id }}, {{ $value->id }})"
                                                                        class="flex-1 text-[10px] bg-gray-100 text-gray-700 py-1 rounded hover:bg-gray-200 transition">
                                                                    <i class="fa-solid fa-xmark"></i>
                                                                </button>
                                                                <button type="button" @click.prevent="resetPrice({{ $attribute->id }}, {{ $value->id }})"
                                                                        class="text-[10px] text-red-600 hover:bg-red-50 px-1.5 py-1 rounded transition">
                                                                    <i class="fa-solid fa-rotate-left"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <label class="block text-[10px] text-gray-500 mb-1">Image (from gallery)</label>
                                                            <template x-if="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].product_image_id">
                                                                <img :src="getImageUrl(selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].product_image_id)"
                                                                     class="mb-1.5 h-16 w-full object-cover rounded border border-gray-200">
                                                            </template>
                                                            <select :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][product_image_id]`"
                                                                    x-model="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].product_image_id"
                                                                    class="w-full text-[11px] border border-gray-300 rounded px-2 py-1.5 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                                                <option value="">— No image —</option>
                                                                @foreach($productImagesList as $pImg)
                                                                    <option value="{{ $pImg['id'] }}">Image #{{ $pImg['id'] }} (sort: {{ $pImg['sort_order'] }})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <input type="hidden"
                                                               :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][value_id]`"
                                                               :value="{{ $value->id }}">
                                                        <input type="hidden"
                                                               :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][price_override]`"
                                                               :value="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override ?? ''">
                                                    </div>
                                                </template>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="space-y-2">
                                        @foreach ($attribute->activeValues as $value)
                                            <div class="rounded-lg border transition"
                                                 :class="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}'] ? 'border-indigo-500 bg-indigo-50/50' : 'border-gray-200'">
                                                <label class="flex items-center gap-3 p-3 cursor-pointer">
                                                    <input type="checkbox"
                                                           @change="toggleAttributeValue({{ $attribute->id }}, {{ $value->id }}, @js($value->value), {{ $value->price_adjustment }})"
                                                           :checked="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']"
                                                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="flex-1 text-sm text-gray-800">{{ $value->value }}</span>

                                                    <template x-if="!selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">
                                                        <span class="text-[10px] text-gray-400 font-medium">default +${{ number_format($value->price_adjustment, 2) }}</span>
                                                    </template>
                                                </label>

                                                <template x-if="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">
                                                    <div class="px-3 pb-3 flex items-center justify-between gap-2 border-t border-indigo-100 pt-2">
                                                        <div x-show="!selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].editing" class="flex items-center gap-2">
                                                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded"
                                                                  :class="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override !== null ? 'bg-emerald-100 text-emerald-700' : 'bg-indigo-50 text-indigo-600'">
                                                                +$<span x-text="formatPrice(selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override !== null ? selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override : {{ $value->price_adjustment }})"></span>
                                                            </span>
                                                            <button type="button" @click.prevent="startEditPrice({{ $attribute->id }}, {{ $value->id }})"
                                                                    class="w-6 h-6 flex items-center justify-center rounded hover:bg-white text-gray-500 transition" title="Customize price">
                                                                <i class="fa-solid fa-pen text-[9px]"></i>
                                                            </button>
                                                        </div>

                                                        <div x-show="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].editing" class="flex items-center gap-1 flex-1">
                                                            <div class="relative flex-1">
                                                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-500 text-[10px]">$</span>
                                                                <input type="number" step="0.01" min="0"
                                                                       x-model="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].temp_override"
                                                                       class="w-full pl-6 pr-2 py-1 border border-indigo-300 rounded text-xs focus:outline-none focus:ring-1 focus:ring-indigo-500"
                                                                       @keydown.enter.prevent="savePrice({{ $attribute->id }}, {{ $value->id }})">
                                                            </div>
                                                            <button type="button" @click.prevent="savePrice({{ $attribute->id }}, {{ $value->id }})"
                                                                    class="w-7 h-7 flex items-center justify-center bg-indigo-600 text-white rounded hover:bg-indigo-700 text-[10px]">
                                                                <i class="fa-solid fa-check"></i>
                                                            </button>
                                                            <button type="button" @click.prevent="cancelEditPrice({{ $attribute->id }}, {{ $value->id }})"
                                                                    class="w-7 h-7 flex items-center justify-center bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-[10px]">
                                                                <i class="fa-solid fa-xmark"></i>
                                                            </button>
                                                            <button type="button" @click.prevent="resetPrice({{ $attribute->id }}, {{ $value->id }})"
                                                                    class="w-7 h-7 flex items-center justify-center text-red-600 rounded hover:bg-red-50 text-[10px]">
                                                                <i class="fa-solid fa-rotate-left"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </template>

                                                <template x-if="selectedAttributeValues['{{ $attribute->id }}']?.['{{ $value->id }}']">
                                                    <div class="px-3 pb-3">
                                                        <input type="hidden"
                                                               :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][value_id]`"
                                                               :value="{{ $value->id }}">
                                                        <input type="hidden"
                                                               :name="`attribute_values[{{ $attribute->id }}][${selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].index}][price_override]`"
                                                               :value="selectedAttributeValues['{{ $attribute->id }}']['{{ $value->id }}'].price_override ?? ''">
                                                    </div>
                                                </template>
                                            </div>
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

                    @php
                        $selectedZones = $product->printZones->pluck('id')->toArray();
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach ($printZones as $zone)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition">
                                <input type="checkbox" name="print_zones[]" value="{{ $zone->id }}"
                                       {{ in_array($zone->id, $selectedZones) ? 'checked' : '' }}
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
                            </div>
                        </div>
                        <i :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform"></i>
                    </button>

                    <div x-show="open" x-collapse class="space-y-4 pt-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" maxlength="200"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="300"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('meta_description', $product->meta_description) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $product->meta_keywords) }}" maxlength="300"
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
                                   value="{{ old('base_price', $product->base_price) }}"
                                   class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sale Price</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">$</span>
                            <input type="number" name="sale_price" step="0.01" min="0"
                                   value="{{ old('sale_price', $product->sale_price) }}"
                                   class="w-full pl-8 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" name="stock" min="0"
                               value="{{ old('stock', $product->stock) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Publish</h3>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="hidden" name="status" value="0">
                            <input type="checkbox" name="status" value="1" {{ old('status', $product->status) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Featured
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="hidden" name="has_variants" value="0">
                            <input type="checkbox" name="has_variants" value="1" {{ old('has_variants', $product->has_variants) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Has Variants (Size × Color)
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $product->sort_order) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                           class="block text-center text-sm text-gray-600 hover:text-gray-800 py-2">Cancel</a>
                    </div>
                </div>

            </div>

        </div>

    </form>

    @foreach ($product->images as $image)
        <form id="delete-image-{{ $image->id }}" method="POST"
              action="{{ route('admin.products.images.destroy', [$product, $image]) }}" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
const dbTiers = @json($dbTiers);
const dbAttributeValues = @json($dbAttributeValues);
const defaultPrices = @json($defaultPrices);
const dbOptions = @json($dbOptions);
const productImagesList = @json($productImagesList);

function productForm() {
    return {
        name: @json(old('name', $product->name)),
        slug: @json(old('slug', $product->slug)),
        modelId: @json((string) old('model_id', $product->model_id)),
        printType: @json(old('print_type', $product->print_type)),
        tiers: [],
        selectedAttributeValues: {},
        options: [],

        init() {
            const oldTiers = @json(old('tiers'));

            if (oldTiers && oldTiers.length) {
                this.tiers = oldTiers.map(t => ({min_qty: t.min_qty || '', max_qty: t.max_qty || '', price: t.price || ''}));
            } else if (dbTiers.length) {
                this.tiers = dbTiers;
            } else {
                this.tiers = [{min_qty: 1, max_qty: 11, price: ''}];
            }

            const oldAttrValues = @json(old('attribute_values'));

            if (oldAttrValues) {
                Object.keys(oldAttrValues).forEach(attrId => {
                    this.selectedAttributeValues[attrId] = {};
                    Object.values(oldAttrValues[attrId]).forEach((v, idx) => {
                        if (v.value_id) {
                            this.selectedAttributeValues[attrId][v.value_id] = {
                                index: idx,
                                value_id: v.value_id,
                                price_override: (v.price_override !== undefined && v.price_override !== '') ? parseFloat(v.price_override) : null,
                                product_image_id: (v.product_image_id !== undefined && v.product_image_id !== '') ? parseInt(v.product_image_id) : null,
                                temp_override: '',
                                editing: false,
                                default_price: defaultPrices[v.value_id] || 0
                            };
                        }
                    });
                });
            } else {
                let attrIndexMap = {};
                dbAttributeValues.forEach(pav => {
                    const attrId = pav.attribute_id;
                    if (!this.selectedAttributeValues[attrId]) {
                        this.selectedAttributeValues[attrId] = {};
                        attrIndexMap[attrId] = 0;
                    }
                    const idx = attrIndexMap[attrId]++;
                    this.selectedAttributeValues[attrId][pav.value_id] = {
                        index: idx,
                        value_id: pav.value_id,
                        price_override: pav.price_override !== null ? parseFloat(pav.price_override) : null,
                        product_image_id: pav.product_image_id || null,
                        temp_override: '',
                        editing: false,
                        default_price: defaultPrices[pav.value_id] || 0
                    };
                });
            }

            const oldOptions = @json(old('options'));

            if (oldOptions && oldOptions.length) {
                this.options = oldOptions.map(o => this.normalizeOption(o));
            } else if (dbOptions.length) {
                this.options = dbOptions.map(o => this.normalizeOption(o));
            }
        },

        normalizeOption(o) {
            return {
                id: o.id || null,
                name: o.name || '',
                type: o.type || 'select',
                price_addon: o.price_addon || 0,
                measurement_unit: o.measurement_unit || 'inch',
                is_required: o.is_required === '1' || o.is_required === 1 || o.is_required === true,
                sort_order: o.sort_order || 0,
                status: o.status !== undefined ? (o.status === '1' || o.status === 1 || o.status === true) : true,
                values: (o.values || []).map(v => ({
                    id: v.id || null,
                    value: v.value || '',
                    price_addon: v.price_addon || 0,
                    sort_order: v.sort_order || 0,
                    status: v.status !== undefined ? (v.status === '1' || v.status === 1 || v.status === true) : true,
                })),
                measurements: (o.measurements || []).map(m => ({
                    id: m.id || null,
                    width_value: m.width_value !== undefined && m.width_value !== null ? parseFloat(m.width_value) : '',
                    height_value: m.height_value !== undefined && m.height_value !== null ? parseFloat(m.height_value) : '',
                    price: m.price !== undefined && m.price !== null ? parseFloat(m.price) : '',
                    is_default: m.is_default === true || m.is_default === 1 || m.is_default === '1',
                    sort_order: m.sort_order || 0,
                    status: m.status !== undefined ? (m.status === '1' || m.status === 1 || m.status === true) : true,
                })),
            };
        },

        addTier() {
            const last = this.tiers[this.tiers.length - 1];
            let nextMin = 1;
            if (last && last.max_qty) nextMin = parseInt(last.max_qty) + 1;
            else if (last && last.min_qty) nextMin = parseInt(last.min_qty) + 11;
            this.tiers.push({min_qty: nextMin, max_qty: '', price: ''});
        },

        addOption() {
            this.options.push({
                id: null,
                name: '',
                type: 'select',
                price_addon: 0,
                measurement_unit: 'inch',
                is_required: false,
                sort_order: this.options.length,
                status: true,
                values: [],
                measurements: [],
            });
        },

        removeOption(index) {
            if (!confirm('Remove this option and all its values?')) return;
            this.options.splice(index, 1);
        },

        addOptionValue(optIndex) {
            if (!this.options[optIndex].values) this.options[optIndex].values = [];
            this.options[optIndex].values.push({
                id: null,
                value: '',
                price_addon: 0,
                sort_order: this.options[optIndex].values.length,
                status: true,
            });
        },

        addMeasurement(optIndex) {
            if (!this.options[optIndex].measurements) this.options[optIndex].measurements = [];
            const isFirst = this.options[optIndex].measurements.length === 0;
            this.options[optIndex].measurements.push({
                id: null,
                width_value: '',
                height_value: '',
                price: '',
                is_default: isFirst,
                sort_order: this.options[optIndex].measurements.length,
                status: true,
            });
        },

        quickFillMeasurements(optIndex) {
            const option = this.options[optIndex];
            if (!option) return;

            if (option.measurements.length > 0) {
                if (!confirm('This will REPLACE existing sizes with a quick grid (1×1, 1×2, 2×1, 2×2, 2×4, 4×4). Continue?')) {
                    return;
                }
            }

            const presets = [
                [1, 1], [1, 2], [2, 1], [2, 2], [2, 4], [4, 4]
            ];

            option.measurements = presets.map((p, i) => ({
                id: null,
                width_value: p[0],
                height_value: p[1],
                price: '',
                is_default: i === 0,
                sort_order: i,
                status: true,
            }));
        },

        setDefaultMeasurement(optIndex, mIndex) {
            this.options[optIndex].measurements.forEach((m, i) => {
                m.is_default = (i === mIndex);
            });
        },

        toggleAttributeValue(attrId, valueId, valueName, defaultPrice) {
            if (!this.selectedAttributeValues[attrId]) {
                this.selectedAttributeValues[attrId] = {};
            }

            if (this.selectedAttributeValues[attrId][valueId]) {
                delete this.selectedAttributeValues[attrId][valueId];
                const keys = Object.keys(this.selectedAttributeValues[attrId]);
                keys.forEach((k, i) => {
                    this.selectedAttributeValues[attrId][k].index = i;
                });
            } else {
                const index = Object.keys(this.selectedAttributeValues[attrId]).length;
                this.selectedAttributeValues[attrId][valueId] = {
                    index: index,
                    value_id: valueId,
                    value_name: valueName,
                    default_price: defaultPrice,
                    price_override: null,
                    product_image_id: null,
                    temp_override: '',
                    editing: false
                };
            }
        },

        startEditPrice(attrId, valueId) {
            const v = this.selectedAttributeValues[attrId][valueId];
            const def = v.default_price !== undefined ? v.default_price : (defaultPrices[valueId] || 0);
            v.temp_override = v.price_override !== null ? v.price_override : def;
            v.editing = true;
        },

        savePrice(attrId, valueId) {
            const v = this.selectedAttributeValues[attrId][valueId];
            if (v.temp_override !== '' && v.temp_override !== null) {
                v.price_override = parseFloat(v.temp_override);
            }
            v.editing = false;
            v.temp_override = '';
        },

        cancelEditPrice(attrId, valueId) {
            const v = this.selectedAttributeValues[attrId][valueId];
            v.editing = false;
            v.temp_override = '';
        },

        resetPrice(attrId, valueId) {
            const v = this.selectedAttributeValues[attrId][valueId];
            v.price_override = null;
            v.editing = false;
            v.temp_override = '';
        },

        getImageUrl(imageId) {
            const img = productImagesList.find(i => i.id === parseInt(imageId));
            return img ? img.url : '';
        },

        formatPrice(value) {
            return parseFloat(value || 0).toFixed(2);
        }
    }
}

tinymce.init({
    selector: '#editor',
    height: 400,
    menubar: false,
    plugins: 'lists link image code table preview',
    toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table code preview',
    branding: false,
    promotion: false,
});
</script>

@endsection

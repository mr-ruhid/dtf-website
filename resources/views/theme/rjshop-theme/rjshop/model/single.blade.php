@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('meta_keywords', $product->meta_keywords)

@push('styles')
<link rel="stylesheet" href="{{ asset('theme/rjshop-theme/css/product-single.css') }}">
@endpush

@section('content')

@php
    $images = $product->images;
    $options = $product->options->where('status', 1);
    $prices = $product->prices;
    $basePrice = (float) ($product->sale_price ?: $product->base_price);
    $pricingType = $product->pricing_type;
    $isDiscount = $pricingType === 'discount';
    $isDesignable = $product->print_type === 'custom_size';

    $unitLabels = ['inch' => 'in', 'feet' => 'ft', 'cm' => 'cm'];

    $volumeTiers = $prices->map(fn($p) => [
        'min_qty' => (int) $p->min_qty,
        'max_qty' => $p->max_qty !== null ? (int) $p->max_qty : null,
        'value' => (float) $p->price,
    ])->values();
@endphp

<section class="rj-sp-hero"
         x-data="singleProduct({
            productId: {{ $product->id }},
            slug: '{{ $product->slug }}',
            basePrice: {{ $basePrice }},
            pricingType: '{{ $pricingType }}',
            options: {{ \Illuminate\Support\Js::from($options->map(function ($o) use ($unitLabels) {
                $measurements = $o->type === 'measurement'
                    ? $o->activeMeasurements->map(fn($m) => [
                        'id' => $m->id,
                        'width' => (float) $m->width_value,
                        'height' => (float) $m->height_value,
                        'price' => (float) $m->price,
                        'width_label' => rtrim(rtrim(number_format((float) $m->width_value, 2, '.', ''), '0'), '.'),
                        'height_label' => rtrim(rtrim(number_format((float) $m->height_value, 2, '.', ''), '0'), '.'),
                        'is_default' => (bool) $m->is_default,
                    ])->values()
                    : collect();

                return [
                    'id' => $o->id,
                    'name' => $o->name,
                    'type' => $o->type,
                    'required' => (bool) $o->is_required,
                    'unit' => $unitLabels[$o->measurement_unit] ?? 'in',
                    'values' => $o->values->where('status', 1)->map(fn($v) => [
                        'id' => $v->id,
                        'value' => $v->value,
                        'price_addon' => (float) $v->price_addon,
                    ])->values(),
                    'measurements' => $measurements,
                ];
            })->values()) }}
         })">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-orb rj-sp-orb-a"></div>
    <div class="rj-sp-orb rj-sp-orb-b"></div>

    <div class="rj-sp-inner">
        <nav class="rj-sp-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            @if($product->model)
                <span>/</span>
                <a href="{{ url('model/' . $product->model->slug) }}">{{ $product->model->name }}</a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="rj-sp-layout">

            <div class="rj-sp-gallery-col">
                <div class="rj-sp-gallery" x-data="{ active: 0 }">
                    <div class="rj-sp-thumbs">
                        @forelse($images as $index => $image)
                            <button type="button"
                                    @click="active = {{ $index }}"
                                    :class="active === {{ $index }} ? 'rj-sp-thumb-active' : ''"
                                    class="rj-sp-thumb">
                                <img src="{{ $image->url }}" alt="{{ $product->name }}">
                            </button>
                        @empty
                            <div class="rj-sp-thumb rj-sp-thumb-placeholder">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endforelse
                    </div>

                    <div class="rj-sp-main-img">
                        @forelse($images as $index => $image)
                            <img src="{{ $image->url }}"
                                 alt="{{ $product->name }}"
                                 class="rj-sp-img"
                                 x-show="active === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100">
                        @empty
                            <div class="rj-sp-img-placeholder">
                                <i class="fa-regular fa-image"></i>
                                <span>No image available</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="rj-sp-info">

                @if($product->is_featured)
                    <div class="rj-sp-badge">
                        <span class="rj-sp-badge-dot"></span>
                        <span>Featured Product</span>
                    </div>
                @endif

                <h1 class="rj-sp-title">{{ $product->name }}</h1>

                <div class="rj-sp-price-row">
                    <span class="rj-sp-price">$<span x-text="finalPrice.toFixed(2)"></span></span>
                    @if($product->sale_price && $product->base_price > $product->sale_price)
                        <span class="rj-sp-price-old">${{ number_format($product->base_price, 2) }}</span>
                        <span class="rj-sp-save">Save ${{ number_format($product->base_price - $product->sale_price, 2) }}</span>
                    @endif
                </div>

                @if($product->short_description)
                    <p class="rj-sp-desc">{{ $product->short_description }}</p>
                @endif

                <div class="rj-sp-features">
                    <span class="rj-sp-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>No minimum</span>
                    </span>
                    <span class="rj-sp-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>Ready in 2 hours</span>
                    </span>
                    <span class="rj-sp-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>Ships same day</span>
                    </span>
                    <span class="rj-sp-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>Free shipping $99+</span>
                    </span>
                </div>

                @if($prices->count())
                    <div class="rj-sp-volume" x-data="{ expanded: false }">
                        <button type="button" @click="expanded = !expanded" class="rj-sp-volume-head">
                            <div class="rj-sp-volume-head-left">
                                <i class="fa-solid" :class="expanded ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                <span>// Volume Pricing</span>
                                <span class="rj-sp-volume-mode">{{ $isDiscount ? 'Discount' : 'Fixed' }}</span>
                            </div>
                            <span class="rj-sp-volume-toggle" x-text="expanded ? 'Hide' : 'View all tiers'"></span>
                        </button>

                        <div class="rj-sp-volume-chips">
                            @foreach($prices->take(3) as $tier)
                                <span class="rj-sp-volume-chip">
                                    <strong>{{ $tier->min_qty }}@if($tier->max_qty)–{{ $tier->max_qty }}@else+@endif</strong>
                                    @if($isDiscount)
                                        <span class="rj-sp-volume-discount">{{ rtrim(rtrim(number_format((float) $tier->price, 2, '.', ''), '0'), '.') }}% off</span>
                                    @else
                                        <span class="rj-sp-volume-price">${{ number_format($tier->price, 2) }}</span>
                                    @endif
                                </span>
                            @endforeach
                            @if($prices->count() > 3)
                                <span class="rj-sp-volume-more">+{{ $prices->count() - 3 }} more</span>
                            @endif
                        </div>

                        <div x-show="expanded" x-collapse x-cloak class="rj-sp-volume-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Quantity</th>
                                        <th>{{ $isDiscount ? 'Discount' : 'Unit Price' }}</th>
                                        @if($isDiscount)
                                            <th>Effective</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($prices as $tier)
                                        <tr>
                                            <td class="rj-sp-mono">
                                                {{ $tier->min_qty }}@if($tier->max_qty)–{{ $tier->max_qty }}@else+@endif pcs
                                            </td>
                                            <td class="rj-sp-mono">
                                                @if($isDiscount)
                                                    {{ rtrim(rtrim(number_format((float) $tier->price, 2, '.', ''), '0'), '.') }}%
                                                @else
                                                    ${{ number_format($tier->price, 2) }}
                                                @endif
                                            </td>
                                            @if($isDiscount)
                                                <td class="rj-sp-mono rj-sp-effective">
                                                    $<span x-text="effectiveTierPrice({{ (float) $tier->price }}).toFixed(2)"></span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($options->count())
                    <div class="rj-sp-options">
                        @foreach($options as $option)
                            <div class="rj-sp-option">

                                @if($option->type === 'measurement')
                                    @php $hasMeasurements = $option->activeMeasurements->count() > 0; @endphp

                                    <div class="rj-sp-measure-block">
                                        <label class="rj-sp-option-label rj-sp-measure-title">
                                            {{ $option->name }}
                                            @if($option->is_required)
                                                <span class="rj-sp-req">*</span>
                                            @endif
                                            <span class="rj-sp-option-unit">({{ $unitLabels[$option->measurement_unit] ?? 'in' }})</span>
                                        </label>

                                        @if($hasMeasurements)
                                            <div class="rj-sp-measure-picker">
                                                <div class="rj-sp-measure-field">
                                                    <label class="rj-sp-measure-field-label">Width</label>
                                                    <div class="rj-sp-select-wrap">
                                                        <select class="rj-sp-select"
                                                                @change="pickWidth({{ $option->id }}, $event.target.value ? parseFloat($event.target.value) : null)">
                                                            <option value="">— W —</option>
                                                            <template x-for="w in getAvailableWidths({{ $option->id }})" :key="w">
                                                                <option :value="w" x-text="w"></option>
                                                            </template>
                                                        </select>
                                                        <i class="fa-solid fa-chevron-down rj-sp-select-icon"></i>
                                                    </div>
                                                </div>

                                                <div class="rj-sp-measure-field">
                                                    <label class="rj-sp-measure-field-label">Height</label>
                                                    <div class="rj-sp-select-wrap">
                                                        <select class="rj-sp-select"
                                                                :disabled="!selectedWidths[{{ $option->id }}]"
                                                                @change="pickHeight({{ $option->id }}, $event.target.value ? findMeasurement({{ $option->id }}, $event.target.value) : null)">
                                                            <option value="">— H —</option>
                                                            <template x-for="h in getAvailableHeights({{ $option->id }})" :key="h.id">
                                                                <option :value="h.id"
                                                                        x-text="h.height + ' — $' + parseFloat(h.price).toFixed(2)"></option>
                                                            </template>
                                                        </select>
                                                        <i class="fa-solid fa-chevron-down rj-sp-select-icon"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="rj-sp-measure-missing">
                                                <i class="fa-solid fa-triangle-exclamation"></i>
                                                <span>No sizes available — please contact us</span>
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    <label class="rj-sp-option-label">
                                        {{ $option->name }}
                                        @if($option->is_required)
                                            <span class="rj-sp-req">*</span>
                                        @endif
                                    </label>

                                    @if($option->type === 'select')
                                        <div class="rj-sp-select-wrap">
                                            <select class="rj-sp-select"
                                                    @change="pickOption({{ $option->id }}, $event.target.value)">
                                                <option value="">— Select {{ $option->name }} —</option>
                                                @foreach($option->values->where('status', 1) as $value)
                                                    <option value="{{ $value->id }}">
                                                        {{ $value->value }}
                                                        @if($value->price_addon > 0)
                                                            (+${{ number_format($value->price_addon, 2) }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i class="fa-solid fa-chevron-down rj-sp-select-icon"></i>
                                        </div>
                                    @elseif($option->type === 'text')
                                        <input type="text" class="rj-sp-input"
                                               placeholder="Enter {{ $option->name }}"
                                               @input="pickOptionText({{ $option->id }}, $event.target.value)">
                                    @elseif($option->type === 'number')
                                        <input type="number" class="rj-sp-input"
                                               placeholder="0"
                                               @input="pickOptionText({{ $option->id }}, $event.target.value)">
                                    @endif
                                @endif

                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- ============ TWO PRIMARY BUTTONS ============ --}}
                @if($isDesignable)
                    <div class="rj-sp-actions-stack">

                        {{-- Build your gang sheet --}}
                        <button type="button"
                                @click="goToDesign()"
                                :disabled="!canAdd"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Build your gang sheet</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        {{-- Upload your own file --}}
                        <button type="button"
                                @click="toggleUpload()"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span x-text="uploadOpen ? 'Cancel upload' : 'Upload your own file'"></span>
                            <i class="fa-solid fa-arrow-down" :class="uploadOpen ? 'rj-sp-rot' : ''"></i>
                        </button>

                    </div>
                @else
                    <div class="rj-sp-actions">
                        <button type="button"
                                @click="addToCart()"
                                :disabled="!canAdd || adding"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-bag-shopping'"></i>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                            <span class="rj-sp-btn-price">$<span x-text="finalPrice.toFixed(2)"></span></span>
                        </button>
                    </div>
                @endif

                {{-- ============ UPLOAD PANEL ============ --}}
                @if($isDesignable)
                    <div x-show="uploadOpen" x-collapse x-cloak class="rj-sp-upload-panel-wrap">

                        <label class="rj-sp-drop"
                               :class="{ 'is-drag': dragging, 'has-file': fileName }"
                               @dragover.prevent="dragging = true"
                               @dragleave.prevent="dragging = false"
                               @drop.prevent="onDrop($event)">
                            <input type="file" accept="image/png,image/jpeg,image/webp,application/pdf" @change="onFile($event)">

                            <template x-if="!fileName">
                                <div class="rj-sp-drop-inner">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <p class="rj-sp-drop-title">Drop your print-ready file here</p>
                                    <p class="rj-sp-drop-sub">PNG · JPG · WEBP · PDF · Max 30MB</p>
                                </div>
                            </template>

                            <template x-if="fileName">
                                <div class="rj-sp-drop-inner">
                                    <i class="fa-solid fa-circle-check rj-sp-drop-ok"></i>
                                    <p class="rj-sp-drop-title" x-text="fileName"></p>
                                    <p class="rj-sp-drop-sub">Click to replace</p>
                                </div>
                            </template>
                        </label>

                        <p class="rj-sp-upload-note" x-show="!fileName">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Your file will be sent with this order. We'll print it as-is.</span>
                        </p>

                        <button type="button"
                                @click="addToCart()"
                                :disabled="!canAdd || !fileName || adding"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-bag-shopping'"></i>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                            <span class="rj-sp-btn-price">$<span x-text="finalPrice.toFixed(2)"></span></span>
                        </button>

                    </div>
                @endif

                <a href="{{ url('contact-us') }}" class="rj-sp-btn-outline">
                    <i class="fa-solid fa-comments"></i>
                    <span>Ask a question</span>
                </a>

            </div>

        </div>
    </div>
</section>

@if($product->description)
<section class="rj-sp-section">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-inner">
        <div class="rj-sp-content rj-sp-desc-content">
            {!! $product->description !!}
        </div>
    </div>
</section>
@endif

@if($relatedProducts->count())
<section class="rj-sp-section">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-inner">
        <div class="rj-sp-steps-head">
            <div class="rj-sp-eyebrow">
                <span class="rj-sp-eyebrow-line"></span>
                <span class="rj-sp-eyebrow-text">You might also like</span>
            </div>
            <h2 class="rj-sp-h2">Related products</h2>
        </div>

        <div class="rj-prod-grid">
            @foreach($relatedProducts as $rel)
                @include('theme.rjshop-theme.rjshop.partials.product-card', ['product' => $rel])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($faqs->count())
<section class="rj-sp-section">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-inner">
        <div class="rj-sp-steps-head">
            <div class="rj-sp-eyebrow">
                <span class="rj-sp-eyebrow-line"></span>
                <span class="rj-sp-eyebrow-text">Help Center</span>
            </div>
            <h2 class="rj-sp-h2">Frequently asked questions</h2>
        </div>

        <div class="rj-sp-faq-list" x-data="{ open: null }">
            @foreach($faqs as $index => $faq)
                <div class="rj-sp-faq" :class="open === {{ $index }} ? 'rj-sp-faq-open' : ''">
                    <button type="button"
                            @click="open = open === {{ $index }} ? null : {{ $index }}"
                            class="rj-sp-faq-q">
                        <span class="rj-sp-faq-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="rj-sp-faq-qtext">{{ $faq->question }}</span>
                        <span class="rj-sp-faq-icon">
                            <i class="fa-solid fa-plus" :class="open === {{ $index }} ? 'rj-sp-faq-icon-rot' : ''"></i>
                        </span>
                    </button>

                    <div x-show="open === {{ $index }}" x-collapse x-cloak>
                        <div class="rj-sp-faq-a">
                            {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li>'))) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rj-sp-faq-more">
            <a href="{{ url('faq') }}" class="rj-sp-btn rj-sp-btn-outline">
                <span>View all FAQs</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

<style>
    .rj-sp-actions-stack {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rj-sp-actions-stack .rj-sp-btn {
        width: 100%;
        box-sizing: border-box;
    }

    .rj-sp-actions-stack .fa-arrow-down {
        font-size: 11px;
        transition: transform 0.3s;
    }

    .rj-sp-rot {
        transform: rotate(180deg);
    }

    .rj-sp-upload-panel-wrap {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
        padding: 0.25rem 0 0;
    }

    .rj-sp-drop {
        display: block;
        padding: 1.75rem 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1.5px dashed rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        cursor: pointer;
        text-align: center;
        transition: all 0.25s;
    }

    .rj-sp-drop:hover,
    .rj-sp-drop.is-drag {
        border-color: #6366f1;
        background: rgba(99, 102, 241, 0.06);
    }

    .rj-sp-drop.has-file {
        border-style: solid;
        border-color: rgba(52, 211, 153, 0.5);
        background: rgba(52, 211, 153, 0.05);
    }

    .rj-sp-drop input {
        display: none;
    }

    .rj-sp-drop-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .rj-sp-drop-inner > i {
        font-size: 26px;
        color: #6b7280;
    }

    .rj-sp-drop-ok {
        color: #34d399 !important;
    }

    .rj-sp-drop-title {
        font-size: 13px;
        color: #e5e7eb;
        margin: 0;
        font-weight: 600;
    }

    .rj-sp-drop-sub {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        font-family: ui-monospace, monospace;
    }

    .rj-sp-upload-note {
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
        font-size: 12px;
        color: #9ca3af;
        margin: 0;
        line-height: 1.5;
    }

    .rj-sp-upload-note i {
        color: #818cf8;
        font-size: 11px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .rj-sp-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        padding: 0.875rem 1.5rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 9999px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.25s;
        align-self: flex-start;
    }

    .rj-sp-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }
</style>

<script>
function singleProduct(config) {
    return {
        productId: config.productId,
        slug: config.slug,
        basePrice: parseFloat(config.basePrice) || 0,
        pricingType: config.pricingType || 'fixed',
        options: config.options || [],

        selections: {},
        selectedWidths: {},
        selectedHeights: {},
        adding: false,

        uploadOpen: false,
        dragging: false,
        fileName: '',
        fileData: null,

        finalPrice: 0,
        canAdd: false,

        init() {
            this.options.forEach(opt => {
                if (opt.type === 'measurement' && opt.measurements && opt.measurements.length) {
                    const def = opt.measurements.find(m => m.is_default);
                    if (def) {
                        this.selectedWidths[opt.id] = def.width;
                        this.selectedHeights[opt.id] = def;
                        this.updateSelection(opt);
                    }
                }
            });
            this.recalc();
        },

        recalc() {
            let measurementPrice = 0;
            let hasMeasurement = false;
            let otherAddon = 0;

            this.options.forEach(opt => {
                const sel = this.selections[opt.id];
                if (!sel) return;

                if (opt.type === 'measurement' && sel.measurement_id) {
                    measurementPrice += parseFloat(sel.price_addon || 0);
                    hasMeasurement = true;
                } else if (opt.type === 'select' && sel.value_id) {
                    const v = opt.values.find(x => x.id === sel.value_id);
                    if (v) otherAddon += parseFloat(v.price_addon || 0);
                }
            });

            const base = hasMeasurement ? measurementPrice : this.basePrice;

            this.finalPrice = Math.round((base + otherAddon) * 100) / 100;
            this.canAdd = this.computeCanAdd();
        },

        computeCanAdd() {
            for (const opt of this.options) {
                if (opt.required && !this.selections[opt.id]) return false;
            }
            return true;
        },

        getAvailableWidths(optionId) {
            const opt = this.options.find(o => o.id === optionId);
            if (!opt || !opt.measurements) return [];

            const widths = new Set();
            opt.measurements.forEach(m => widths.add(m.width));

            return Array.from(widths).sort((a, b) => a - b);
        },

        getAvailableHeights(optionId) {
            const opt = this.options.find(o => o.id === optionId);
            if (!opt || !opt.measurements) return [];

            const selectedW = this.selectedWidths[optionId];
            if (selectedW === undefined || selectedW === null) return [];

            return opt.measurements
                .filter(m => m.width === selectedW)
                .sort((a, b) => a.height - b.height);
        },

        findMeasurement(optionId, measurementId) {
            const opt = this.options.find(o => o.id === optionId);
            if (!opt || !opt.measurements) return null;

            const id = parseInt(measurementId);
            return opt.measurements.find(m => m.id === id) || null;
        },

        pickWidth(optionId, width) {
            if (!width) {
                this.selectedWidths[optionId] = null;
                this.selectedHeights[optionId] = null;
                delete this.selections[optionId];
                this.recalc();
                return;
            }

            if (this.selectedWidths[optionId] === width) {
                return;
            }

            this.selectedWidths[optionId] = width;
            this.selectedHeights[optionId] = null;

            delete this.selections[optionId];
            this.recalc();
        },

        pickHeight(optionId, h) {
            const opt = this.options.find(o => o.id === optionId);
            if (!opt) return;

            if (!h) {
                this.selectedHeights[optionId] = null;
                delete this.selections[optionId];
                this.recalc();
                return;
            }

            this.selectedHeights[optionId] = h;
            this.updateSelection(opt);
            this.recalc();
        },

        updateSelection(opt) {
            const w = this.selectedWidths[opt.id];
            const h = this.selectedHeights[opt.id];

            if (w === undefined || w === null || !h) {
                delete this.selections[opt.id];
                return;
            }

            const unit = opt.unit || 'in';
            const wLabel = h.width_label || String(w);
            const hLabel = h.height_label || String(h.height);
            const label = wLabel + ' × ' + hLabel + ' ' + unit;

            this.selections[opt.id] = {
                option_id: opt.id,
                option_name: opt.name,
                measurement_id: h.id,
                label: label,
                value: label,
                price_addon: parseFloat(h.price || 0),
                type: 'measurement',
                width: parseFloat(w),
                height: parseFloat(h.height),
            };
        },

        effectiveTierPrice(percent) {
            const base = this.finalPrice;
            return Math.round(base * (1 - (parseFloat(percent) || 0) / 100) * 100) / 100;
        },

        pickOption(optionId, valueId) {
            if (!valueId) {
                delete this.selections[optionId];
                this.recalc();
                return;
            }
            const opt = this.options.find(o => o.id === optionId);
            if (!opt) return;
            const v = opt.values.find(x => x.id == valueId);
            if (!v) return;

            this.selections[optionId] = {
                option_id: optionId,
                option_name: opt.name,
                value_id: v.id,
                value: v.value,
                price_addon: parseFloat(v.price_addon || 0),
                type: 'select',
            };
            this.recalc();
        },

        pickOptionText(optionId, value) {
            const opt = this.options.find(o => o.id === optionId);
            if (!opt) return;

            if (!value) {
                delete this.selections[optionId];
                this.recalc();
                return;
            }

            this.selections[optionId] = {
                option_id: optionId,
                option_name: opt.name,
                value_id: null,
                value: value,
                price_addon: 0,
                type: 'text',
            };
            this.recalc();
        },

        toggleUpload() {
            if (!this.canAdd) {
                this.flash('Please select all required options first');
                return;
            }

            this.uploadOpen = !this.uploadOpen;

            if (!this.uploadOpen) {
                this.fileName = '';
                this.fileData = null;
            }
        },

        onFile(e) {
            const f = e.target.files[0];
            if (!f) return;
            this.readFile(f);
        },

        onDrop(e) {
            this.dragging = false;
            const f = e.dataTransfer.files[0];
            if (!f) return;
            this.readFile(f);
        },

        readFile(f) {
            if (f.size > 30 * 1024 * 1024) {
                this.flash('File is larger than 30MB');
                return;
            }

            const allowed = /^(image\/(png|jpe?g|webp)|application\/pdf)$/i.test(f.type);

            if (!allowed) {
                this.flash('Only PNG, JPG, WEBP, PDF files are supported');
                return;
            }

            this.fileName = f.name;
            const reader = new FileReader();
            reader.onload = (ev) => { this.fileData = ev.target.result; };
            reader.readAsDataURL(f);
        },

        goToDesign() {
            if (!this.canAdd) {
                this.flash('Please select all required options');
                return;
            }

            let w = null, h = null;

            for (const id in this.selections) {
                const s = this.selections[id];
                if (s.type === 'measurement' && s.width && s.height) {
                    w = s.width;
                    h = s.height;
                    break;
                }
            }

            let url = '/design/' + this.slug;
            if (w && h) url += '?w=' + w + '&h=' + h;

            window.location.href = url;
        },

        async addToCart() {
            if (!this.canAdd || this.adding) {
                if (!this.canAdd) this.flash('Please select all required options');
                return;
            }

            if (!this.fileName || !this.fileData) {
                this.flash('Please upload a file first');
                return;
            }

            this.adding = true;

            const attributes = {};
            const optionsPayload = [];
            let w = null, h = null;

            Object.values(this.selections).forEach(s => {
                attributes[s.option_name] = s.value;
                optionsPayload.push({
                    option_name: s.option_name,
                    option_value: s.value,
                    price_addon: s.price_addon,
                });
                if (s.type === 'measurement' && s.width && s.height) {
                    w = s.width;
                    h = s.height;
                }
            });

            attributes['Design'] = 'Uploaded file';

            const payload = {
                product_id: this.productId,
                unit_price: this.finalPrice,
                qty: 1,
                attributes: attributes,
                options: optionsPayload,
                print_type: 'custom_size',
                width_inch: w,
                height_inch: h,
                composite_image: this.fileData || null,
                file_name: this.fileName,
                note: 'Uploaded artwork: ' + this.fileName,
            };

            try {
                const res = await Alpine.store('cart').add(payload);

                if (res && res.success) {
                    this.fileName = '';
                    this.fileData = null;
                    this.uploadOpen = false;
                    this.flash('Added to cart ✓');
                } else {
                    this.flash('Could not add to cart');
                }
            } catch (e) {
                this.flash('Could not add to cart');
            }

            this.adding = false;
        },

        flash(msg) {
            const el = document.createElement('div');
            el.textContent = msg;
            el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#0a0715;color:#fff;padding:12px 24px;border-radius:9999px;border:1px solid rgba(99,102,241,0.4);font-size:13px;font-weight:600;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.6);';
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2200);
        }
    }
}
</script>

@endsection
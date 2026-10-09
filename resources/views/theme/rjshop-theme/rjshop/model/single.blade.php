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
    $basePrice = $product->sale_price ?: $product->base_price;
    $pricingType = $product->pricing_type;
    $isDiscount = $pricingType === 'discount';
    $breadcrumbParent = $product->model;
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
            basePrice: {{ (float) $basePrice }},
            pricingType: '{{ $pricingType }}',
            volumeTiers: {{ \Illuminate\Support\Js::from($volumeTiers) }},
            options: {{ \Illuminate\Support\Js::from($options->map(function ($o) use ($unitLabels) {
                $measurements = $o->type === 'measurement'
                    ? $o->activeMeasurements->map(fn($m) => [
                        'id' => $m->id,
                        'width' => (float) $m->width_value,
                        'height' => (float) $m->height_value,
                        'price' => (float) $m->price,
                        'label' => rtrim(rtrim(number_format((float) $m->width_value, 2, '.', ''), '0'), '.')
                            . ' × '
                            . rtrim(rtrim(number_format((float) $m->height_value, 2, '.', ''), '0'), '.'),
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
            @if($breadcrumbParent)
                <span>/</span>
                <a href="{{ url($breadcrumbParent->slug) }}">{{ $breadcrumbParent->name }}</a>
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
                    <template x-if="volumePercent > 0">
                        <span class="rj-sp-save" x-text="'-' + volumePercent + '%'"></span>
                    </template>
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
                                <span class="rj-sp-volume-mode" x-text="'{{ $isDiscount ? 'Discount' : 'Fixed' }}'"></span>
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
                                <label class="rj-sp-option-label">
                                    {{ $option->name }}
                                    @if($option->is_required)
                                        <span class="rj-sp-req">*</span>
                                    @endif
                                    @if($option->type === 'measurement')
                                        <span class="rj-sp-option-unit">({{ $unitLabels[$option->measurement_unit] ?? 'in' }})</span>
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
                                @elseif($option->type === 'measurement')
                                    @php $hasMeasurements = $option->activeMeasurements->count() > 0; @endphp

                                    @if($hasMeasurements)
                                        <div class="rj-sp-select-wrap">
                                            <select class="rj-sp-select"
                                                    @change="pickMeasurement({{ $option->id }}, $event.target.value)">
                                                <option value="">— Select {{ $option->name }} —</option>
                                                @foreach($option->activeMeasurements as $m)
                                                    @php
                                                        $w = rtrim(rtrim(number_format((float) $m->width_value, 2, '.', ''), '0'), '.');
                                                        $h = rtrim(rtrim(number_format((float) $m->height_value, 2, '.', ''), '0'), '.');
                                                        $u = $unitLabels[$option->measurement_unit] ?? 'in';
                                                    @endphp
                                                    <option value="{{ $m->id }}">
                                                        {{ $w }} × {{ $h }} {{ $u }}
                                                        @if($m->price > 0)
                                                            (+${{ number_format($m->price, 2) }})
                                                        @endif
                                                        @if($m->is_default)
                                                            · Default
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i class="fa-solid fa-chevron-down rj-sp-select-icon"></i>
                                        </div>
                                    @else
                                        <div class="rj-sp-measure-missing">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>No sizes available — please contact us</span>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($isDesignable)
                    <div class="rj-sp-actions">
                        <button type="button"
                                @click="goToDesign()"
                                :disabled="!canAdd"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Build your gang sheet</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                @endif

                <div class="rj-sp-qty-row">
                    <label class="rj-sp-option-label">Quantity</label>
                    <div class="rj-sp-qty">
                        <button type="button" @click="decQty()" class="rj-sp-qty-btn">−</button>
                        <input type="number" x-model.number="qty" min="1" class="rj-sp-qty-input">
                        <button type="button" @click="incQty()" class="rj-sp-qty-btn">+</button>
                    </div>
                </div>

                @if(!$isDesignable)
                    <div class="rj-sp-actions">
                        <button type="button"
                                @click="addToCart()"
                                :disabled="!canAdd || adding"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-bag-shopping'"></i>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                            <span class="rj-sp-btn-price">$<span x-text="totalPrice.toFixed(2)"></span></span>
                        </button>
                    </div>
                @endif

                <a href="{{ url('contact-us') }}" class="rj-sp-btn rj-sp-btn-outline">
                    <i class="fa-solid fa-comments"></i>
                    <span>Ask a question</span>
                </a>

                <div class="rj-sp-upload-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    @if($isDesignable)
                        <span>Already have a print-ready file? <a href="{{ url('contact-us') }}">Upload it instead →</a></span>
                    @else
                        <span>Need help with this product? <a href="{{ url('contact-us') }}">Contact support →</a></span>
                    @endif
                </div>

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
                @include('theme.rjshop-theme.partials.product-card', ['product' => $rel])
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

<script>
function singleProduct(config) {
    return {
        productId: config.productId,
        slug: config.slug,
        basePrice: parseFloat(config.basePrice) || 0,
        pricingType: config.pricingType || 'fixed',
        volumeTiers: config.volumeTiers || [],
        options: config.options || [],

        selections: {},
        qty: 1,
        adding: false,

        init() {
            this.options.forEach(opt => {
                if (opt.type === 'measurement' && opt.measurements && opt.measurements.length) {
                    const def = opt.measurements.find(m => m.is_default);
                    if (def) {
                        this.selections[opt.id] = {
                            option_id: opt.id,
                            option_name: opt.name,
                            measurement_id: def.id,
                            label: def.label + ' ' + (opt.unit || 'in'),
                            value: def.label + ' ' + (opt.unit || 'in'),
                            price_addon: parseFloat(def.price || 0),
                            type: 'measurement',
                        };
                    }
                }
            });
        },

        get optionsAddon() {
            let total = 0;
            this.options.forEach(opt => {
                const sel = this.selections[opt.id];
                if (!sel) return;

                if (opt.type === 'select' && sel.value_id) {
                    const v = opt.values.find(x => x.id === sel.value_id);
                    if (v) total += parseFloat(v.price_addon || 0);
                } else if (opt.type === 'measurement' && sel.measurement_id) {
                    total += parseFloat(sel.price_addon || 0);
                }
            });
            return total;
        },

        get rawPrice() {
            return this.basePrice + this.optionsAddon;
        },

        get volumePercent() {
            if (this.pricingType !== 'discount') return 0;
            if (!this.volumeTiers.length) return 0;

            const q = this.qty || 1;
            const match = this.volumeTiers.find(t => {
                const min = Number(t.min_qty) || 0;
                const max = t.max_qty !== null && t.max_qty !== undefined ? Number(t.max_qty) : null;
                return q >= min && (max === null || q <= max);
            });

            return match ? parseFloat(match.value || 0) : 0;
        },

        get finalPrice() {
            const base = this.rawPrice;
            if (this.pricingType === 'discount') {
                const pct = this.volumePercent;
                return Math.round(base * (1 - pct / 100) * 100) / 100;
            }
            if (this.volumeTiers.length) {
                const q = this.qty || 1;
                const match = this.volumeTiers.find(t => {
                    const min = Number(t.min_qty) || 0;
                    const max = t.max_qty !== null && t.max_qty !== undefined ? Number(t.max_qty) : null;
                    return q >= min && (max === null || q <= max);
                });
                if (match) return parseFloat(match.value || 0) + this.optionsAddon;
            }
            return base;
        },

        get totalPrice() {
            return this.finalPrice * (this.qty || 1);
        },

        get canAdd() {
            for (const opt of this.options) {
                if (opt.required && !this.selections[opt.id]) return false;
            }
            return true;
        },

        effectiveTierPrice(percent) {
            const base = this.rawPrice;
            return Math.round(base * (1 - (parseFloat(percent) || 0) / 100) * 100) / 100;
        },

        pickOption(optionId, valueId) {
            if (!valueId) {
                delete this.selections[optionId];
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
        },

        pickOptionText(optionId, value) {
            const opt = this.options.find(o => o.id === optionId);
            if (!opt) return;

            if (!value) {
                delete this.selections[optionId];
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
        },

        pickMeasurement(optionId, measurementId) {
            if (!measurementId) {
                delete this.selections[optionId];
                return;
            }

            const opt = this.options.find(o => o.id === optionId);
            if (!opt) return;

            const m = (opt.measurements || []).find(x => x.id == measurementId);
            if (!m) return;

            const unit = opt.unit || 'in';
            const label = m.label + ' ' + unit;

            this.selections[optionId] = {
                option_id: optionId,
                option_name: opt.name,
                measurement_id: m.id,
                label: label,
                value: label,
                price_addon: parseFloat(m.price || 0),
                type: 'measurement',
            };
        },

        incQty() { this.qty = Math.min(999, (this.qty || 1) + 1); },
        decQty() { this.qty = Math.max(1, (this.qty || 1) - 1); },

        goToDesign() {
            if (!this.canAdd) {
                this.flash('Please select all required options');
                return;
            }
            window.location.href = '/design/' + this.slug;
        },

        async addToCart() {
            if (!this.canAdd || this.adding) {
                if (!this.canAdd) this.flash('Please select all required options');
                return;
            }

            this.adding = true;

            const attributes = {};
            const optionsPayload = [];

            Object.values(this.selections).forEach(s => {
                attributes[s.option_name] = s.value;
                optionsPayload.push({
                    option_name: s.option_name,
                    option_value: s.value,
                    price_addon: s.price_addon,
                });
            });

            const payload = {
                product_id: this.productId,
                unit_price: this.finalPrice,
                qty: this.qty,
                attributes: attributes,
                options: optionsPayload,
                print_type: '{{ $product->print_type ?: "none" }}'
            };

            try {
                await Alpine.store('cart').add(payload);
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

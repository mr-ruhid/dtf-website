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
    $mainImage = $images->first();
    $options = $product->options->where('status', 1);
    $prices = $product->prices;
    $basePrice = $product->sale_price ?: $product->base_price;

    $isDesignable = $product->print_type === 'custom_size'
        || $product->printZones->where('status', 1)->count() > 0;

    $defaultZone = $product->printZones->where('status', 1)->sortBy('sort_order')->first();
    $defaultW = $defaultZone ? (float) ($defaultZone->max_width_inch ?: 12) : 12;
    $defaultH = $defaultZone ? (float) ($defaultZone->max_height_inch ?: 12) : 12;
    $defaultZonePrice = $defaultZone ? (float) $defaultZone->price_addon : 0;

    $pricing = [
        'enabled' => (\App\Models\Setting::get('design_custom_enabled') ?? '1') == '1',
        'per_sq_inch' => (float) (\App\Models\Setting::get('design_custom_price_per_sq_inch') ?? '0.05'),
        'min_price' => (float) (\App\Models\Setting::get('design_custom_min_price') ?? '4.50'),
        'min_inch' => (float) (\App\Models\Setting::get('design_custom_min_inch') ?? '1'),
        'max_inch' => (float) (\App\Models\Setting::get('design_custom_max_inch') ?? '60'),
    ];

    $startingPrice = (float) $basePrice;

    if ($startingPrice <= 0 && $prices->count()) {
        $firstTier = $prices->sortBy('min_qty')->first();
        $startingPrice = (float) $firstTier->price;
    }

    if ($startingPrice <= 0 && $defaultZonePrice > 0) {
        $startingPrice = $defaultZonePrice;
    }

    if ($startingPrice <= 0 && $isDesignable) {
        $startingPrice = $pricing['min_price'];
    }

    if ($startingPrice <= 0) {
        $startingPrice = 4.50;
    }

    $displayOptions = $isDesignable
        ? $options->where('type', '!=', 'measurement')
        : $options;
@endphp

<section class="rj-sp-hero"
         x-data="standardProduct({
            productId: {{ $product->id }},
            slug: '{{ $product->slug }}',
            basePrice: {{ (float) $startingPrice }},
            requiresDesign: {{ $isDesignable ? 'true' : 'false' }},
            defaultW: {{ $defaultW }},
            defaultH: {{ $defaultH }},
            defaultZonePrice: {{ $defaultZonePrice }},
            pricing: {{ \Illuminate\Support\Js::from($pricing) }},
            options: {{ \Illuminate\Support\Js::from($displayOptions->map(function ($o) {
                return [
                    'id' => $o->id,
                    'name' => $o->name,
                    'type' => $o->type,
                    'required' => (bool) $o->is_required,
                    'values' => $o->values->where('status', 1)->map(fn($v) => [
                        'id' => $v->id,
                        'value' => $v->value,
                        'price_addon' => (float) $v->price_addon,
                    ])->values(),
                ];
            })->values()) }}
         })">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-orb rj-sp-orb-a"></div>
    <div class="rj-sp-orb rj-sp-orb-b"></div>

    <div class="rj-sp-inner">
        <nav class="rj-sp-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <a href="{{ url($model->slug) }}">{{ $model->name }}</a>
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

                @if($prices->count() > 1)
                    <div class="rj-sp-volume-box">
                        <div class="rj-sp-volume-head">
                            <i class="fa-solid fa-tags"></i>
                            <span>// Volume Pricing</span>
                        </div>
                        <div class="rj-sp-volume-chips">
                            @foreach($prices as $tier)
                                <span class="rj-sp-volume-chip">
                                    <strong>{{ $tier->min_qty }}@if($tier->max_qty)–{{ $tier->max_qty }}@else+@endif</strong>
                                    <span class="rj-sp-volume-price">${{ number_format($tier->price, 2) }}</span>
                                </span>
                            @endforeach
                        </div>
                        <p class="rj-sp-volume-note">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Final price calculated at checkout based on total quantity.</span>
                        </p>
                    </div>
                @endif

                @if($displayOptions->count())
                    <div class="rj-sp-options">
                        @foreach($displayOptions as $option)
                            <div class="rj-sp-option">
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
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($isDesignable && $pricing['enabled'])
                    <div class="rj-sp-qty-row">
                        <label class="rj-sp-option-label">
                            Sheet Size
                            <span class="rj-sp-req">*</span>
                        </label>
                        <div class="rj-sp-measure-2">
                            <input type="number"
                                   :min="pricing.min_inch"
                                   :max="pricing.max_inch"
                                   step="0.1"
                                   class="rj-sp-input"
                                   x-model.number="designW"
                                   placeholder="Width">
                            <span class="rj-sp-measure-sep">×</span>
                            <input type="number"
                                   :min="pricing.min_inch"
                                   :max="pricing.max_inch"
                                   step="0.1"
                                   class="rj-sp-input"
                                   x-model.number="designH"
                                   placeholder="Height">
                            <span class="rj-sp-measure-unit">in</span>
                        </div>
                        <p class="rj-sp-measure-hint" x-text="designHint"></p>
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

                <div class="rj-sp-actions">
                    @if($isDesignable)
                        <button type="button"
                                @click="goToDesign()"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Build your gang sheet</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    @else
                        <button type="button"
                                @click="addToCart()"
                                :disabled="adding"
                                class="rj-sp-btn rj-sp-btn-primary">
                            <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-bag-shopping'"></i>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                            <span class="rj-sp-btn-price">$<span x-text="totalPrice.toFixed(2)"></span></span>
                        </button>
                    @endif

                    <a href="{{ url('contact-us') }}" class="rj-sp-btn rj-sp-btn-outline">
                        <i class="fa-solid fa-comments"></i>
                        <span>Ask a question</span>
                    </a>
                </div>

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

<section class="rj-sp-section">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-inner">
        <div class="rj-sp-steps-head">
            <div class="rj-sp-eyebrow">
                <span class="rj-sp-eyebrow-line"></span>
                <span class="rj-sp-eyebrow-text">How to use it</span>
            </div>
            <h2 class="rj-sp-h2">Cut. Place. Press. Peel.</h2>
            <p class="rj-sp-sub">Four simple steps — from sheet to shirt in minutes.</p>
        </div>

        <div class="rj-sp-steps">
            @php
                $steps = [
                    ['num' => '01', 'title' => 'Cut', 'text' => 'Trim each design from the sheet. No weeding, no mess.'],
                    ['num' => '02', 'title' => 'Place', 'text' => 'Position the transfer on the garment, print-side down.'],
                    ['num' => '03', 'title' => 'Press', 'text' => '310°F / 155°C. Medium pressure. 12–15 seconds.'],
                    ['num' => '04', 'title' => 'Peel', 'text' => 'Wait 5 seconds. Peel warm. Enjoy your custom print.'],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="rj-sp-step">
                    <div class="rj-sp-step-num">{{ $step['num'] }}</div>
                    <div class="rj-sp-step-line"></div>
                    <h3 class="rj-sp-step-title">{{ $step['title'] }}</h3>
                    <p class="rj-sp-step-text">{{ $step['text'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

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

        <div class="rj-sp-related">
            @foreach($relatedProducts as $rel)
                <a href="{{ url('product/' . $rel->slug) }}" class="rj-sp-related-card">
                    <div class="rj-sp-related-img">
                        @if($rel->images->first())
                            <img src="{{ $rel->images->first()->url }}" alt="{{ $rel->name }}">
                        @else
                            <div class="rj-sp-related-placeholder">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="rj-sp-related-body">
                        <h3 class="rj-sp-related-title">{{ $rel->name }}</h3>
                        <p class="rj-sp-related-price">${{ number_format($rel->sale_price ?: $rel->base_price, 2) }}</p>
                    </div>
                </a>
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
function standardProduct(config) {
    const p = config.pricing || {};

    const pricing = {
        enabled: p.enabled !== false && p.enabled !== 0 && p.enabled !== '0',
        per_sq_inch: (p.per_sq_inch !== null && p.per_sq_inch !== undefined && p.per_sq_inch !== '') ? parseFloat(p.per_sq_inch) : 0.05,
        min_price: (p.min_price !== null && p.min_price !== undefined && p.min_price !== '') ? parseFloat(p.min_price) : 4.50,
        min_inch: (p.min_inch !== null && p.min_inch !== undefined && p.min_inch !== '') ? parseFloat(p.min_inch) : 1,
        max_inch: (p.max_inch !== null && p.max_inch !== undefined && p.max_inch !== '') ? parseFloat(p.max_inch) : 60
    };

    return {
        productId: config.productId,
        slug: config.slug,
        basePrice: parseFloat(config.basePrice) || 0,
        requiresDesign: config.requiresDesign,
        options: config.options || [],
        pricing: pricing,

        selections: {},
        qty: 1,
        adding: false,

        designW: config.defaultW || 12,
        designH: config.defaultH || 12,

        get designHint() {
            if (!this.designW || !this.designH) return '';
            const min = this.pricing.min_inch;
            const max = this.pricing.max_inch;
            if (this.designW < min || this.designH < min) return 'Minimum ' + min + ' inch';
            if (this.designW > max || this.designH > max) return 'Maximum ' + max + ' inch';
            return this.designW + ' × ' + this.designH + ' in sheet';
        },

        get sheetPrice() {
            if (!this.requiresDesign) return 0;

            const area = (this.designW || 12) * (this.designH || 12);
            const calculated = area * this.pricing.per_sq_inch;

            return Math.round(Math.max(calculated, this.pricing.min_price) * 100) / 100;
        },

        get optionsAddon() {
            let total = 0;
            this.options.forEach(opt => {
                const sel = this.selections[opt.id];
                if (!sel) return;
                if (opt.type === 'select' && sel.value_id) {
                    const v = opt.values.find(x => x.id === sel.value_id);
                    if (v) total += parseFloat(v.price_addon || 0);
                }
            });
            return total;
        },

        get finalPrice() {
            return Math.max(0, this.basePrice + this.sheetPrice + this.optionsAddon);
        },

        get totalPrice() {
            return this.finalPrice * (this.qty || 1);
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
            };
        },

        incQty() { this.qty = Math.min(999, (this.qty || 1) + 1); },
        decQty() { this.qty = Math.max(1, (this.qty || 1) - 1); },

        goToDesign() {
            const min = this.pricing.min_inch;
            const max = this.pricing.max_inch;

            if (!this.designW || !this.designH) {
                this.flash('Please enter width and height');
                return;
            }
            if (this.designW < min || this.designH < min) {
                this.flash('Minimum size is ' + min + ' inch');
                return;
            }
            if (this.designW > max || this.designH > max) {
                this.flash('Maximum size is ' + max + ' inch');
                return;
            }

            window.location.href = '/design/' + this.slug + '?w=' + this.designW + '&h=' + this.designH;
        },

        async addToCart() {
            if (this.adding) return;

            for (const opt of this.options) {
                if (opt.required && !this.selections[opt.id]) {
                    this.flash('Please select: ' + opt.name);
                    return;
                }
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

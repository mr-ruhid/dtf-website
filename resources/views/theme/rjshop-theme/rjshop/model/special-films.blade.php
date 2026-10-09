@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $model->meta_title ?: $model->name)
@section('meta_description', $model->meta_description ?: $model->description)
@section('meta_keywords', $model->meta_keywords)

@section('content')

@php
    $product = $model->products()
        ->where('status', 1)
        ->with(['images', 'options' => function ($q) {
            $q->where('status', 1);
        }, 'options.activeMeasurements'])
        ->orderBy('sort_order')
        ->first();

    $measurementOption = null;
    $measurements = collect();
    $unit = 'in';
    $unitLabels = ['inch' => 'in', 'feet' => 'ft', 'cm' => 'cm'];

    if ($product) {
        $measurementOption = $product->options->where('type', 'measurement')->first();
        $measurements = $measurementOption ? $measurementOption->activeMeasurements : collect();
        $unit = $measurementOption ? ($unitLabels[$measurementOption->measurement_unit] ?? 'in') : 'in';
    }

    $widths = $measurements->pluck('width_value')->map(fn($v) => (float) $v)->unique()->sort()->values();
    $heightMap = [];
    foreach ($measurements as $m) {
        $w = (float) $m->width_value;
        $h = (float) $m->height_value;
        if (!isset($heightMap[$w])) $heightMap[$w] = [];
        $heightMap[$w][] = [
            'id' => $m->id,
            'height' => $h,
            'price' => (float) $m->price,
            'label' => rtrim(rtrim(number_format($h, 2, '.', ''), '0'), '.'),
        ];
    }
    foreach ($heightMap as $w => $rows) {
        usort($heightMap[$w], fn($a, $b) => $a['height'] <=> $b['height']);
    }

    $mainImage = $product ? $product->images->first() : null;
    $basePrice = $product ? (float) ($product->sale_price ?: $product->base_price) : 0;
@endphp

<section class="rj-sf"
         @if($product) x-data="specialFilms({
            productId: {{ $product->id }},
            productSlug: @js($product->slug),
            basePrice: {{ $basePrice }},
            unit: '{{ $unit }}',
            widthOptions: {{ \Illuminate\Support\Js::from($widths) }},
            heightMap: {{ \Illuminate\Support\Js::from($heightMap) }}
         })" @endif>

    <div class="rj-sf-bg"></div>

    <div class="rj-sf-inner">

        <nav class="rj-sf-crumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span class="current">{{ $model->name }}</span>
        </nav>

        @if($product)
            <div class="rj-sf-hero">

                <div class="rj-sf-hero-text">
                    <p class="rj-sf-eyebrow">THE ONLY SPECIALTY FILM</p>
                    <h1 class="rj-sf-title">{{ $product->name }}</h1>

                    @if($product->short_description)
                        <p class="rj-sf-lead">{{ $product->short_description }}</p>
                    @endif
                </div>

                <div class="rj-sf-hero-media-wrap">
                    <div class="rj-sf-quantum">
                        <div class="rj-sf-orbit o1"><span class="rj-sf-particle p-1"></span></div>
                        <div class="rj-sf-orbit o2"><span class="rj-sf-particle p-2"></span></div>
                        <div class="rj-sf-orbit o3"><span class="rj-sf-particle p-3"></span></div>
                    </div>

                    <div class="rj-sf-hero-media">
                        @if($mainImage)
                            <img src="{{ $mainImage->url }}" alt="{{ $product->name }}">
                        @else
                            <div class="rj-sf-media-empty">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            @if($measurements->count())
                <div class="rj-sf-config">

                    <div class="rj-sf-config-head">
                        <p class="rj-sf-config-title"><span class="rj-sf-num">1</span> SIZE (W × H, {{ strtoupper($unit) }})</p>
                    </div>

                    <div class="rj-sf-measure">
                        <div class="rj-sf-select-wrap">
                            <select class="rj-sf-select" x-model.number="width" @change="onWidthChange()">
                                <option value="">Width</option>
                                <template x-for="w in widthOptions" :key="w">
                                    <option :value="w" x-text="formatNum(w) + ' {{ $unit }}'"></option>
                                </template>
                            </select>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>

                        <span class="rj-sf-x">×</span>

                        <div class="rj-sf-select-wrap">
                            <select class="rj-sf-select" x-model.number="heightId" :disabled="!width" @change="onHeightChange()">
                                <option value="">Height</option>
                                <template x-for="h in availableHeights" :key="h.id">
                                    <option :value="h.id" x-text="h.label + ' {{ $unit }}'"></option>
                                </template>
                            </select>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                    </div>

                    <p class="rj-sf-hint" x-show="!width || !heightId">Pick a width, then a height to continue.</p>
                    <p class="rj-sf-hint rj-sf-hint-ok" x-show="width && heightId" x-cloak>
                        <i class="fa-solid fa-check"></i>
                        <span x-text="formatNum(width) + ' × ' + selectedHeightLabel + ' {{ $unit }} — $' + unitPrice.toFixed(2) + ' per unit'"></span>
                    </p>

                    <div class="rj-sf-cta">
                        <button type="button"
                                class="rj-sf-btn"
                                :disabled="!canContinue"
                                @click="goToDesign()">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>Continue to design</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </button>

                        <div class="rj-sf-price-tag" x-show="canContinue" x-cloak>
                            <span>From</span>
                            <strong x-text="'$' + unitPrice.toFixed(2)"></strong>
                        </div>
                    </div>

                </div>
            @else
                <div class="rj-sf-config">
                    <div class="rj-sf-alert">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>No sizes configured for this product yet.</span>
                    </div>
                </div>
            @endif
        @else
            <div class="rj-sf-hero">
                <div class="rj-sf-hero-text">
                    <p class="rj-sf-eyebrow">THE ONLY SPECIALTY FILM</p>
                    <h1 class="rj-sf-title">{{ $model->name }}</h1>
                    @if($model->description)
                        <p class="rj-sf-lead">{{ strip_tags($model->description) }}</p>
                    @endif
                </div>
            </div>
        @endif

    </div>
</section>

<style>
    .rj-sf {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 2.5rem 0 5rem;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sf { padding: 3.5rem 0 6rem; } }

    .rj-sf-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(168, 85, 247, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(168, 85, 247, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-sf-inner {
        position: relative;
        max-width: 88rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sf-inner { padding: 0 2.5rem; } }

    .rj-sf-crumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
        margin-bottom: 2.5rem;
    }
    .rj-sf-crumb a { color: #6b7280; text-decoration: none; }
    .rj-sf-crumb a:hover { color: #c084fc; }
    .rj-sf-crumb .current { color: #9ca3af; }

    .rj-sf-hero {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
        align-items: center;
        margin-bottom: 4rem;
    }
    @media (min-width: 900px) {
        .rj-sf-hero {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 4rem;
        }
    }

    .rj-sf-hero-text {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        max-width: 34rem;
    }

    .rj-sf-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #c084fc;
        margin: 0;
        font-weight: 700;
    }
    .rj-sf-title {
        font-size: clamp(2rem, 4vw, 3.25rem);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -0.03em;
        color: #fff;
        margin: 0;
    }
    .rj-sf-lead {
        font-size: 1.125rem;
        color: #d1d5db;
        line-height: 1.6;
        margin: 0;
        font-weight: 400;
    }

    .rj-sf-hero-media-wrap {
        position: relative;
        padding: 26px;
    }

    .rj-sf-quantum {
        position: absolute;
        inset: 26px;
        pointer-events: none;
        z-index: 1;
    }

    .rj-sf-orbit {
        position: absolute;
        top: 50%;
        left: 50%;
        border-radius: 50%;
        transform-origin: center;
        animation: rj-sf-orbit linear infinite;
        will-change: transform;
    }
    .rj-sf-orbit.o1 { width: 100%; height: 100%; animation-duration: 14s; }
    .rj-sf-orbit.o2 { width: 116%; height: 116%; animation-duration: 20s; animation-direction: reverse; }
    .rj-sf-orbit.o3 { width: 84%; height: 84%; animation-duration: 10s; animation-direction: reverse; }

    @keyframes rj-sf-orbit {
        from { transform: translate(-50%, -50%) rotate(0deg); }
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .rj-sf-particle {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 9px;
        height: 9px;
        border-radius: 50%;
        opacity: 0.35;
        transition: opacity 0.4s, filter 0.4s;
    }
    .p-1 { background: #a855f7; box-shadow: 0 0 10px 3px rgba(168, 85, 247, 0.55); }
    .p-2 { background: #22d3ee; box-shadow: 0 0 10px 3px rgba(34, 211, 238, 0.55); width: 7px; height: 7px; }
    .p-3 { background: #f472b6; box-shadow: 0 0 10px 3px rgba(244, 114, 182, 0.55); width: 5px; height: 5px; }

    .rj-sf-hero-media {
        position: relative;
        z-index: 2;
        aspect-ratio: 4 / 3;
        border-radius: 16px;
        overflow: hidden;
        background: #f8f7f4;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: box-shadow 0.5s;
    }
    .rj-sf-hero-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, transparent 45%, rgba(168, 85, 247, 0.22) 100%);
        opacity: 0;
        transition: opacity 0.5s;
        pointer-events: none;
        z-index: 3;
    }

    .rj-sf-hero-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform;
    }

    .rj-sf-media-empty {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
        font-size: 2.5rem;
    }

    .rj-sf-hero-media-wrap:hover .rj-sf-hero-media {
        box-shadow: 0 0 70px -12px rgba(168, 85, 247, 0.55), 0 0 0 1px rgba(168, 85, 247, 0.3);
    }
    .rj-sf-hero-media-wrap:hover .rj-sf-hero-media::after { opacity: 1; }
    .rj-sf-hero-media-wrap:hover .rj-sf-hero-media img { transform: scale(1.06); }
    .rj-sf-hero-media-wrap:hover .rj-sf-particle {
        opacity: 1;
        filter: brightness(1.6) drop-shadow(0 0 6px currentColor);
    }

    .rj-sf-config {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-width: 44rem;
        padding: 1.75rem 1.75rem 1.5rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
    }

    .rj-sf-config-head {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }
    .rj-sf-config-title {
        font-family: ui-monospace, monospace;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #fff;
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.625rem;
    }
    .rj-sf-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 1.5px solid rgba(255, 255, 255, 0.25);
        color: #d1d5db;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .rj-sf-measure {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .rj-sf-select-wrap {
        position: relative;
        flex: 1;
    }
    .rj-sf-select {
        width: 100%;
        appearance: none;
        -webkit-appearance: none;
        color-scheme: dark;
        background-color: rgba(255, 255, 255, 0.03);
        border: 1.5px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 14px 40px 14px 16px;
        color: #fff;
        font-size: 14px;
        font-family: inherit;
        cursor: pointer;
        outline: none;
        transition: all 0.2s;
    }
    .rj-sf-select option {
        background: #0a0715;
        color: #fff;
    }
    .rj-sf-select:hover,
    .rj-sf-select:focus {
        border-color: rgba(168, 85, 247, 0.5);
        background-color: rgba(168, 85, 247, 0.05);
    }
    .rj-sf-select:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .rj-sf-select-wrap > i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6b7280;
        font-size: 11px;
    }
    .rj-sf-x {
        color: #6b7280;
        font-size: 14px;
    }

    .rj-sf-hint {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
    }
    .rj-sf-hint-ok {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6ee7b7;
        font-family: ui-monospace, monospace;
        font-size: 12px;
    }
    .rj-sf-hint-ok i { color: #34d399; }

    .rj-sf-alert {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        background: rgba(251, 191, 36, 0.08);
        border: 1px solid rgba(251, 191, 36, 0.25);
        color: #fcd34d;
        font-size: 12px;
    }
    .rj-sf-alert i { color: #fbbf24; margin-top: 2px; }

    .rj-sf-cta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .rj-sf-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.625rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #a855f7, #ec4899);
        color: #fff;
        border: none;
        border-radius: 9999px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 8px 24px -8px rgba(168, 85, 247, 0.5);
    }
    .rj-sf-btn:hover:not(:disabled) {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 12px 32px -8px rgba(168, 85, 247, 0.7);
    }
    .rj-sf-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        box-shadow: none;
    }

    .rj-sf-price-tag {
        display: inline-flex;
        flex-direction: column;
        align-items: flex-end;
        font-family: ui-monospace, monospace;
        line-height: 1.1;
    }
    .rj-sf-price-tag span {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
    }
    .rj-sf-price-tag strong {
        font-size: 18px;
        font-weight: 800;
        color: #fff;
    }
</style>

<script>
function specialFilms(config) {
    return {
        productId: config.productId,
        productSlug: config.productSlug,
        basePrice: parseFloat(config.basePrice) || 0,
        unit: config.unit || 'in',
        widthOptions: config.widthOptions || [],
        heightMap: config.heightMap || {},

        width: null,
        heightId: null,

        get availableHeights() {
            if (!this.width) return [];
            return this.heightMap[this.width] || [];
        },

        get selectedHeight() {
            if (!this.heightId) return null;
            return this.availableHeights.find(h => h.id === this.heightId) || null;
        },

        get selectedHeightLabel() {
            const h = this.selectedHeight;
            return h ? h.label : '';
        },

        get unitPrice() {
            const h = this.selectedHeight;
            return h ? parseFloat(h.price) : this.basePrice;
        },

        get canContinue() {
            return this.width && this.heightId;
        },

        onWidthChange() {
            this.heightId = null;
        },

        onHeightChange() {},

        formatNum(v) {
            const n = parseFloat(v);
            if (isNaN(n)) return v;
            return n % 1 === 0 ? String(n) : n.toFixed(2).replace(/\.?0+$/, '');
        },

        goToDesign() {
            if (!this.canContinue) return;
            let url = '/design/' + this.productSlug;
            url += '?w=' + this.width + '&h=' + (this.selectedHeight ? this.selectedHeight.height : 0);
            window.location.href = url;
        }
    }
}
</script>

@endsection

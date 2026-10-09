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
    $measurementOption = $product->options->where('status', 1)->where('type', 'measurement')->first();
    $measurements = $measurementOption ? $measurementOption->activeMeasurements : collect();
    $unitLabels = ['inch' => 'in', 'feet' => 'ft', 'cm' => 'cm'];
    $unit = $measurementOption ? ($unitLabels[$measurementOption->measurement_unit] ?? 'in') : 'ft';

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

    $materialCards = collect([$product])->merge($siblings)->values();

    $basePrice = (float) ($product->sale_price ?: $product->base_price);
@endphp

<section class="rj-sg-hero"
         x-data="signProduct({
            productId: {{ $product->id }},
            productName: @js($product->name),
            basePrice: {{ $basePrice }},
            unit: '{{ $unit }}',
            widthOptions: {{ \Illuminate\Support\Js::from($widths) }},
            heightMap: {{ \Illuminate\Support\Js::from($heightMap) }}
         })">

    <div class="rj-sg-bg"></div>

    <div class="rj-sg-inner">

        <nav class="rj-sg-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            @if($product->model)
                <span>/</span>
                <a href="{{ url('model/' . $product->model->slug) }}">{{ $product->model->name }}</a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="rj-sg-layout">

            <div class="rj-sg-left">

                <div class="rj-sg-gallery" x-data="{ active: 0 }">
                    <div class="rj-sg-thumbs">
                        @forelse($images as $index => $image)
                            <button type="button"
                                    @click="active = {{ $index }}"
                                    :class="active === {{ $index }} ? 'rj-sg-thumb-active' : ''"
                                    class="rj-sg-thumb">
                                <img src="{{ $image->url }}" alt="{{ $product->name }}">
                            </button>
                        @empty
                            <div class="rj-sg-thumb rj-sg-thumb-empty">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endforelse
                    </div>

                    <div class="rj-sg-main">
                        @forelse($images as $index => $image)
                            <img src="{{ $image->url }}"
                                 alt="{{ $product->name }}"
                                 class="rj-sg-img"
                                 x-show="active === {{ $index }}">
                        @empty
                            <div class="rj-sg-img-empty">
                                <i class="fa-regular fa-image"></i>
                                <span>No image</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($relatedProducts->count())
                    <div class="rj-sg-accessories">
                        <p class="rj-sg-acc-title">GOES WITH THIS</p>
                        <div class="rj-sg-acc-list">
                            @foreach($relatedProducts->take(3) as $rel)
                                @php $relImg = $rel->images->first(); @endphp
                                <a href="{{ url('product/' . $rel->slug) }}" class="rj-sg-acc-card">
                                    <div class="rj-sg-acc-img">
                                        @if($relImg)
                                            <img src="{{ $relImg->url }}" alt="{{ $rel->name }}">
                                        @endif
                                    </div>
                                    <div class="rj-sg-acc-body">
                                        <p class="rj-sg-acc-name">{{ $rel->name }}</p>
                                        <p class="rj-sg-acc-price">${{ number_format((float) ($rel->sale_price ?: $rel->base_price), 2) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <div class="rj-sg-right">

                <p class="rj-sg-eyebrow">STICKS ON</p>
                <h1 class="rj-sg-title">{{ $product->name }}</h1>

                @if($product->short_description)
                    <p class="rj-sg-lead">{{ $product->short_description }}</p>
                @endif

                @if($product->description)
                    <p class="rj-sg-details">{{ strip_tags($product->description) }}</p>
                @endif

                @if($materialCards->count() > 1)
                    <div class="rj-sg-step">
                        <p class="rj-sg-step-title"><span class="rj-sg-step-num">1</span> MATERIAL</p>

                        <div class="rj-sg-materials">
                            @foreach($materialCards as $material)
                                @php
                                    $isCurrent = $material->id === $product->id;
                                    $matImg = $material->images->first();
                                @endphp
                                <a href="{{ url('product/' . $material->slug) }}"
                                   class="rj-sg-material {{ $isCurrent ? 'is-active' : '' }}">
                                    <p class="rj-sg-mat-name">{{ $material->name }}</p>
                                    @if($isCurrent)
                                        <p class="rj-sg-mat-here">YOU'RE HERE</p>
                                    @else
                                        <p class="rj-sg-mat-link">View product →</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>

                        <div class="rj-sg-note rj-sg-note-info">
                            <i class="fa-solid fa-check"></i>
                            <span>You're customizing <strong>{{ $product->name }}</strong> — you can continue to the next step below.</span>
                        </div>

                        <div class="rj-sg-note rj-sg-note-warn">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Each material is a <strong>separate product</strong>. Choosing a different one above will take you to that product's page — please make sure you're ordering the right material.</span>
                        </div>
                    </div>
                @endif

                <div class="rj-sg-step">
                    <p class="rj-sg-step-title"><span class="rj-sg-step-num">2</span> SIZE (W × H, {{ strtoupper($unit) }})</p>

                    @if($measurements->count())
                        <div class="rj-sg-measure-row">
                            <div class="rj-sg-select-wrap">
                                <select class="rj-sg-select" x-model.number="width" @change="onWidthChange()">
                                    <option value="">Width</option>
                                    <template x-for="w in widthOptions" :key="w">
                                        <option :value="w" x-text="formatNum(w) + ' {{ $unit }}'"></option>
                                    </template>
                                </select>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>

                            <span class="rj-sg-times">×</span>

                            <div class="rj-sg-select-wrap">
                                <select class="rj-sg-select" x-model.number="heightId" :disabled="!width" @change="onHeightChange()">
                                    <option value="">Height</option>
                                    <template x-for="h in availableHeights" :key="h.id">
                                        <option :value="h.id" x-text="h.label + ' {{ $unit }}'"></option>
                                    </template>
                                </select>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>

                        <p class="rj-sg-hint" x-show="!width || !heightId">Pick a width, then a height to see your price.</p>
                        <p class="rj-sg-hint rj-sg-hint-ok" x-show="width && heightId" x-cloak>
                            <i class="fa-solid fa-check"></i>
                            <span x-text="formatNum(width) + ' × ' + selectedHeightLabel + ' {{ $unit }} — $' + unitPrice.toFixed(2) + ' per unit'"></span>
                        </p>
                    @else
                        <div class="rj-sg-note rj-sg-note-warn">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>No sizes configured for this product yet.</span>
                        </div>
                    @endif
                </div>

                <div class="rj-sg-step">
                    <p class="rj-sg-step-title"><span class="rj-sg-step-num">3</span> UPLOAD ARTWORK</p>

                    <label class="rj-sg-drop"
                           :class="{ 'is-dragging': dragging, 'has-file': fileName }"
                           @dragover.prevent="dragging = true"
                           @dragleave.prevent="dragging = false"
                           @drop.prevent="onDrop($event)">
                        <input type="file" accept="image/png,image/jpeg,image/webp,application/pdf" class="hidden" @change="onFile($event)">

                        <template x-if="!fileName">
                            <div class="rj-sg-drop-inner">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <p class="rj-sg-drop-title">Drop artwork here or click to upload</p>
                                <p class="rj-sg-drop-sub">PNG, JPG, WEBP, PDF · Max 30MB</p>
                            </div>
                        </template>

                        <template x-if="fileName">
                            <div class="rj-sg-drop-inner">
                                <i class="fa-solid fa-circle-check rj-sg-drop-ok"></i>
                                <p class="rj-sg-drop-title" x-text="fileName"></p>
                                <p class="rj-sg-drop-sub">Click to replace</p>
                            </div>
                        </template>
                    </label>

                    <p class="rj-sg-hint rj-sg-hint-soft">You can also send it later — we'll email you a link.</p>
                </div>

                <div class="rj-sg-step">
                    <p class="rj-sg-step-title"><span class="rj-sg-step-num">4</span> QUANTITY & NOTES</p>

                    <div class="rj-sg-qty-row">
                        <div class="rj-sg-qty">
                            <button type="button" @click="decQty()" class="rj-sg-qty-btn">−</button>
                            <input type="number" x-model.number="qty" min="1" max="999" class="rj-sg-qty-input">
                            <button type="button" @click="incQty()" class="rj-sg-qty-btn">+</button>
                        </div>
                    </div>

                    <textarea class="rj-sg-notes"
                              x-model="notes"
                              rows="3"
                              maxlength="500"
                              placeholder="Anything we should know? (optional)"></textarea>
                </div>

                <div class="rj-sg-total">
                    <div class="rj-sg-total-row">
                        <span>Unit price</span>
                        <span x-text="'$' + unitPrice.toFixed(2)"></span>
                    </div>
                    <div class="rj-sg-total-row">
                        <span x-text="'Quantity × ' + (qty || 1)"></span>
                        <span x-text="'$' + (unitPrice * (qty || 1)).toFixed(2)"></span>
                    </div>
                    <div class="rj-sg-total-row rj-sg-total-grand">
                        <span>Total</span>
                        <span x-text="'$' + totalPrice.toFixed(2)"></span>
                    </div>
                </div>

                <button type="button"
                        class="rj-sg-btn"
                        :disabled="!canAdd || adding"
                        @click="addToCart()">
                    <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-cart-plus'"></i>
                    <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                    <span class="rj-sg-btn-price">$<span x-text="totalPrice.toFixed(2)"></span></span>
                </button>

                <a href="{{ url('contact-us') }}" class="rj-sg-btn-outline">
                    <i class="fa-solid fa-comments"></i>
                    <span>Ask a question</span>
                </a>

            </div>

        </div>
    </div>
</section>

<style>
    .rj-sg-hero { position: relative; background: #05030f; color: #fff; overflow: hidden; padding: 2rem 0 4rem; }
    @media (min-width: 768px) { .rj-sg-hero { padding: 3rem 0 6rem; } }
    .rj-sg-bg {
        position: absolute; inset: 0; opacity: 0.03; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-sg-inner { position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; }
    @media (min-width: 1024px) { .rj-sg-inner { padding: 0 3rem; } }

    .rj-sg-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 2rem;
    }
    .rj-sg-breadcrumb a { color: #6b7280; text-decoration: none; }
    .rj-sg-breadcrumb a:hover { color: #a5b4fc; }
    .rj-sg-breadcrumb .current { color: #9ca3af; }

    .rj-sg-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
    @media (min-width: 900px) {
        .rj-sg-layout { grid-template-columns: minmax(0, 440px) 1fr; gap: 3rem; align-items: flex-start; }
    }

    .rj-sg-left { display: flex; flex-direction: column; gap: 1.5rem; }

    .rj-sg-gallery { display: flex; gap: 0.75rem; }
    .rj-sg-thumbs { display: flex; flex-direction: column; gap: 0.5rem; flex-shrink: 0; }
    .rj-sg-thumb {
        width: 54px; height: 54px; border-radius: 10px; overflow: hidden;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        padding: 0; cursor: pointer; transition: all 0.3s;
    }
    .rj-sg-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rj-sg-thumb:hover { border-color: rgba(99,102,241,0.4); }
    .rj-sg-thumb-active { border-color: #6366f1 !important; box-shadow: 0 0 16px -4px rgba(99,102,241,0.6); }
    .rj-sg-thumb-empty { display: flex; align-items: center; justify-content: center; color: rgba(255,255,255,0.1); }

    .rj-sg-main {
        flex: 1; aspect-ratio: 1; border-radius: 16px; overflow: hidden;
        background: #f8f7f4; border: 1px solid rgba(255,255,255,0.08);
        position: relative; display: flex; align-items: center; justify-content: center;
    }
    .rj-sg-img { width: 100%; height: 100%; object-fit: contain; }
    .rj-sg-img-empty {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 0.5rem; color: #4b5563; font-size: 2rem;
    }
    .rj-sg-img-empty span { font-size: 12px; }

    .rj-sg-accessories { padding-top: 0.5rem; }
    .rj-sg-acc-title {
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.22em;
        color: #6b7280; margin: 0 0 0.75rem;
    }
    .rj-sg-acc-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .rj-sg-acc-card {
        display: flex; gap: 0.75rem; align-items: center;
        padding: 0.625rem; border-radius: 10px;
        background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06);
        text-decoration: none; transition: all 0.25s;
    }
    .rj-sg-acc-card:hover { border-color: rgba(99,102,241,0.3); background: rgba(99,102,241,0.05); }
    .rj-sg-acc-img {
        width: 44px; height: 44px; border-radius: 8px; overflow: hidden;
        background: #0a0715; border: 1px solid rgba(255,255,255,0.06);
        flex-shrink: 0; display: flex; align-items: center; justify-content: center;
    }
    .rj-sg-acc-img img { width: 100%; height: 100%; object-fit: cover; }
    .rj-sg-acc-body { flex: 1; min-width: 0; }
    .rj-sg-acc-name { font-size: 12px; color: #d1d5db; margin: 0; line-height: 1.3; }
    .rj-sg-acc-price { font-family: ui-monospace, monospace; font-size: 11px; color: #a5b4fc; margin: 2px 0 0; font-weight: 600; }

    .rj-sg-right { display: flex; flex-direction: column; gap: 1.5rem; min-width: 0; }

    .rj-sg-eyebrow {
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.25em;
        color: #f97316; margin: 0;
    }
    .rj-sg-title {
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0;
    }
    .rj-sg-lead {
        font-size: 1.125rem; font-weight: 600; color: #e5e7eb;
        line-height: 1.4; margin: 0;
    }
    .rj-sg-details {
        font-size: 0.9375rem; color: #9ca3af; line-height: 1.6; margin: 0;
    }

    .rj-sg-step { display: flex; flex-direction: column; gap: 0.75rem; }
    .rj-sg-step-title {
        font-family: ui-monospace, monospace; font-size: 12px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #fff; margin: 0; font-weight: 700;
        display: flex; align-items: center; gap: 0.625rem;
    }
    .rj-sg-step-num {
        width: 22px; height: 22px; border-radius: 50%;
        background: transparent; border: 1.5px solid rgba(255,255,255,0.25);
        color: #d1d5db; font-size: 11px; font-weight: 700;
        display: inline-flex; align-items: center; justify-content: center;
    }

    .rj-sg-materials { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.625rem; }
    @media (max-width: 640px) { .rj-sg-materials { grid-template-columns: 1fr; } }
    .rj-sg-material {
        padding: 0.875rem 0.875rem 1rem;
        border-radius: 10px;
        background: rgba(255,255,255,0.02);
        border: 1.5px solid rgba(255,255,255,0.08);
        text-decoration: none;
        transition: all 0.25s;
        display: flex; flex-direction: column; gap: 0.5rem;
    }
    .rj-sg-material:hover { border-color: rgba(249,115,22,0.5); }
    .rj-sg-material.is-active {
        border-color: #f97316;
        background: rgba(249,115,22,0.06);
        box-shadow: 0 0 24px -8px rgba(249,115,22,0.4);
    }
    .rj-sg-mat-name { font-size: 13px; color: #e5e7eb; font-weight: 600; margin: 0; line-height: 1.3; }
    .rj-sg-mat-here { font-family: ui-monospace, monospace; font-size: 10px; color: #f97316; margin: 0; font-weight: 700; letter-spacing: 0.1em; }
    .rj-sg-mat-link { font-family: ui-monospace, monospace; font-size: 10px; color: #818cf8; margin: 0; }

    .rj-sg-note {
        display: flex; gap: 0.75rem; align-items: flex-start;
        padding: 0.75rem 1rem; border-radius: 10px;
        font-size: 12px; line-height: 1.5;
    }
    .rj-sg-note i { margin-top: 2px; flex-shrink: 0; font-size: 12px; }
    .rj-sg-note-info { background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.25); color: #6ee7b7; }
    .rj-sg-note-info i { color: #34d399; }
    .rj-sg-note-warn { background: rgba(251,191,36,0.08); border: 1px solid rgba(251,191,36,0.25); color: #fcd34d; }
    .rj-sg-note-warn i { color: #fbbf24; }

    .rj-sg-measure-row { display: flex; align-items: center; gap: 0.75rem; }
    .rj-sg-select-wrap { position: relative; flex: 1; }
    .rj-sg-select {
        width: 100%; appearance: none; -webkit-appearance: none;
        background: rgba(255,255,255,0.03);
        border: 1.5px solid rgba(255,255,255,0.1);
        border-radius: 12px; padding: 14px 40px 14px 16px;
        color: #fff; font-size: 14px; font-family: inherit;
        cursor: pointer; outline: none;
        transition: all 0.2s;
    }
    .rj-sg-select:hover, .rj-sg-select:focus { border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.05); }
    .rj-sg-select:disabled { opacity: 0.5; cursor: not-allowed; }
    .rj-sg-select-wrap i {
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        pointer-events: none; color: #6b7280; font-size: 11px;
    }
    .rj-sg-times { color: #6b7280; font-size: 14px; }

    .rj-sg-hint { font-size: 12px; color: #6b7280; margin: 0; }
    .rj-sg-hint-soft { font-style: italic; color: #4b5563; }
    .rj-sg-hint-ok {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: #6ee7b7; font-family: ui-monospace, monospace; font-size: 12px;
    }
    .rj-sg-hint-ok i { color: #34d399; }

    .rj-sg-drop {
        display: block; padding: 1.75rem 1rem;
        background: rgba(255,255,255,0.02);
        border: 1.5px dashed rgba(255,255,255,0.15);
        border-radius: 14px;
        cursor: pointer; text-align: center;
        transition: all 0.25s;
    }
    .rj-sg-drop:hover, .rj-sg-drop.is-dragging {
        border-color: #6366f1;
        background: rgba(99,102,241,0.06);
    }
    .rj-sg-drop.has-file {
        border-style: solid;
        border-color: rgba(52,211,153,0.5);
        background: rgba(52,211,153,0.05);
    }
    .rj-sg-drop input { display: none; }
    .rj-sg-drop-inner { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
    .rj-sg-drop-inner > i { font-size: 26px; color: #6b7280; }
    .rj-sg-drop-ok { color: #34d399 !important; }
    .rj-sg-drop-title { font-size: 13px; color: #e5e7eb; margin: 0; font-weight: 600; }
    .rj-sg-drop-sub { font-size: 11px; color: #6b7280; margin: 0; font-family: ui-monospace, monospace; }

    .rj-sg-qty-row { display: flex; align-items: center; gap: 0.75rem; }
    .rj-sg-qty {
        display: inline-flex; align-items: center;
        background: rgba(255,255,255,0.03);
        border: 1.5px solid rgba(255,255,255,0.1);
        border-radius: 12px; overflow: hidden;
    }
    .rj-sg-qty-btn {
        width: 44px; height: 44px;
        background: transparent; border: none; color: #d1d5db;
        font-size: 18px; cursor: pointer; transition: all 0.2s;
    }
    .rj-sg-qty-btn:hover { background: rgba(99,102,241,0.15); color: #fff; }
    .rj-sg-qty-input {
        width: 60px; height: 44px; background: transparent;
        border: none; border-left: 1px solid rgba(255,255,255,0.08);
        border-right: 1px solid rgba(255,255,255,0.08);
        color: #fff; font-family: ui-monospace, monospace;
        font-size: 15px; font-weight: 600; text-align: center;
        outline: none; -moz-appearance: textfield;
    }
    .rj-sg-qty-input::-webkit-outer-spin-button,
    .rj-sg-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .rj-sg-notes {
        width: 100%; padding: 12px 14px;
        background: rgba(255,255,255,0.02);
        border: 1.5px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        color: #fff; font-family: inherit; font-size: 13px;
        outline: none; resize: vertical; min-height: 76px;
        transition: border-color 0.2s;
    }
    .rj-sg-notes:focus { border-color: rgba(99,102,241,0.5); }
    .rj-sg-notes::placeholder { color: #4b5563; }

    .rj-sg-total {
        display: flex; flex-direction: column; gap: 0.5rem;
        padding: 1rem 1.25rem;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
    }
    .rj-sg-total-row {
        display: flex; justify-content: space-between; align-items: baseline;
        font-size: 13px; color: #9ca3af;
        font-family: ui-monospace, monospace;
    }
    .rj-sg-total-row span:last-child { color: #e5e7eb; font-weight: 600; }
    .rj-sg-total-grand {
        padding-top: 0.5rem; margin-top: 0.25rem;
        border-top: 1px solid rgba(255,255,255,0.08);
        font-size: 15px;
    }
    .rj-sg-total-grand span:last-child { color: #fff; font-size: 17px; font-weight: 800; }

    .rj-sg-btn {
        display: flex; align-items: center; justify-content: space-between;
        gap: 0.625rem; padding: 1rem 1.25rem 1rem 1.75rem;
        background: #fff; color: #05030f;
        border: none; border-radius: 9999px;
        font-family: inherit; font-size: 15px; font-weight: 700;
        cursor: pointer; transition: all 0.3s;
    }
    .rj-sg-btn:hover:not(:disabled) {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192,132,252,0.4);
        transform: translateY(-1px);
    }
    .rj-sg-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .rj-sg-btn > span:first-of-type { flex: 1; text-align: left; margin-left: 0.5rem; }
    .rj-sg-btn-price {
        font-family: ui-monospace, monospace; font-size: 13px;
        padding: 6px 14px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff; border-radius: 9999px; font-weight: 700;
    }

    .rj-sg-btn-outline {
        display: inline-flex; align-items: center; justify-content: center; gap: 0.625rem;
        padding: 1rem 1.75rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.12);
        border-radius: 9999px;
        color: #fff; text-decoration: none;
        font-size: 14px; font-weight: 600;
        transition: all 0.25s;
    }
    .rj-sg-btn-outline:hover { background: rgba(99,102,241,0.1); border-color: rgba(99,102,241,0.4); }
</style>

<script>
function signProduct(config) {
    return {
        productId: config.productId,
        productName: config.productName,
        basePrice: parseFloat(config.basePrice) || 0,
        unit: config.unit || 'ft',
        widthOptions: config.widthOptions || [],
        heightMap: config.heightMap || {},

        width: null,
        heightId: null,

        dragging: false,
        fileName: '',
        fileData: null,

        qty: 1,
        notes: '',
        adding: false,

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

        get totalPrice() {
            return this.unitPrice * (this.qty || 1);
        },

        get canAdd() {
            return this.width && this.heightId;
        },

        onWidthChange() {
            this.heightId = null;
        },

        onHeightChange() {
        },

        formatNum(v) {
            const n = parseFloat(v);
            if (isNaN(n)) return v;
            return n % 1 === 0 ? String(n) : n.toFixed(2).replace(/\.?0+$/, '');
        },

        incQty() { this.qty = Math.min(999, (this.qty || 1) + 1); },
        decQty() { this.qty = Math.max(1, (this.qty || 1) - 1); },

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
            this.fileName = f.name;
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.fileData = ev.target.result;
            };
            reader.readAsDataURL(f);
        },

        async addToCart() {
            if (!this.canAdd || this.adding) {
                if (!this.canAdd) this.flash('Please select width and height');
                return;
            }

            this.adding = true;

            const sizeLabel = this.formatNum(this.width) + ' × ' + this.selectedHeightLabel + ' ' + this.unit;

            const attributes = {
                'Size': sizeLabel,
                'Material': this.productName,
            };

            const payload = {
                product_id: this.productId,
                name: this.productName,
                unit_price: this.unitPrice,
                qty: this.qty,
                attributes: attributes,
                print_type: 'custom_size',
                width_inch: parseFloat(this.width),
                height_inch: parseFloat(this.selectedHeight ? this.selectedHeight.height : 0),
                note: this.notes || null,
                image: this.fileData || null
            };

            try {
                const store = window.Alpine && window.Alpine.store('cart');
                if (!store) {
                    this.flash('Cart not ready');
                    this.adding = false;
                    return;
                }
                const res = await store.add(payload);
                if (res && res.success) this.flash('Added to cart ✓');
                else this.flash('Could not add to cart');
            } catch (e) {
                this.flash('Error: ' + (e.message || 'unknown'));
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

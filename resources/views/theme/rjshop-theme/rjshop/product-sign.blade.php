@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('meta_keywords', $product->meta_keywords)

@section('content')

@php
    $images = $product->images;
    $mainImage = $images->first();

    $measurementOption = $product->options->where('status', 1)->where('type', 'measurement')->first();
    $measurements = $measurementOption ? $measurementOption->activeMeasurements : collect();
    $unitLabels = ['inch' => 'in', 'feet' => 'ft', 'cm' => 'cm'];
    $unit = $measurementOption ? ($unitLabels[$measurementOption->measurement_unit] ?? 'ft') : 'ft';

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

<section class="rj-sg2"
         x-data="signProduct({
            productId: {{ $product->id }},
            productName: @js($product->name),
            basePrice: {{ $basePrice }},
            unit: '{{ $unit }}',
            widthOptions: {{ \Illuminate\Support\Js::from($widths) }},
            heightMap: {{ \Illuminate\Support\Js::from($heightMap) }}
         })">

    <div class="rj-sg2-inner">

        <nav class="rj-sg2-crumb">
            <a href="{{ url('/') }}">Home</a>
            @if($product->model)
                <span>/</span>
                <a href="{{ url('model/' . $product->model->slug) }}">{{ $product->model->name }}</a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="rj-sg2-grid">

            <div class="rj-sg2-media-wrap">
                <div class="rj-sg2-quantum">
                    <div class="rj-sg2-orbit o1"><span class="rj-sg2-particle p-o"></span></div>
                    <div class="rj-sg2-orbit o2"><span class="rj-sg2-particle p-i"></span></div>
                    <div class="rj-sg2-orbit o3"><span class="rj-sg2-particle p-p"></span></div>
                </div>

                <div class="rj-sg2-media">
                    @if($mainImage)
                        <img src="{{ $mainImage->url }}" alt="{{ $product->name }}">
                    @else
                        <div class="rj-sg2-media-empty">
                            <i class="fa-regular fa-image"></i>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rj-sg2-info">

                <p class="rj-sg2-eyebrow">STICKS ON</p>
                <h1 class="rj-sg2-title">{{ $product->name }}</h1>

                @if($product->short_description)
                    <p class="rj-sg2-lead">{{ $product->short_description }}</p>
                @endif

                @if($product->description)
                    <p class="rj-sg2-desc">{{ strip_tags($product->description) }}</p>
                @endif

                @if($materialCards->count() > 1)
                    <div class="rj-sg2-block">
                        <p class="rj-sg2-block-title"><span class="rj-sg2-num">1</span> MATERIAL</p>

                        <div class="rj-sg2-materials">
                            @foreach($materialCards as $material)
                                @php $isCurrent = $material->id === $product->id; @endphp
                                <a href="{{ url('product/' . $material->slug) }}"
                                   class="rj-sg2-mat {{ $isCurrent ? 'is-active' : '' }}">
                                    <p class="rj-sg2-mat-name">{{ $material->name }}</p>
                                    @if($isCurrent)
                                        <p class="rj-sg2-mat-here">YOU'RE HERE</p>
                                    @else
                                        <p class="rj-sg2-mat-link">View product →</p>
                                    @endif
                                </a>
                            @endforeach
                        </div>

                        <div class="rj-sg2-alert rj-sg2-alert-ok">
                            <i class="fa-solid fa-check"></i>
                            <span>You're customizing <strong>{{ $product->name }}</strong> — continue to the next step below.</span>
                        </div>

                        <div class="rj-sg2-alert rj-sg2-alert-warn">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Each material is a <strong>separate product</strong>. Choosing a different one above will take you to that product's page.</span>
                        </div>
                    </div>
                @endif

                <div class="rj-sg2-block">
                    <p class="rj-sg2-block-title"><span class="rj-sg2-num">2</span> SIZE (W × H, {{ strtoupper($unit) }})</p>

                    @if($measurements->count())
                        <div class="rj-sg2-measure">
                            <div class="rj-sg2-select-wrap">
                                <select class="rj-sg2-select" x-model.number="width" @change="onWidthChange()">
                                    <option value="">Width</option>
                                    <template x-for="w in widthOptions" :key="w">
                                        <option :value="w" x-text="formatNum(w) + ' {{ $unit }}'"></option>
                                    </template>
                                </select>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>

                            <span class="rj-sg2-x">×</span>

                            <div class="rj-sg2-select-wrap">
                                <select class="rj-sg2-select" x-model.number="heightId" :disabled="!width" @change="onHeightChange()">
                                    <option value="">Height</option>
                                    <template x-for="h in availableHeights" :key="h.id">
                                        <option :value="h.id" x-text="h.label + ' {{ $unit }}'"></option>
                                    </template>
                                </select>
                                <i class="fa-solid fa-chevron-down"></i>
                            </div>
                        </div>

                        <p class="rj-sg2-hint" x-show="!width || !heightId">Pick a width, then a height to see your price.</p>
                        <p class="rj-sg2-hint rj-sg2-hint-ok" x-show="width && heightId" x-cloak>
                            <i class="fa-solid fa-check"></i>
                            <span x-text="formatNum(width) + ' × ' + selectedHeightLabel + ' {{ $unit }} — $' + unitPrice.toFixed(2) + ' per unit'"></span>
                        </p>
                    @else
                        <div class="rj-sg2-alert rj-sg2-alert-warn">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>No sizes configured for this product yet.</span>
                        </div>
                    @endif
                </div>

                <div class="rj-sg2-block">
                    <p class="rj-sg2-block-title"><span class="rj-sg2-num">3</span> UPLOAD ARTWORK</p>

                    <label class="rj-sg2-drop"
                           :class="{ 'is-drag': dragging, 'has-file': fileName }"
                           @dragover.prevent="dragging = true"
                           @dragleave.prevent="dragging = false"
                           @drop.prevent="onDrop($event)">
                        <input type="file" accept="image/png,image/jpeg,image/webp,application/pdf" @change="onFile($event)">

                        <template x-if="!fileName">
                            <div class="rj-sg2-drop-inner">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                                <p class="rj-sg2-drop-title">Drop artwork here or click to upload</p>
                                <p class="rj-sg2-drop-sub">PNG · JPG · WEBP · PDF · Max 30MB</p>
                            </div>
                        </template>

                        <template x-if="fileName">
                            <div class="rj-sg2-drop-inner">
                                <i class="fa-solid fa-circle-check rj-sg2-drop-ok"></i>
                                <p class="rj-sg2-drop-title" x-text="fileName"></p>
                                <p class="rj-sg2-drop-sub">Click to replace</p>
                            </div>
                        </template>
                    </label>

                    <p class="rj-sg2-hint rj-sg2-hint-soft">You can also send it later — we'll email you a link.</p>
                </div>

                <div class="rj-sg2-block">
                    <p class="rj-sg2-block-title"><span class="rj-sg2-num">4</span> QUANTITY & NOTES</p>

                    <div class="rj-sg2-qty">
                        <button type="button" @click="decQty()" class="rj-sg2-qty-btn">−</button>
                        <input type="number" x-model.number="qty" min="1" max="999" class="rj-sg2-qty-input">
                        <button type="button" @click="incQty()" class="rj-sg2-qty-btn">+</button>
                    </div>

                    <textarea class="rj-sg2-notes"
                              x-model="notes"
                              rows="3"
                              maxlength="500"
                              placeholder="Anything we should know? (optional)"></textarea>
                </div>

                <div class="rj-sg2-total">
                    <div class="rj-sg2-total-row">
                        <span>Unit price</span>
                        <span x-text="'$' + unitPrice.toFixed(2)"></span>
                    </div>
                    <div class="rj-sg2-total-row">
                        <span x-text="'Quantity × ' + (qty || 1)"></span>
                        <span x-text="'$' + (unitPrice * (qty || 1)).toFixed(2)"></span>
                    </div>
                    <div class="rj-sg2-total-row rj-sg2-grand">
                        <span>Total</span>
                        <span x-text="'$' + totalPrice.toFixed(2)"></span>
                    </div>
                </div>

                <button type="button"
                        class="rj-sg2-btn"
                        :disabled="!canAdd || adding"
                        @click="addToCart()">
                    <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-cart-plus'"></i>
                    <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                    <span class="rj-sg2-btn-price">$<span x-text="totalPrice.toFixed(2)"></span></span>
                </button>

                <a href="{{ url('contact-us') }}" class="rj-sg2-btn-outline">
                    <i class="fa-solid fa-comments"></i>
                    <span>Ask a question</span>
                </a>

            </div>

        </div>
    </div>
</section>

<style>
    .rj-sg2 {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 2.5rem 0 5rem;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sg2 { padding: 3.5rem 0 6rem; } }

    .rj-sg2-inner {
        max-width: 88rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sg2-inner { padding: 0 2.5rem; } }

    .rj-sg2-crumb {
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
    .rj-sg2-crumb a { color: #6b7280; text-decoration: none; }
    .rj-sg2-crumb a:hover { color: #fb923c; }
    .rj-sg2-crumb .current { color: #9ca3af; }

    .rj-sg2-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
    }
    @media (min-width: 900px) {
        .rj-sg2-grid {
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 1fr);
            gap: 3.5rem;
            align-items: flex-start;
        }
    }

    .rj-sg2-media-wrap {
        position: sticky;
        top: 100px;
        padding: 26px;
    }

    .rj-sg2-quantum {
        position: absolute;
        inset: 26px;
        pointer-events: none;
        z-index: 1;
    }

    .rj-sg2-orbit {
        position: absolute;
        top: 50%;
        left: 50%;
        border-radius: 50%;
        transform-origin: center;
        animation: rj-quantum-orbit linear infinite;
        will-change: transform;
    }
    .rj-sg2-orbit.o1 { width: 100%; height: 100%; animation-duration: 14s; }
    .rj-sg2-orbit.o2 { width: 116%; height: 116%; animation-duration: 20s; animation-direction: reverse; }
    .rj-sg2-orbit.o3 { width: 84%; height: 84%; animation-duration: 10s; animation-direction: reverse; }

    @keyframes rj-quantum-orbit {
        from { transform: translate(-50%, -50%) rotate(0deg); }
        to { transform: translate(-50%, -50%) rotate(360deg); }
    }

    .rj-sg2-particle {
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
    .p-o { background: #f97316; box-shadow: 0 0 10px 3px rgba(249, 115, 22, 0.55); }
    .p-i { background: #818cf8; box-shadow: 0 0 10px 3px rgba(129, 140, 248, 0.55); width: 7px; height: 7px; }
    .p-p { background: #ec4899; box-shadow: 0 0 10px 3px rgba(236, 72, 153, 0.55); width: 5px; height: 5px; }

    .rj-sg2-media {
        position: relative;
        z-index: 2;
        border-radius: 16px;
        overflow: hidden;
        background: #f8f7f4;
        border: 1px solid rgba(255, 255, 255, 0.08);
        aspect-ratio: 1;
        transition: box-shadow 0.5s;
    }
    .rj-sg2-media::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, transparent 45%, rgba(249, 115, 22, 0.18) 100%);
        opacity: 0;
        transition: opacity 0.5s;
        pointer-events: none;
        z-index: 3;
    }

    .rj-sg2-media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        will-change: transform;
    }

    .rj-sg2-media-empty {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #d1d5db;
        font-size: 2.5rem;
    }

    .rj-sg2-media-wrap:hover .rj-sg2-media {
        box-shadow: 0 0 70px -12px rgba(249, 115, 22, 0.5), 0 0 0 1px rgba(249, 115, 22, 0.25);
    }
    .rj-sg2-media-wrap:hover .rj-sg2-media::after { opacity: 1; }
    .rj-sg2-media-wrap:hover .rj-sg2-media img { transform: scale(1.06); }
    .rj-sg2-media-wrap:hover .rj-sg2-particle {
        opacity: 1;
        filter: brightness(1.6) drop-shadow(0 0 6px currentColor);
    }

    .rj-sg2-info {
        display: flex;
        flex-direction: column;
        gap: 1.75rem;
        min-width: 0;
    }

    .rj-sg2-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #f97316;
        margin: 0;
        font-weight: 700;
    }
    .rj-sg2-title {
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        font-weight: 900;
        line-height: 1.08;
        letter-spacing: -0.025em;
        color: #fff;
        margin: 0;
    }
    .rj-sg2-lead {
        font-size: 1.125rem;
        font-weight: 600;
        color: #e5e7eb;
        line-height: 1.4;
        margin: 0;
    }
    .rj-sg2-desc {
        font-size: 0.9375rem;
        color: #9ca3af;
        line-height: 1.65;
        margin: 0;
    }

    .rj-sg2-block {
        display: flex;
        flex-direction: column;
        gap: 0.875rem;
    }
    .rj-sg2-block-title {
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
    .rj-sg2-num {
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

    .rj-sg2-materials {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.625rem;
    }
    @media (max-width: 640px) { .rj-sg2-materials { grid-template-columns: 1fr; } }

    .rj-sg2-mat {
        padding: 0.875rem 0.875rem 1rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.02);
        border: 1.5px solid rgba(255, 255, 255, 0.08);
        text-decoration: none;
        transition: all 0.25s;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .rj-sg2-mat:hover { border-color: rgba(249, 115, 22, 0.5); }
    .rj-sg2-mat.is-active {
        border-color: #f97316;
        background: rgba(249, 115, 22, 0.06);
        box-shadow: 0 0 24px -8px rgba(249, 115, 22, 0.4);
    }
    .rj-sg2-mat-name {
        font-size: 13px;
        color: #e5e7eb;
        font-weight: 600;
        margin: 0;
        line-height: 1.3;
    }
    .rj-sg2-mat-here {
        font-family: ui-monospace, monospace;
        font-size: 10px;
        color: #f97316;
        margin: 0;
        font-weight: 700;
        letter-spacing: 0.1em;
    }
    .rj-sg2-mat-link {
        font-family: ui-monospace, monospace;
        font-size: 10px;
        color: #818cf8;
        margin: 0;
    }

    .rj-sg2-alert {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.5;
    }
    .rj-sg2-alert i { margin-top: 2px; flex-shrink: 0; font-size: 12px; }
    .rj-sg2-alert-ok {
        background: rgba(52, 211, 153, 0.08);
        border: 1px solid rgba(52, 211, 153, 0.25);
        color: #6ee7b7;
    }
    .rj-sg2-alert-ok i { color: #34d399; }
    .rj-sg2-alert-warn {
        background: rgba(251, 191, 36, 0.08);
        border: 1px solid rgba(251, 191, 36, 0.25);
        color: #fcd34d;
    }
    .rj-sg2-alert-warn i { color: #fbbf24; }

    .rj-sg2-measure {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .rj-sg2-select-wrap {
        position: relative;
        flex: 1;
    }
    .rj-sg2-select {
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
    .rj-sg2-select option {
        background: #0a0715;
        color: #fff;
    }
    .rj-sg2-select:hover,
    .rj-sg2-select:focus {
        border-color: rgba(249, 115, 22, 0.5);
        background-color: rgba(249, 115, 22, 0.05);
    }
    .rj-sg2-select:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .rj-sg2-select-wrap > i {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #6b7280;
        font-size: 11px;
    }
    .rj-sg2-x {
        color: #6b7280;
        font-size: 14px;
    }

    .rj-sg2-hint {
        font-size: 12px;
        color: #6b7280;
        margin: 0;
    }
    .rj-sg2-hint-soft { font-style: italic; color: #4b5563; }
    .rj-sg2-hint-ok {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #6ee7b7;
        font-family: ui-monospace, monospace;
        font-size: 12px;
    }
    .rj-sg2-hint-ok i { color: #34d399; }

    .rj-sg2-drop {
        display: block;
        padding: 1.75rem 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1.5px dashed rgba(255, 255, 255, 0.15);
        border-radius: 14px;
        cursor: pointer;
        text-align: center;
        transition: all 0.25s;
    }
    .rj-sg2-drop:hover,
    .rj-sg2-drop.is-drag {
        border-color: #f97316;
        background: rgba(249, 115, 22, 0.06);
    }
    .rj-sg2-drop.has-file {
        border-style: solid;
        border-color: rgba(52, 211, 153, 0.5);
        background: rgba(52, 211, 153, 0.05);
    }
    .rj-sg2-drop input { display: none; }
    .rj-sg2-drop-inner { display: flex; flex-direction: column; align-items: center; gap: 0.5rem; }
    .rj-sg2-drop-inner > i { font-size: 26px; color: #6b7280; }
    .rj-sg2-drop-ok { color: #34d399 !important; }
    .rj-sg2-drop-title { font-size: 13px; color: #e5e7eb; margin: 0; font-weight: 600; }
    .rj-sg2-drop-sub {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        font-family: ui-monospace, monospace;
    }

    .rj-sg2-qty {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1.5px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        overflow: hidden;
        align-self: flex-start;
    }
    .rj-sg2-qty-btn {
        width: 44px;
        height: 44px;
        background: transparent;
        border: none;
        color: #d1d5db;
        font-size: 18px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .rj-sg2-qty-btn:hover { background: rgba(249, 115, 22, 0.15); color: #fff; }
    .rj-sg2-qty-input {
        width: 60px;
        height: 44px;
        background: transparent;
        border: none;
        border-left: 1px solid rgba(255, 255, 255, 0.08);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        color: #fff;
        font-family: ui-monospace, monospace;
        font-size: 15px;
        font-weight: 600;
        text-align: center;
        outline: none;
        -moz-appearance: textfield;
    }
    .rj-sg2-qty-input::-webkit-outer-spin-button,
    .rj-sg2-qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .rj-sg2-notes {
        width: 100%;
        padding: 12px 14px;
        background: rgba(255, 255, 255, 0.02);
        border: 1.5px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: #fff;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        resize: vertical;
        min-height: 76px;
        transition: border-color 0.2s;
    }
    .rj-sg2-notes:focus { border-color: rgba(249, 115, 22, 0.5); }
    .rj-sg2-notes::placeholder { color: #4b5563; }

    .rj-sg2-total {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
    }
    .rj-sg2-total-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        font-size: 13px;
        color: #9ca3af;
        font-family: ui-monospace, monospace;
    }
    .rj-sg2-total-row span:last-child { color: #e5e7eb; font-weight: 600; }
    .rj-sg2-grand {
        padding-top: 0.5rem;
        margin-top: 0.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        font-size: 15px;
    }
    .rj-sg2-grand span:last-child { color: #fff; font-size: 17px; font-weight: 800; }

    .rj-sg2-btn {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.625rem;
        padding: 1rem 1.25rem 1rem 1.75rem;
        background: #f97316;
        color: #fff;
        border: none;
        border-radius: 9999px;
        font-family: inherit;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }
    .rj-sg2-btn:hover:not(:disabled) {
        background: #ea580c;
        box-shadow: 0 0 40px rgba(249, 115, 22, 0.45);
        transform: translateY(-1px);
    }
    .rj-sg2-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .rj-sg2-btn > span:first-of-type { flex: 1; text-align: left; margin-left: 0.5rem; }
    .rj-sg2-btn-price {
        font-family: ui-monospace, monospace;
        font-size: 13px;
        padding: 6px 14px;
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        border-radius: 9999px;
        font-weight: 700;
    }

    .rj-sg2-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.625rem;
        padding: 1rem 1.75rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 9999px;
        color: #fff;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.25s;
    }
    .rj-sg2-btn-outline:hover {
        background: rgba(249, 115, 22, 0.1);
        border-color: rgba(249, 115, 22, 0.4);
    }
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

        onHeightChange() {},

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
            reader.onload = (ev) => { this.fileData = ev.target.result; };
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
            el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#0a0715;color:#fff;padding:12px 24px;border-radius:9999px;border:1px solid rgba(249,115,22,0.4);font-size:13px;font-weight:600;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.6);';
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2200);
        }
    }
}
</script>

@endsection

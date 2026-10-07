@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('meta_keywords', $product->meta_keywords)

@section('content')

@php
    $images = $product->images;
    $firstImage = $images->first();
    $basePrice = $product->sale_price ?: $product->base_price;

    $imgUrl = function ($path) {
        if (!$path) return null;
        return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
    };

    $colorAttr = null;
    $sizeAttr = null;
    foreach ($product->attributeValues as $pav) {
        if (!$pav->attribute) continue;
        if ($pav->attribute->type === 'color' && !$colorAttr) $colorAttr = $pav->attribute;
        if ($pav->attribute->type !== 'color' && !$sizeAttr) $sizeAttr = $pav->attribute;
    }

    $colors = collect();
    if ($colorAttr) {
        $colors = $product->attributeValues
            ->where('attribute_id', $colorAttr->id)
            ->sortBy(fn($pav) => $pav->attributeValue->sort_order ?? 0)
            ->map(function ($pav) {
                $av = $pav->attributeValue;
                return [
                    'id' => $pav->id,
                    'value_id' => $pav->attribute_value_id,
                    'name' => $av->value ?? '',
                    'color_code' => $av->color_code ?? '#000000',
                    'image' => $pav->image_url,
                    'price_override' => $pav->price_override,
                ];
            })
            ->values();
    }

    $sizes = collect();
    if ($sizeAttr) {
        $sizes = $product->attributeValues
            ->where('attribute_id', $sizeAttr->id)
            ->sortBy(fn($pav) => $pav->attributeValue->sort_order ?? 0)
            ->map(function ($pav) {
                $av = $pav->attributeValue;
                return [
                    'id' => $pav->id,
                    'value_id' => $pav->attribute_value_id,
                    'name' => $av->value ?? '',
                    'price_override' => $pav->price_override,
                ];
            })
            ->values();
    }

    $defaultImageUrl = $firstImage ? $firstImage->url : '';
@endphp

<section class="rj-ap-hero"
         x-data="apparelProduct({
            productId: {{ $product->id }},
            basePrice: {{ (float) $basePrice }},
            defaultImage: '{{ $defaultImageUrl }}',
            colors: {{ \Illuminate\Support\Js::from($colors) }},
            sizes: {{ \Illuminate\Support\Js::from($sizes) }}
         })">
    <div class="rj-ap-grid-bg"></div>
    <div class="rj-ap-orb rj-ap-orb-a"></div>
    <div class="rj-ap-orb rj-ap-orb-b"></div>

    <div class="rj-ap-inner">
        <nav class="rj-ap-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            @if($product->model)
                <span>/</span>
                <a href="{{ url('model/' . $product->model->slug) }}">{{ $product->model->name }}</a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="rj-ap-layout">

            <div class="rj-ap-gallery">
                @if($images->count())
                    <div class="rj-ap-thumbs">
                        @foreach($images as $index => $image)
                            <button type="button"
                                    @click="pickThumb({{ $index }}, '{{ $image->url }}')"
                                    :class="activeThumb === {{ $index }} ? 'rj-ap-thumb-active' : ''"
                                    class="rj-ap-thumb">
                                <img src="{{ $image->url }}" alt="{{ $product->name }}">
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="rj-ap-main">
                    <template x-if="displayImage">
                        <img :src="displayImage" :alt="selectedColor ? selectedColor.name : '{{ $product->name }}'" class="rj-ap-img">
                    </template>

                    <template x-if="!displayImage">
                        <div class="rj-ap-img-placeholder">
                            <i class="fa-regular fa-image"></i>
                            <span>No image available</span>
                        </div>
                    </template>

                    <div class="rj-ap-color-badge" x-show="selectedColor && selectedColor.image" x-cloak>
                        <span class="rj-ap-color-badge-dot" :style="'background:' + (selectedColor ? selectedColor.color_code : '#000')"></span>
                        <span x-text="selectedColor ? selectedColor.name : ''"></span>
                    </div>
                </div>
            </div>

            <div class="rj-ap-info">

                @if($product->is_featured)
                    <div class="rj-ap-badge">
                        <span class="rj-ap-badge-dot"></span>
                        <span>Featured</span>
                    </div>
                @endif

                <h1 class="rj-ap-title">{{ $product->name }}</h1>

                <div class="rj-ap-price-row">
                    <span class="rj-ap-price">$<span x-text="finalPrice.toFixed(2)"></span></span>
                    @if($product->sale_price && $product->base_price > $product->sale_price)
                        <span class="rj-ap-price-old">${{ number_format($product->base_price, 2) }}</span>
                        <span class="rj-ap-save">Save ${{ number_format($product->base_price - $product->sale_price, 2) }}</span>
                    @endif
                </div>

                @if($product->short_description)
                    <p class="rj-ap-desc">{{ $product->short_description }}</p>
                @endif

                <div class="rj-ap-features">
                    <span class="rj-ap-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>In stock</span>
                    </span>
                    <span class="rj-ap-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>Ships same day</span>
                    </span>
                    <span class="rj-ap-feature">
                        <i class="fa-solid fa-check"></i>
                        <span>Free shipping $99+</span>
                    </span>
                </div>

                @if($colors->count())
                    <div class="rj-ap-block">
                        <div class="rj-ap-block-head">
                            <label class="rj-ap-block-label">
                                Color
                                <span class="rj-ap-selected" x-text="selectedColor ? selectedColor.name : 'Choose a color'"></span>
                            </label>
                        </div>

                        <div class="rj-ap-colors">
                            <template x-for="color in colors" :key="color.id">
                                <button type="button"
                                        @click="pickColor(color)"
                                        :class="selectedColor && selectedColor.id === color.id ? 'rj-ap-color-active' : ''"
                                        class="rj-ap-color"
                                        :title="color.name">
                                    <span class="rj-ap-color-inner" :style="'background:' + color.color_code"></span>
                                    <i class="fa-solid fa-check rj-ap-color-check" x-show="selectedColor && selectedColor.id === color.id"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                @endif

                @if($sizes->count())
                    <div class="rj-ap-block">
                        <div class="rj-ap-block-head">
                            <label class="rj-ap-block-label">
                                Size
                                <span class="rj-ap-selected" x-text="selectedSize ? selectedSize.name : 'Choose a size'"></span>
                            </label>
                        </div>

                        <div class="rj-ap-sizes">
                            <template x-for="size in sizes" :key="size.id">
                                <button type="button"
                                        @click="pickSize(size)"
                                        :class="selectedSize && selectedSize.id === size.id ? 'rj-ap-size-active' : ''"
                                        class="rj-ap-size">
                                    <span x-text="size.name"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                @endif

                <div class="rj-ap-block">
                    <div class="rj-ap-block-head">
                        <label class="rj-ap-block-label">Quantity</label>
                    </div>

                    <div class="rj-ap-qty">
                        <button type="button" @click="decQty()" class="rj-ap-qty-btn">−</button>
                        <input type="number" x-model.number="qty" min="1" class="rj-ap-qty-input">
                        <button type="button" @click="incQty()" class="rj-ap-qty-btn">+</button>
                    </div>
                </div>

                <div class="rj-ap-actions">
                    <button type="button"
                            @click="addToCart()"
                            :disabled="adding"
                            class="rj-ap-btn rj-ap-btn-primary">
                        <span class="rj-ap-btn-left">
                            <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-bag-shopping'"></i>
                            <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
                        </span>
                        <span class="rj-ap-btn-price">$<span x-text="totalPrice.toFixed(2)"></span></span>
                    </button>
                    <a href="{{ url('contact-us') }}" class="rj-ap-btn rj-ap-btn-outline">
                        <i class="fa-solid fa-comments"></i>
                        <span>Ask a question</span>
                    </a>
                </div>

                <div class="rj-ap-hint">
                    <i class="fa-solid fa-truck-fast"></i>
                    <span>Fast production · Ships from the USA · No minimums</span>
                </div>

            </div>

        </div>
    </div>
</section>

@if($product->description)
<section class="rj-ap-section">
    <div class="rj-ap-grid-bg"></div>
    <div class="rj-ap-inner">
        <div class="rj-ap-desc-content">
            {!! $product->description !!}
        </div>
    </div>
</section>
@endif

<style>
    .rj-ap-hero,
    .rj-ap-section {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }
    .rj-ap-hero { padding: 2rem 0 4rem; }
    @media (min-width: 768px) { .rj-ap-hero { padding: 3rem 0 6rem; } }
    .rj-ap-section { padding: 4rem 0; }

    .rj-ap-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-ap-orb {
        position: absolute; width: 400px; height: 400px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-ap-orb-a { top: 0; left: 20%; background: rgba(99, 102, 241, 0.07); }
    .rj-ap-orb-b { bottom: 0; right: 20%; background: rgba(236, 72, 153, 0.07); }

    .rj-ap-inner {
        position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-ap-inner { padding: 0 3rem; } }

    .rj-ap-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 2rem;
    }
    .rj-ap-breadcrumb a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .rj-ap-breadcrumb a:hover { color: #a5b4fc; }
    .rj-ap-breadcrumb .current { color: #9ca3af; }

    .rj-ap-layout {
        display: grid; grid-template-columns: 1fr; gap: 2rem;
    }
    @media (min-width: 900px) {
        .rj-ap-layout {
            grid-template-columns: minmax(0, 480px) 1fr;
            gap: 3rem;
            align-items: flex-start;
        }
    }

    .rj-ap-gallery {
        display: flex; gap: 0.75rem;
        width: 100%;
        max-width: 480px;
    }

    .rj-ap-thumbs {
        display: flex; flex-direction: column; gap: 0.5rem; flex-shrink: 0;
    }
    .rj-ap-thumb {
        width: 54px; height: 54px; border-radius: 10px; overflow: hidden;
        background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);
        cursor: pointer; transition: all 0.3s ease; padding: 0;
    }
    .rj-ap-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rj-ap-thumb:hover { border-color: rgba(99, 102, 241, 0.4); }
    .rj-ap-thumb-active { border-color: #6366f1 !important; box-shadow: 0 0 16px -4px rgba(99, 102, 241, 0.6); }

    .rj-ap-main {
        flex: 1; border-radius: 16px; overflow: hidden; position: relative;
        background: #f8f7f4;
        border: 1px solid rgba(255, 255, 255, 0.08);
        aspect-ratio: 1;
        display: flex; align-items: center; justify-content: center;
        max-width: 400px;
    }
    .rj-ap-img {
        width: 100%; height: 100%; object-fit: contain; display: block;
    }
    .rj-ap-img-placeholder {
        width: 100%; height: 100%; display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 0.75rem;
        color: #4b5563; font-size: 2rem;
    }
    .rj-ap-img-placeholder span { font-size: 12px; }

    .rj-ap-color-badge {
        position: absolute; top: 12px; left: 12px;
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 6px 12px; background: rgba(5, 3, 15, 0.85);
        backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 9999px;
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.15em; color: #fff;
    }
    .rj-ap-color-badge-dot {
        width: 8px; height: 8px; border-radius: 50%;
        box-shadow: 0 0 8px 1px rgba(255, 255, 255, 0.3);
    }

    .rj-ap-info { display: flex; flex-direction: column; gap: 1.25rem; min-width: 0; }

    .rj-ap-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 4px 12px; background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 9999px;
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.2em; color: #fbbf24;
        align-self: flex-start;
    }
    .rj-ap-badge-dot {
        width: 5px; height: 5px; background: #fbbf24; border-radius: 50%;
        box-shadow: 0 0 6px 2px rgba(251, 191, 36, 0.8);
    }

    .rj-ap-title {
        font-size: clamp(1.5rem, 3vw, 2.25rem);
        font-weight: 900; line-height: 1.15; letter-spacing: -0.02em;
        color: #fff; margin: 0;
    }

    .rj-ap-price-row { display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap; }
    .rj-ap-price {
        font-size: 1.75rem; font-weight: 900; color: #fff;
        font-family: ui-monospace, monospace;
    }
    .rj-ap-price-old {
        font-size: 1rem; color: #6b7280; text-decoration: line-through;
        font-family: ui-monospace, monospace;
    }
    .rj-ap-save {
        padding: 3px 10px; background: rgba(52, 211, 153, 0.15);
        border: 1px solid rgba(52, 211, 153, 0.3); border-radius: 6px;
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.15em; color: #34d399;
    }

    .rj-ap-desc {
        font-size: 0.9375rem; color: #9ca3af; line-height: 1.7;
        margin: 0; font-weight: 300;
    }

    .rj-ap-features {
        display: flex; flex-wrap: wrap; gap: 0.75rem 1.5rem;
        padding: 0.875rem 0; border-top: 1px solid rgba(255, 255, 255, 0.06);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .rj-ap-feature {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 12px; color: #d1d5db;
    }
    .rj-ap-feature i {
        font-size: 9px; color: #34d399;
        background: rgba(52, 211, 153, 0.15); border-radius: 50%;
        width: 16px; height: 16px;
        display: flex; align-items: center; justify-content: center;
    }

    .rj-ap-block { display: flex; flex-direction: column; gap: 0.625rem; }
    .rj-ap-block-head { display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; }
    .rj-ap-block-label {
        font-size: 11px; font-weight: 600; color: #e5e7eb;
        text-transform: uppercase; letter-spacing: 0.15em;
        font-family: ui-monospace, monospace;
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .rj-ap-selected {
        color: #6b7280; font-weight: 400; text-transform: none;
        letter-spacing: normal; font-family: inherit; font-size: 12px;
    }

    .rj-ap-colors { display: flex; flex-wrap: wrap; gap: 0.625rem; }
    .rj-ap-color {
        position: relative; width: 42px; height: 42px; border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        cursor: pointer; padding: 0;
        transition: all 0.3s ease;
        display: flex; align-items: center; justify-content: center;
    }
    .rj-ap-color:hover {
        transform: scale(1.08);
        border-color: rgba(255, 255, 255, 0.3);
    }
    .rj-ap-color-inner {
        width: 32px; height: 32px; border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: inset 0 0 8px rgba(0, 0, 0, 0.2);
    }
    .rj-ap-color-active {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25), 0 0 20px -4px rgba(99, 102, 241, 0.5);
        transform: scale(1.05);
    }
    .rj-ap-color-check {
        position: absolute; top: -2px; right: -2px;
        width: 16px; height: 16px; border-radius: 50%;
        background: #6366f1; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 8px; border: 2px solid #05030f;
    }

    .rj-ap-sizes { display: flex; flex-wrap: wrap; gap: 0.5rem; }
    .rj-ap-size {
        min-width: 52px; padding: 0.625rem 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px; color: #d1d5db;
        font-family: ui-monospace, monospace; font-size: 13px;
        font-weight: 600; text-transform: uppercase;
        letter-spacing: 0.05em; cursor: pointer;
        transition: all 0.3s ease;
    }
    .rj-ap-size:hover {
        border-color: rgba(99, 102, 241, 0.5);
        background: rgba(99, 102, 241, 0.08);
        color: #fff;
    }
    .rj-ap-size-active {
        background: linear-gradient(135deg, #6366f1, #a855f7) !important;
        border-color: transparent !important;
        color: #fff !important;
        box-shadow: 0 0 20px -4px rgba(99, 102, 241, 0.6);
    }

    .rj-ap-qty {
        display: inline-flex; align-items: center;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px; overflow: hidden;
        align-self: flex-start;
    }
    .rj-ap-qty-btn {
        width: 44px; height: 44px;
        background: transparent; border: none; color: #d1d5db;
        font-size: 18px; font-weight: 600; cursor: pointer;
        transition: all 0.2s ease;
    }
    .rj-ap-qty-btn:hover {
        background: rgba(99, 102, 241, 0.15); color: #fff;
    }
    .rj-ap-qty-input {
        width: 60px; height: 44px; background: transparent;
        border: none; border-left: 1px solid rgba(255, 255, 255, 0.08);
        border-right: 1px solid rgba(255, 255, 255, 0.08);
        color: #fff; font-family: ui-monospace, monospace;
        font-size: 15px; font-weight: 600; text-align: center;
        outline: none;
        -moz-appearance: textfield;
    }
    .rj-ap-qty-input::-webkit-outer-spin-button,
    .rj-ap-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none; margin: 0;
    }

    .rj-ap-actions { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; }

    .rj-ap-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem; font-weight: 600; font-size: 0.9375rem;
        padding: 1rem 1.75rem; border-radius: 9999px;
        text-decoration: none; transition: all 0.3s ease;
        cursor: pointer; border: none; font-family: inherit;
    }
    .rj-ap-btn i { font-size: 12px; }
    .rj-ap-btn-primary {
        background: #fff; color: #05030f;
        justify-content: space-between;
        padding-left: 1.5rem; padding-right: 1rem;
    }
    .rj-ap-btn-primary:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.4);
        transform: translateY(-1px);
    }
    .rj-ap-btn-primary:disabled {
        opacity: 0.7; cursor: wait; transform: none;
    }
    .rj-ap-btn-left {
        display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .rj-ap-btn-price {
        font-family: ui-monospace, monospace; font-size: 13px;
        padding: 5px 12px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        border-radius: 9999px;
        font-weight: 700;
    }
    .rj-ap-btn-outline {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .rj-ap-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .rj-ap-hint {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 12px; color: #6b7280;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        margin-top: 0.25rem;
    }
    .rj-ap-hint i { color: #818cf8; font-size: 11px; }

    .rj-ap-desc-content {
        color: #d1d5db; font-size: 1rem; line-height: 1.85;
        max-width: 48rem;
    }
    .rj-ap-desc-content h1,
    .rj-ap-desc-content h2,
    .rj-ap-desc-content h3 { color: #fff; font-weight: 800; letter-spacing: -0.02em; margin: 2rem 0 1rem; }
    .rj-ap-desc-content h1 { font-size: 1.75rem; }
    .rj-ap-desc-content h2 { font-size: 1.5rem; }
    .rj-ap-desc-content h3 { font-size: 1.25rem; }
    .rj-ap-desc-content p { margin-bottom: 1rem; color: #9ca3af; }
    .rj-ap-desc-content strong { color: #fff; }
    .rj-ap-desc-content a { color: #818cf8; text-decoration: underline; }
    .rj-ap-desc-content ul, .rj-ap-desc-content ol { padding-left: 1.5rem; margin-bottom: 1rem; color: #9ca3af; }
    .rj-ap-desc-content ul { list-style: disc; }
    .rj-ap-desc-content ol { list-style: decimal; }
    .rj-ap-desc-content img { border-radius: 12px; margin: 1.5rem 0; max-width: 100%; }
</style>

<script>
function apparelProduct(config) {
    return {
        productId: config.productId,
        basePrice: config.basePrice,
        defaultImage: config.defaultImage || '',
        colors: config.colors || [],
        sizes: config.sizes || [],

        activeThumb: 0,
        thumbImage: config.defaultImage || '',
        selectedColor: null,
        selectedSize: null,
        qty: 1,
        adding: false,

        get displayImage() {
            if (this.selectedColor && this.selectedColor.image) {
                return this.selectedColor.image;
            }
            return this.thumbImage || this.defaultImage || '';
        },

        get finalPrice() {
            let price = this.basePrice;
            if (this.selectedColor && this.selectedColor.price_override !== null && this.selectedColor.price_override !== undefined) {
                price = parseFloat(this.selectedColor.price_override);
            } else if (this.selectedSize && this.selectedSize.price_override !== null && this.selectedSize.price_override !== undefined) {
                price = parseFloat(this.selectedSize.price_override);
            }
            return price;
        },

        get totalPrice() {
            return this.finalPrice * (this.qty || 1);
        },

        pickThumb(index, url) {
            this.selectedColor = null;
            this.activeThumb = index;
            this.thumbImage = url;
        },

        pickColor(color) {
            if (this.selectedColor && this.selectedColor.id === color.id) {
                this.selectedColor = null;
                return;
            }
            this.selectedColor = color;
        },

        pickSize(size) {
            if (this.selectedSize && this.selectedSize.id === size.id) {
                this.selectedSize = null;
                return;
            }
            this.selectedSize = size;
        },

        incQty() {
            this.qty = Math.min(999, (this.qty || 1) + 1);
        },

        decQty() {
            this.qty = Math.max(1, (this.qty || 1) - 1);
        },

        async addToCart() {
            if (this.adding) return;

            if (this.colors.length && !this.selectedColor) {
                this.flash('Please choose a color');
                return;
            }
            if (this.sizes.length && !this.selectedSize) {
                this.flash('Please choose a size');
                return;
            }

            this.adding = true;

            const attributes = {};
            if (this.selectedColor) attributes['Color'] = this.selectedColor.name;
            if (this.selectedSize) attributes['Size'] = this.selectedSize.name;

            const payload = {
                product_id: this.productId,
                unit_price: this.finalPrice,
                qty: this.qty,
                attributes: attributes,
                print_type: 'apparel'
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

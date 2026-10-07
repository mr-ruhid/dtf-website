@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('meta_keywords', $product->meta_keywords)

@section('content')

@php
    $images = $product->images;
    $options = $product->options->where('status', 1);
    $prices = $product->prices;
    $basePrice = $product->sale_price ?: $product->base_price;
    $breadcrumbParent = $product->model;
@endphp

<section class="rj-sp-hero">
    <div class="rj-sp-grid-bg"></div>
    <div class="rj-sp-orb rj-sp-orb-a"></div>
    <div class="rj-sp-orb rj-sp-orb-b"></div>

    <div class="rj-sp-inner">
        <nav class="rj-sp-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            @if($breadcrumbParent)
                <span>/</span>
                <a href="{{ url('model/' . $breadcrumbParent->slug) }}">{{ $breadcrumbParent->name }}</a>
            @endif
            <span>/</span>
            <span class="current">{{ $product->name }}</span>
        </nav>

        <div class="rj-sp-layout">

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

            <div class="rj-sp-info">

                @if($product->is_featured)
                    <div class="rj-sp-badge">
                        <span class="rj-sp-badge-dot"></span>
                        <span>Featured Product</span>
                    </div>
                @endif

                <h1 class="rj-sp-title">{{ $product->name }}</h1>

                <div class="rj-sp-price-row">
                    <span class="rj-sp-price">${{ number_format($basePrice, 2) }}</span>
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
                    <div class="rj-sp-tiers">
                        <div class="rj-sp-tiers-label">// Quantity Pricing</div>
                        <div class="rj-sp-tiers-grid">
                            @foreach($prices as $tier)
                                <div class="rj-sp-tier">
                                    <span class="rj-sp-tier-qty">
                                        {{ $tier->min_qty }}@if($tier->max_qty)–{{ $tier->max_qty }}@else+@endif
                                    </span>
                                    <span class="rj-sp-tier-price">${{ number_format($tier->price, 2) }}</span>
                                    <span class="rj-sp-tier-unit">each</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($options->count())
                    <div class="rj-sp-options" x-data="{ selections: {} }">
                        @foreach($options as $option)
                            <div class="rj-sp-option">
                                <label class="rj-sp-option-label">
                                    {{ $option->name }}
                                    @if($option->is_required)
                                        <span class="rj-sp-req">*</span>
                                    @endif
                                </label>

                                @if($option->type === 'select')
                                    <div class="rj-sp-select-wrap">
                                        <select class="rj-sp-select" x-model="selections[{{ $option->id }}]">
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
                                           x-model="selections[{{ $option->id }}]">
                                @elseif($option->type === 'number')
                                    <input type="number" class="rj-sp-input"
                                           placeholder="0"
                                           x-model="selections[{{ $option->id }}]">
                                @elseif($option->type === 'measurement')
                                    <div class="rj-sp-measure">
                                        <input type="number" class="rj-sp-input" placeholder="Width">
                                        <span class="rj-sp-measure-sep">×</span>
                                        <input type="number" class="rj-sp-input" placeholder="Height">
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="rj-sp-actions">
                    <a href="{{ url('design') }}" class="rj-sp-btn rj-sp-btn-primary">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Start your order</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ url('contact-us') }}" class="rj-sp-btn rj-sp-btn-outline">
                        <i class="fa-solid fa-comments"></i>
                        <span>Ask a question</span>
                    </a>
                </div>

                <div class="rj-sp-upload-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Need help with this product? <a href="{{ url('contact-us') }}">Contact support →</a></span>
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

<style>
    .rj-sp-hero,
    .rj-sp-section {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }
    .rj-sp-hero { padding: 2rem 0 4rem; }
    @media (min-width: 768px) { .rj-sp-hero { padding: 3rem 0 6rem; } }
    .rj-sp-section { padding: 4rem 0; }
    @media (min-width: 768px) { .rj-sp-section { padding: 6rem 0; } }

    .rj-sp-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-sp-orb {
        position: absolute; width: 400px; height: 400px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-sp-orb-a { top: 0; left: 20%; background: rgba(99, 102, 241, 0.07); }
    .rj-sp-orb-b { bottom: 0; right: 20%; background: rgba(236, 72, 153, 0.07); }

    .rj-sp-inner {
        position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sp-inner { padding: 0 3rem; } }

    .rj-sp-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 2rem;
    }
    .rj-sp-breadcrumb a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .rj-sp-breadcrumb a:hover { color: #a5b4fc; }
    .rj-sp-breadcrumb .current { color: #9ca3af; }

    .rj-sp-layout {
        display: grid; grid-template-columns: 1fr; gap: 2rem;
    }
    @media (min-width: 900px) {
        .rj-sp-layout { grid-template-columns: 1fr 1fr; gap: 3rem; }
    }

    .rj-sp-gallery { display: flex; gap: 1rem; }
    .rj-sp-thumbs {
        display: flex; flex-direction: column; gap: 0.5rem; flex-shrink: 0;
    }
    .rj-sp-thumb {
        width: 60px; height: 60px; border-radius: 10px; overflow: hidden;
        background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.08);
        cursor: pointer; transition: all 0.3s ease; padding: 0;
    }
    .rj-sp-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rj-sp-thumb:hover { border-color: rgba(99, 102, 241, 0.4); }
    .rj-sp-thumb-active { border-color: #6366f1 !important; box-shadow: 0 0 16px -4px rgba(99, 102, 241, 0.6); }
    .rj-sp-thumb-placeholder {
        display: flex; align-items: center; justify-content: center;
        color: #4b5563; font-size: 1.25rem;
    }

    .rj-sp-main-img {
        flex: 1; border-radius: 16px; overflow: hidden;
        background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08);
        aspect-ratio: 1; position: relative;
    }
    .rj-sp-img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .rj-sp-img-placeholder {
        width: 100%; height: 100%; display: flex; flex-direction: column;
        align-items: center; justify-content: center; gap: 0.75rem;
        color: #4b5563; font-size: 2rem;
    }
    .rj-sp-img-placeholder span { font-size: 12px; }

    .rj-sp-info { display: flex; flex-direction: column; gap: 1.25rem; }

    .rj-sp-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 4px 12px; background: rgba(251, 191, 36, 0.1);
        border: 1px solid rgba(251, 191, 36, 0.3); border-radius: 9999px;
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.2em; color: #fbbf24;
        align-self: flex-start;
    }
    .rj-sp-badge-dot {
        width: 5px; height: 5px; background: #fbbf24; border-radius: 50%;
        box-shadow: 0 0 6px 2px rgba(251, 191, 36, 0.8);
    }

    .rj-sp-title {
        font-size: clamp(1.75rem, 3.5vw, 2.75rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0;
    }

    .rj-sp-price-row { display: flex; align-items: baseline; gap: 0.75rem; flex-wrap: wrap; }
    .rj-sp-price {
        font-size: 2rem; font-weight: 900; color: #fff;
        font-family: ui-monospace, monospace;
    }
    .rj-sp-price-old {
        font-size: 1.125rem; color: #6b7280; text-decoration: line-through;
        font-family: ui-monospace, monospace;
    }
    .rj-sp-save {
        padding: 3px 10px; background: rgba(52, 211, 153, 0.15);
        border: 1px solid rgba(52, 211, 153, 0.3); border-radius: 6px;
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.15em; color: #34d399;
    }

    .rj-sp-desc {
        font-size: 1rem; color: #9ca3af; line-height: 1.7;
        margin: 0; font-weight: 300;
    }

    .rj-sp-features {
        display: flex; flex-wrap: wrap; gap: 0.75rem 1.5rem;
        padding: 1rem 0; border-top: 1px solid rgba(255, 255, 255, 0.06);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }
    .rj-sp-feature {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-size: 12.5px; color: #d1d5db;
    }
    .rj-sp-feature i {
        font-size: 9px; color: #34d399;
        background: rgba(52, 211, 153, 0.15); border-radius: 50%;
        width: 16px; height: 16px;
        display: flex; align-items: center; justify-content: center;
    }

    .rj-sp-tiers-label {
        font-family: ui-monospace, monospace; font-size: 10px;
        text-transform: uppercase; letter-spacing: 0.3em;
        color: #818cf8; margin-bottom: 0.75rem;
    }
    .rj-sp-tiers-grid {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 0.5rem;
    }
    .rj-sp-tier {
        padding: 0.75rem; background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.07); border-radius: 10px;
        display: flex; flex-direction: column; gap: 2px;
        transition: all 0.3s ease;
    }
    .rj-sp-tier:hover {
        border-color: rgba(99, 102, 241, 0.4);
        background: rgba(99, 102, 241, 0.05);
    }
    .rj-sp-tier-qty {
        font-family: ui-monospace, monospace; font-size: 11px;
        color: #9ca3af; letter-spacing: 0.05em;
    }
    .rj-sp-tier-price {
        font-family: ui-monospace, monospace; font-size: 16px;
        font-weight: 700; color: #fff;
    }
    .rj-sp-tier-unit {
        font-family: ui-monospace, monospace; font-size: 9px;
        color: #4b5563; text-transform: uppercase; letter-spacing: 0.1em;
    }

    .rj-sp-options { display: flex; flex-direction: column; gap: 1rem; }
    .rj-sp-option { display: flex; flex-direction: column; gap: 0.5rem; }
    .rj-sp-option-label {
        font-size: 12px; font-weight: 600; color: #e5e7eb;
        text-transform: uppercase; letter-spacing: 0.1em;
        font-family: ui-monospace, monospace;
    }
    .rj-sp-req { color: #f472b6; margin-left: 0.25rem; }

    .rj-sp-select-wrap { position: relative; }
    .rj-sp-select {
        width: 100%; padding: 0.875rem 2.5rem 0.875rem 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px; font-size: 14px; color: #fff;
        font-family: inherit; cursor: pointer;
        appearance: none; -webkit-appearance: none;
        outline: none; transition: all 0.3s ease;
    }
    .rj-sp-select:focus {
        border-color: rgba(99, 102, 241, 0.6);
        background: rgba(99, 102, 241, 0.05);
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.25);
    }
    .rj-sp-select option { background: #0a0715; color: #fff; }
    .rj-sp-select-icon {
        position: absolute; right: 1rem; top: 50%;
        transform: translateY(-50%); color: #6b7280;
        font-size: 11px; pointer-events: none;
    }

    .rj-sp-input {
        width: 100%; padding: 0.875rem 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px; font-size: 14px; color: #fff;
        font-family: inherit; outline: none; transition: all 0.3s ease;
    }
    .rj-sp-input::placeholder { color: #4b5563; }
    .rj-sp-input:focus {
        border-color: rgba(99, 102, 241, 0.6);
        background: rgba(99, 102, 241, 0.05);
    }

    .rj-sp-measure { display: flex; align-items: center; gap: 0.5rem; }
    .rj-sp-measure-sep { color: #6b7280; font-family: ui-monospace, monospace; }

    .rj-sp-actions { display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; }

    .rj-sp-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem; font-weight: 600; font-size: 0.9375rem;
        padding: 1rem 1.75rem; border-radius: 9999px;
        text-decoration: none; transition: all 0.3s ease;
        cursor: pointer; border: none; font-family: inherit;
    }
    .rj-sp-btn i { font-size: 12px; }
    .rj-sp-btn-primary {
        background: #fff; color: #05030f;
    }
    .rj-sp-btn-primary:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.4);
        transform: translateY(-1px);
    }
    .rj-sp-btn-primary i:last-child { transition: transform 0.3s; }
    .rj-sp-btn-primary:hover i:last-child { transform: translateX(4px); }
    .rj-sp-btn-outline {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .rj-sp-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .rj-sp-upload-hint {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 12px; color: #6b7280;
        padding: 0.75rem 1rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px dashed rgba(255, 255, 255, 0.08);
        border-radius: 10px;
    }
    .rj-sp-upload-hint i { color: #818cf8; font-size: 11px; }
    .rj-sp-upload-hint a { color: #818cf8; text-decoration: none; font-weight: 500; }
    .rj-sp-upload-hint a:hover { color: #c7d2fe; text-decoration: underline; }

    .rj-sp-steps-head { margin-bottom: 3rem; max-width: 40rem; }
    .rj-sp-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .rj-sp-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-sp-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }
    .rj-sp-h2 {
        font-size: clamp(1.5rem, 3vw, 2.25rem); font-weight: 900;
        line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 0.75rem;
    }
    .rj-sp-sub { font-size: 0.9375rem; color: #6b7280; line-height: 1.7; margin: 0; }

    .rj-sp-steps {
        display: grid; grid-template-columns: 1fr; gap: 1.5rem;
    }
    @media (min-width: 640px) { .rj-sp-steps { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .rj-sp-steps { grid-template-columns: repeat(4, 1fr); gap: 1rem; } }

    .rj-sp-step {
        padding: 1.5rem 1.25rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        transition: all 0.4s ease;
    }
    .rj-sp-step:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-3px);
    }
    .rj-sp-step-num {
        font-family: ui-monospace, monospace; font-size: 11px;
        color: #818cf8; letter-spacing: 0.15em; margin-bottom: 0.75rem;
    }
    .rj-sp-step-line {
        width: 32px; height: 2px; background: linear-gradient(90deg, #6366f1, #ec4899);
        border-radius: 2px; margin-bottom: 1rem;
    }
    .rj-sp-step-title {
        font-size: 1.25rem; font-weight: 800; color: #fff;
        letter-spacing: -0.01em; margin: 0 0 0.5rem;
    }
    .rj-sp-step-text { font-size: 13px; color: #9ca3af; line-height: 1.6; margin: 0; }

    .rj-sp-desc-content {
        color: #d1d5db; font-size: 1rem; line-height: 1.85;
    }
    .rj-sp-desc-content h1,
    .rj-sp-desc-content h2,
    .rj-sp-desc-content h3 { color: #fff; font-weight: 800; letter-spacing: -0.02em; margin: 2rem 0 1rem; }
    .rj-sp-desc-content h1 { font-size: 1.75rem; }
    .rj-sp-desc-content h2 { font-size: 1.5rem; }
    .rj-sp-desc-content h3 { font-size: 1.25rem; }
    .rj-sp-desc-content p { margin-bottom: 1rem; color: #9ca3af; }
    .rj-sp-desc-content strong { color: #fff; }
    .rj-sp-desc-content a { color: #818cf8; text-decoration: underline; }
    .rj-sp-desc-content ul, .rj-sp-desc-content ol { padding-left: 1.5rem; margin-bottom: 1rem; color: #9ca3af; }
    .rj-sp-desc-content ul { list-style: disc; }
    .rj-sp-desc-content ol { list-style: decimal; }
    .rj-sp-desc-content img { border-radius: 12px; margin: 1.5rem 0; max-width: 100%; }

    .rj-prod-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;
    }
    @media (min-width: 768px) { .rj-prod-grid { grid-template-columns: repeat(3, 1fr); gap: 1.25rem; } }
    @media (min-width: 1024px) { .rj-prod-grid { grid-template-columns: repeat(4, 1fr); } }

    .rj-sp-faq-list { display: flex; flex-direction: column; gap: 0.75rem; }
    .rj-sp-faq {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px; overflow: hidden;
        transition: all 0.3s ease;
    }
    .rj-sp-faq:hover { border-color: rgba(255, 255, 255, 0.12); }
    .rj-sp-faq-open {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 0 30px -8px rgba(99, 102, 241, 0.4);
    }
    .rj-sp-faq-q {
        width: 100%; text-align: left; padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        cursor: pointer; background: transparent; border: none;
        color: inherit; font-family: inherit;
    }
    .rj-sp-faq-num {
        font-family: ui-monospace, monospace; font-size: 11px;
        font-weight: 700; color: rgba(129, 140, 248, 0.8);
        letter-spacing: 0.1em; width: 2rem; flex-shrink: 0;
    }
    .rj-sp-faq-qtext {
        flex: 1; font-size: 15px; font-weight: 600; color: #fff;
        line-height: 1.4; padding-right: 0.5rem;
    }
    .rj-sp-faq-icon {
        width: 2rem; height: 2rem; border-radius: 50%;
        background: rgba(255, 255, 255, 0.04);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background 0.3s ease;
    }
    .rj-sp-faq-q:hover .rj-sp-faq-icon { background: rgba(99, 102, 241, 0.15); }
    .rj-sp-faq-icon i {
        font-size: 11px; color: #9ca3af;
        transition: all 0.3s ease;
    }
    .rj-sp-faq-q:hover .rj-sp-faq-icon i { color: #a5b4fc; }
    .rj-sp-faq-icon-rot { transform: rotate(45deg); color: #a5b4fc !important; }
    .rj-sp-faq-a {
        padding: 0 1.5rem 1.5rem 3.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.25rem; font-size: 14px; line-height: 1.7;
        color: #9ca3af;
    }
    .rj-sp-faq-a p { margin-bottom: 0.75rem; }
    .rj-sp-faq-a p:last-child { margin-bottom: 0; }
    .rj-sp-faq-a strong { color: #fff; font-weight: 600; }
    .rj-sp-faq-a a { color: #818cf8; text-decoration: underline; }

    .rj-sp-faq-more {
        margin-top: 2.5rem; display: flex; justify-content: center;
    }
</style>

@endsection

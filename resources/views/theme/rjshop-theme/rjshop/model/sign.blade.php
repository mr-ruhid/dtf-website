@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $model->meta_title ?: $model->name)
@section('meta_description', $model->meta_description ?: $model->description)
@section('meta_keywords', $model->meta_keywords)

@section('content')

@php
    $hero = \App\Models\Widget::where('key', 'sign-hero')->where('is_active', 1)->first();
    $heroSettings = $hero ? ($hero->settings ?? []) : [];

    $imgUrl = function ($path) {
        if (!$path) return null;
        return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
    };

    $products = $model->products()
        ->where('status', 1)
        ->with('images')
        ->orderBy('sort_order')
        ->get();
@endphp

@if(!empty($heroSettings))
<section class="rj-sgn-hero">
    <div class="rj-sgn-bg"></div>
    <div class="rj-sgn-inner">
        <div class="rj-sgn-hero-grid">

            <div class="rj-sgn-hero-text">
                @if(!empty($heroSettings['eyebrow']))
                    <p class="rj-sgn-eyebrow">{{ $heroSettings['eyebrow'] }}</p>
                @endif

                @if(!empty($heroSettings['title']))
                    <h1 class="rj-sgn-title">{!! nl2br(e($heroSettings['title'])) !!}</h1>
                @endif

                @if(!empty($heroSettings['subtitle']))
                    <p class="rj-sgn-sub">{!! nl2br(e($heroSettings['subtitle'])) !!}</p>
                @endif

                @if(!empty($heroSettings['step1_text']) || !empty($heroSettings['step2_text']) || !empty($heroSettings['step3_text']))
                    <div class="rj-sgn-steps">
                        @foreach(['step1_text', 'step2_text', 'step3_text'] as $i => $key)
                            @if(!empty($heroSettings[$key]))
                                <div class="rj-sgn-step">
                                    <span class="rj-sgn-step-num">{{ $i + 1 }}</span>
                                    <span>{{ $heroSettings[$key] }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rj-sgn-hero-images">
                @if(!empty($heroSettings['main_image']))
                    <div class="rj-sgn-img-main">
                        <img src="{{ $imgUrl($heroSettings['main_image']) }}" alt="">
                    </div>
                @endif

                <div class="rj-sgn-img-sm-col">
                    @if(!empty($heroSettings['image_2']))
                        <div class="rj-sgn-img-sm">
                            <img src="{{ $imgUrl($heroSettings['image_2']) }}" alt="">
                        </div>
                    @endif
                    @if(!empty($heroSettings['image_3']))
                        <div class="rj-sgn-img-sm">
                            <img src="{{ $imgUrl($heroSettings['image_3']) }}" alt="">
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>
@endif

@if($products->count())
<section class="rj-sgn-products">
    <div class="rj-sgn-inner">
        <div class="rj-sgn-head">
            <p class="rj-sgn-head-eyebrow">THE THREE WE PRINT</p>
            <h2 class="rj-sgn-head-title">What each one is for.</h2>
        </div>

        <div class="rj-sgn-grid">
            @foreach($products as $product)
                @php
                    $img = $product->images->first();
                    $imgSrc = $img ? $img->url : null;
                    $buttonLabel = $product->name;
                    $short = $product->short_description;
                    $full = $product->description;
                @endphp
                <div class="rj-sgn-card">
                    <div class="rj-sgn-card-img">
                        @if($imgSrc)
                            <img src="{{ $imgSrc }}" alt="{{ $product->name }}">
                        @else
                            <div class="rj-sgn-card-img-empty">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif
                    </div>

                    <div class="rj-sgn-card-body">
                        <h3 class="rj-sgn-card-title">{{ $product->name }}</h3>

                        @if($short)
                            <p class="rj-sgn-card-lead">{{ $short }}</p>
                        @endif

                        @if($full)
                            <p class="rj-sgn-card-desc">{{ strip_tags($full) }}</p>
                        @endif

                        <a href="{{ url('product/' . $product->slug) }}" class="rj-sgn-card-btn">
                            <span>Order {{ \Illuminate\Support\Str::lower($product->name) }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($model->description)
<section class="rj-sgn-about">
    <div class="rj-sgn-inner">
        <div class="rj-sgn-about-content">
            {!! $model->description !!}
        </div>
    </div>
</section>
@endif

<style>
    .rj-sgn-hero,
    .rj-sgn-products,
    .rj-sgn-about {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }
    .rj-sgn-hero { padding: 3rem 0 4rem; }
    .rj-sgn-products { padding: 3rem 0 5rem; }
    .rj-sgn-about { padding: 3rem 0 5rem; }

    .rj-sgn-bg {
        position: absolute; inset: 0; opacity: 0.03; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-sgn-inner {
        position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sgn-inner { padding: 0 3rem; } }

    .rj-sgn-hero-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 3rem;
        align-items: center;
    }
    @media (min-width: 900px) {
        .rj-sgn-hero-grid { grid-template-columns: 1fr 1.1fr; gap: 4rem; }
    }

    .rj-sgn-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #f97316;
        margin: 0 0 1rem;
        font-weight: 700;
    }
    .rj-sgn-title {
        font-size: clamp(2rem, 4.5vw, 3.5rem);
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -0.03em;
        color: #fff;
        margin: 0 0 1.25rem;
    }
    .rj-sgn-sub {
        font-size: 1.0625rem;
        color: #d1d5db;
        line-height: 1.6;
        margin: 0 0 2rem;
        max-width: 32rem;
    }

    .rj-sgn-steps {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }
    .rj-sgn-step {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        font-size: 13px;
        color: #9ca3af;
    }
    .rj-sgn-step-num {
        width: 22px; height: 22px;
        border-radius: 50%;
        background: #f97316;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-family: ui-monospace, monospace;
    }

    .rj-sgn-hero-images {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        grid-template-rows: 1fr;
        gap: 0.75rem;
        aspect-ratio: 4 / 3;
    }
    .rj-sgn-img-main {
        border-radius: 16px;
        overflow: hidden;
        background: #0a0715;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }
    .rj-sgn-img-main img {
        width: 100%; height: 100%; object-fit: cover; display: block;
    }
    .rj-sgn-img-sm-col {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .rj-sgn-img-sm {
        flex: 1;
        border-radius: 16px;
        overflow: hidden;
        background: #0a0715;
        border: 1px solid rgba(255, 255, 255, 0.08);
        min-height: 0;
    }
    .rj-sgn-img-sm img {
        width: 100%; height: 100%; object-fit: cover; display: block;
    }

    .rj-sgn-head {
        margin-bottom: 2.5rem;
        max-width: 48rem;
    }
    .rj-sgn-head-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #f97316;
        margin: 0 0 0.75rem;
        font-weight: 700;
    }
    .rj-sgn-head-title {
        font-size: clamp(1.75rem, 3vw, 2.5rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 0;
    }

    .rj-sgn-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @media (min-width: 700px) { .rj-sgn-grid { grid-template-columns: repeat(3, 1fr); } }

    .rj-sgn-card {
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 18px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sgn-card:hover {
        transform: translateY(-4px);
        border-color: rgba(249, 115, 22, 0.4);
        box-shadow: 0 20px 40px -20px rgba(249, 115, 22, 0.35);
    }

    .rj-sgn-card-img {
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: #0a0715;
        position: relative;
    }
    .rj-sgn-card-img img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sgn-card:hover .rj-sgn-card-img img { transform: scale(1.05); }
    .rj-sgn-card-img-empty {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255, 255, 255, 0.06);
        font-size: 2.5rem;
    }

    .rj-sgn-card-body {
        padding: 1.5rem 1.5rem 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        flex: 1;
    }
    .rj-sgn-card-title {
        font-size: 1.375rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.2;
    }
    .rj-sgn-card-lead {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #e5e7eb;
        line-height: 1.45;
        margin: 0;
    }
    .rj-sgn-card-desc {
        font-size: 0.8125rem;
        color: #6b7280;
        line-height: 1.6;
        margin: 0;
    }

    .rj-sgn-card-btn {
        margin-top: auto;
        padding-top: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        padding: 0.875rem 1.25rem;
        background: #f97316;
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.3s;
        margin-top: 1rem;
    }
    .rj-sgn-card-btn:hover {
        background: #ea580c;
        box-shadow: 0 0 30px -8px rgba(249, 115, 22, 0.6);
        transform: translateY(-1px);
    }
    .rj-sgn-card-btn i { font-size: 11px; }

    .rj-sgn-about-content {
        color: #d1d5db;
        font-size: 1rem;
        line-height: 1.85;
        max-width: 48rem;
    }
    .rj-sgn-about-content h1,
    .rj-sgn-about-content h2,
    .rj-sgn-about-content h3 { color: #fff; font-weight: 800; letter-spacing: -0.02em; margin: 2rem 0 1rem; }
    .rj-sgn-about-content p { margin-bottom: 1rem; color: #9ca3af; }
</style>

@endsection

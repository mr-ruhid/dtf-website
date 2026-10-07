@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $model->meta_title ?: $model->name)
@section('meta_description', $model->meta_description ?: $model->description)
@section('meta_keywords', $model->meta_keywords)

@section('content')

<section class="rj-grid-hero">
    <div class="rj-grid-bg"></div>
    <div class="rj-grid-orb rj-grid-orb-a"></div>
    <div class="rj-grid-orb rj-grid-orb-b"></div>

    <div class="rj-grid-inner">
        <nav class="rj-grid-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span class="current">{{ $model->name }}</span>
        </nav>

        @if($model->icon)
            <div class="rj-grid-icon">
                <i class="fa-solid {{ $model->icon }}"></i>
            </div>
        @endif

        <h1 class="rj-grid-title">{{ $model->name }}</h1>

        @if($model->description)
            <p class="rj-grid-subtitle">{{ $model->description }}</p>
        @endif
    </div>
</section>

@if($categories->count())
<section class="rj-grid-section">
    <div class="rj-grid-bg"></div>
    <div class="rj-grid-inner">
        <div class="rj-grid-head">
            <div class="rj-grid-eyebrow">
                <span class="rj-grid-eyebrow-line"></span>
                <span class="rj-grid-eyebrow-text">Browse by category</span>
            </div>
            <h2 class="rj-grid-h2">Shop categories</h2>
        </div>

        <div class="rj-cat-grid">
            @foreach($categories as $category)
                <a href="{{ url('model/' . $model->slug . '/' . $category->slug) }}" class="rj-cat-card">
                    <div class="rj-cat-img">
                        @if($category->image_url)
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}">
                        @else
                            <div class="rj-cat-placeholder">
                                <i class="fa-solid fa-folder"></i>
                            </div>
                        @endif
                        <div class="rj-cat-veil"></div>
                    </div>
                    <div class="rj-cat-body">
                        <h3 class="rj-cat-title">{{ $category->name }}</h3>
                        @if($category->children->count())
                            <p class="rj-cat-meta">{{ $category->children->count() }} subs</p>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="rj-grid-section">
    <div class="rj-grid-bg"></div>
    <div class="rj-grid-inner">
        <div class="rj-grid-head">
            <div class="rj-grid-eyebrow">
                <span class="rj-grid-eyebrow-line"></span>
                <span class="rj-grid-eyebrow-text">All products</span>
            </div>
            <div class="rj-grid-head-row">
                <h2 class="rj-grid-h2">{{ $model->name }}</h2>
                @if($products->count())
                    <span class="rj-grid-count">{{ $products->total() }} {{ $products->total() === 1 ? 'product' : 'products' }}</span>
                @endif
            </div>
        </div>

        @if($products->count())
            <div class="rj-prod-grid">
                @foreach($products as $product)
                    @include('theme.rjshop-theme.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="rj-grid-pagination">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="rj-grid-empty">
                <div class="rj-grid-empty-icon">
                    <i class="fa-regular fa-box-open"></i>
                </div>
                <p class="rj-grid-empty-title">No products yet</p>
                <p class="rj-grid-empty-text">Check back soon — products are being added.</p>
            </div>
        @endif
    </div>
</section>

<style>
    .rj-grid-hero,
    .rj-grid-section {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }
    .rj-grid-hero { padding: 4rem 0 3rem; }
    @media (min-width: 768px) { .rj-grid-hero { padding: 6rem 0 4rem; } }
    .rj-grid-section { padding: 3rem 0; }
    @media (min-width: 768px) { .rj-grid-section { padding: 4rem 0; } }

    .rj-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-grid-orb {
        position: absolute; width: 400px; height: 400px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-grid-orb-a { top: 0; left: 20%; background: rgba(99, 102, 241, 0.07); }
    .rj-grid-orb-b { bottom: 0; right: 20%; background: rgba(236, 72, 153, 0.07); }

    .rj-grid-inner {
        position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-grid-inner { padding: 0 3rem; } }

    .rj-grid-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 1.5rem;
    }
    .rj-grid-breadcrumb a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .rj-grid-breadcrumb a:hover { color: #a5b4fc; }
    .rj-grid-breadcrumb .current { color: #9ca3af; }

    .rj-grid-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(236, 72, 153, 0.15));
        border: 1px solid rgba(99, 102, 241, 0.3);
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 22px; margin-bottom: 1rem;
    }

    .rj-grid-title {
        font-size: clamp(1.75rem, 4vw, 3rem);
        font-weight: 900; line-height: 1.08; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 1rem;
    }
    .rj-grid-subtitle {
        font-size: 1rem; color: #6b7280; line-height: 1.7;
        max-width: 40rem; margin: 0; font-weight: 300;
    }

    .rj-grid-head { margin-bottom: 2rem; }
    .rj-grid-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
    .rj-grid-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-grid-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }
    .rj-grid-head-row {
        display: flex; align-items: baseline; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
    }
    .rj-grid-h2 {
        font-size: clamp(1.25rem, 2.5vw, 1.75rem); font-weight: 800;
        line-height: 1.15; letter-spacing: -0.01em; color: #fff; margin: 0;
    }
    .rj-grid-count {
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.2em;
        color: #6b7280;
    }

    .rj-cat-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.625rem;
    }
    @media (min-width: 640px) { .rj-cat-grid { grid-template-columns: repeat(4, 1fr); } }
    @media (min-width: 1024px) { .rj-cat-grid { grid-template-columns: repeat(6, 1fr); gap: 0.75rem; } }
    @media (min-width: 1280px) { .rj-cat-grid { grid-template-columns: repeat(8, 1fr); } }

    .rj-cat-card {
        display: flex; flex-direction: column; overflow: hidden;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 10px; text-decoration: none; color: inherit;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-cat-card:hover {
        transform: translateY(-2px);
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 12px 24px -10px rgba(99, 102, 241, 0.3);
    }
    .rj-cat-img {
        position: relative; aspect-ratio: 1; overflow: hidden; background: #0a0715;
    }
    .rj-cat-img img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-cat-card:hover .rj-cat-img img { transform: scale(1.06); }
    .rj-cat-placeholder {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        color: rgba(255, 255, 255, 0.06); font-size: 1.25rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(236, 72, 153, 0.08));
    }
    .rj-cat-veil {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(5, 3, 15, 0.6) 0%, transparent 60%);
        pointer-events: none;
    }
    .rj-cat-body { padding: 0.5rem 0.625rem 0.625rem; }
    .rj-cat-title {
        font-size: 11.5px; font-weight: 600; color: #fff;
        line-height: 1.3; margin: 0;
        transition: color 0.3s;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
        text-overflow: ellipsis;
    }
    .rj-cat-card:hover .rj-cat-title { color: #a5b4fc; }
    .rj-cat-meta {
        font-family: ui-monospace, monospace; font-size: 8px;
        text-transform: uppercase; letter-spacing: 0.1em;
        color: #6b7280; margin: 0.15rem 0 0;
        display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;
    }

    .rj-prod-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;
    }
    @media (min-width: 768px) { .rj-prod-grid { grid-template-columns: repeat(3, 1fr); gap: 1.25rem; } }
    @media (min-width: 1024px) { .rj-prod-grid { grid-template-columns: repeat(4, 1fr); } }

    .rj-grid-pagination {
        margin-top: 3rem; display: flex; justify-content: center;
    }
    .rj-grid-pagination nav { display: inline-flex; }
    .rj-grid-pagination .pagination,
    .rj-grid-pagination nav > div { display: flex; gap: 0.25rem; }
    .rj-grid-pagination a,
    .rj-grid-pagination span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.25rem; height: 2.25rem; padding: 0 0.75rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #9ca3af; font-size: 13px;
        font-family: ui-monospace, monospace;
        text-decoration: none; transition: all 0.2s ease;
    }
    .rj-grid-pagination a:hover {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.4);
        color: #fff;
    }
    .rj-grid-pagination .active span {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-color: transparent; color: #fff;
    }
    .rj-grid-pagination .disabled span { opacity: 0.35; cursor: not-allowed; }

    .rj-grid-empty {
        max-width: 28rem; margin: 0 auto; text-align: center; padding: 4rem 0;
    }
    .rj-grid-empty-icon {
        width: 4rem; height: 4rem; border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.25rem; color: #4b5563; font-size: 1.5rem;
    }
    .rj-grid-empty-title { font-size: 14px; color: #6b7280; margin-bottom: 0.25rem; }
    .rj-grid-empty-text { font-size: 12px; color: #4b5563; }
</style>

@endsection

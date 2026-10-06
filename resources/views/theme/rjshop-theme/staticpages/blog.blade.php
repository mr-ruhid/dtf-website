@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Blog')
@section('meta_description', 'Latest news, tips and guides')
@section('meta_keywords', 'blog, news, dtf, printing')

@section('content')

<section class="rj-blog-hero">
    <div class="rj-blog-grid-bg"></div>
    <div class="rj-blog-orb rj-blog-orb-a"></div>
    <div class="rj-blog-orb rj-blog-orb-b"></div>

    <div class="rj-blog-inner">
        <div class="rj-blog-hero-content">
            <div class="rj-blog-eyebrow">
                <span class="rj-blog-eyebrow-line"></span>
                <span class="rj-blog-eyebrow-text">Journal</span>
                <span class="rj-blog-eyebrow-line"></span>
            </div>

            <h1 class="rj-blog-title">Latest from the Blog</h1>
            <p class="rj-blog-subtitle">Tips, guides, and industry news — updated regularly.</p>
        </div>
    </div>
</section>

@if($posts->count())
<section class="rj-blog-list">
    <div class="rj-blog-grid-bg"></div>
    <div class="rj-blog-inner">

        <div class="rj-blog-grid">
            @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="rj-blog-card">
                    <div class="rj-blog-img-wrap">
                        @if($post->image_url)
                            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="rj-blog-img">
                        @else
                            <div class="rj-blog-img-placeholder">
                                <i class="fa-regular fa-newspaper"></i>
                            </div>
                        @endif
                        <div class="rj-blog-img-veil"></div>
                        <div class="rj-blog-date-badge">
                            <span class="rj-blog-date-day">{{ $post->published_at ? $post->published_at->format('d') : '—' }}</span>
                            <span class="rj-blog-date-month">{{ $post->published_at ? $post->published_at->format('M') : '' }}</span>
                        </div>
                    </div>

                    <div class="rj-blog-card-body">
                        @if($post->published_at)
                            <span class="rj-blog-meta">{{ $post->published_at->format('d M Y') }}</span>
                        @endif

                        <h3 class="rj-blog-card-title">{{ $post->title }}</h3>

                        @if($post->excerpt)
                            <p class="rj-blog-card-excerpt">{{ Str::limit($post->excerpt, 120) }}</p>
                        @endif

                        <span class="rj-blog-read-more">
                            <span>Read article</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        @if($posts->hasPages())
            <div class="rj-blog-pagination">
                {{ $posts->links() }}
            </div>
        @endif

    </div>
</section>
@else
<section class="rj-blog-list">
    <div class="rj-blog-inner">
        <div class="rj-blog-empty">
            <div class="rj-blog-empty-icon">
                <i class="fa-regular fa-newspaper"></i>
            </div>
            <p class="rj-blog-empty-title">No posts yet</p>
            <p class="rj-blog-empty-text">Blog articles will appear here once published.</p>
        </div>
    </div>
</section>
@endif

<style>
    .rj-blog-hero,
    .rj-blog-list {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }

    .rj-blog-hero {
        padding: 5rem 0 3rem;
    }
    @media (min-width: 768px) {
        .rj-blog-hero { padding: 7rem 0 4rem; }
    }

    .rj-blog-list {
        padding-bottom: 5rem;
    }
    @media (min-width: 768px) {
        .rj-blog-list { padding-bottom: 7rem; }
    }

    .rj-blog-grid-bg {
        position: absolute;
        inset: 0;
        opacity: 0.025;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-blog-orb {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        filter: blur(120px);
        pointer-events: none;
    }
    .rj-blog-orb-a {
        top: 0;
        left: 25%;
        background: rgba(99, 102, 241, 0.07);
    }
    .rj-blog-orb-b {
        bottom: 0;
        right: 25%;
        background: rgba(236, 72, 153, 0.07);
    }

    .rj-blog-inner {
        position: relative;
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) {
        .rj-blog-inner { padding: 0 3rem; }
    }

    .rj-blog-hero-content {
        max-width: 48rem;
        margin: 0 auto;
        text-align: center;
    }

    .rj-blog-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .rj-blog-eyebrow-line {
        width: 1.25rem;
        height: 1px;
        background: rgba(244, 114, 182, 0.6);
    }

    .rj-blog-eyebrow-text {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }

    .rj-blog-title {
        font-size: clamp(1.75rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1.08;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 0 0 1rem;
    }

    .rj-blog-subtitle {
        font-size: 0.9375rem;
        color: #6b7280;
        line-height: 1.7;
        max-width: 36rem;
        margin: 0 auto;
        font-weight: 300;
    }
    @media (min-width: 768px) {
        .rj-blog-subtitle { font-size: 1.0625rem; }
    }

    .rj-blog-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @media (min-width: 640px) {
        .rj-blog-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 1024px) {
        .rj-blog-grid { grid-template-columns: repeat(3, 1fr); gap: 1.75rem; }
    }

    .rj-blog-card {
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .rj-blog-card:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(99, 102, 241, 0.25);
    }

    .rj-blog-img-wrap {
        position: relative;
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #0a0715;
    }

    .rj-blog-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 900ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    .rj-blog-card:hover .rj-blog-img {
        transform: scale(1.06);
    }

    .rj-blog-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.05);
        font-size: 3rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
    }

    .rj-blog-img-veil {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(5, 3, 15, 0.6) 0%, transparent 60%);
        pointer-events: none;
    }

    .rj-blog-date-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: rgba(5, 3, 15, 0.75);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 6px 10px;
        min-width: 44px;
    }

    .rj-blog-date-day {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        line-height: 1;
    }

    .rj-blog-date-month {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #a5b4fc;
        margin-top: 2px;
        line-height: 1;
    }

    .rj-blog-card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .rj-blog-meta {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #818cf8;
        margin-bottom: 0.75rem;
    }

    .rj-blog-card-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
        letter-spacing: -0.01em;
        margin: 0 0 0.75rem;
        transition: color 0.3s ease;
    }

    .rj-blog-card:hover .rj-blog-card-title {
        color: #a5b4fc;
    }

    .rj-blog-card-excerpt {
        font-size: 13.5px;
        color: #9ca3af;
        line-height: 1.65;
        margin: 0 0 1.25rem;
        flex: 1;
    }

    .rj-blog-read-more {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        transition: color 0.3s ease;
        margin-top: auto;
    }

    .rj-blog-card:hover .rj-blog-read-more {
        color: #a5b4fc;
    }

    .rj-blog-read-more i {
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    .rj-blog-card:hover .rj-blog-read-more i {
        transform: translateX(4px);
    }

    .rj-blog-pagination {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
    }

    .rj-blog-pagination nav {
        display: inline-flex;
    }

    .rj-blog-pagination .pagination,
    .rj-blog-pagination nav > div {
        display: flex;
        gap: 0.25rem;
    }

    .rj-blog-pagination a,
    .rj-blog-pagination span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2.25rem;
        height: 2.25rem;
        padding: 0 0.75rem;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #9ca3af;
        font-size: 13px;
        font-family: ui-monospace, SFMono-Regular, monospace;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .rj-blog-pagination a:hover {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.4);
        color: #fff;
    }

    .rj-blog-pagination .active span {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-color: transparent;
        color: #fff;
    }

    .rj-blog-pagination .disabled span {
        opacity: 0.35;
        cursor: not-allowed;
    }

    .rj-blog-empty {
        max-width: 28rem;
        margin: 0 auto;
        text-align: center;
        padding: 4rem 0;
    }

    .rj-blog-empty-icon {
        width: 4rem;
        height: 4rem;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
        color: #4b5563;
        font-size: 1.5rem;
    }

    .rj-blog-empty-title {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .rj-blog-empty-text {
        font-size: 12px;
        color: #4b5563;
    }
</style>

@endsection

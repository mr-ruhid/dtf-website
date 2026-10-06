@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $post->seo_title)
@section('meta_description', $post->seo_description)
@section('meta_keywords', $post->meta_keywords)

@section('content')

<section class="rj-post-hero">
    <div class="rj-post-grid-bg"></div>
    <div class="rj-post-orb rj-post-orb-a"></div>
    <div class="rj-post-orb rj-post-orb-b"></div>

    <div class="rj-post-inner">

        <div class="rj-post-hero-content">
            <a href="{{ route('blog.index') }}" class="rj-post-back">
                <i class="fa-solid fa-arrow-left"></i>
                <span>All articles</span>
            </a>

            <div class="rj-post-eyebrow">
                <span class="rj-post-eyebrow-line"></span>
                <span class="rj-post-eyebrow-text">Journal</span>
            </div>

            <h1 class="rj-post-title">{{ $post->title }}</h1>

            @if($post->excerpt)
                <p class="rj-post-excerpt">{{ $post->excerpt }}</p>
            @endif

            <div class="rj-post-meta">
                @if($post->published_at)
                    <span class="rj-post-meta-item">
                        <i class="fa-regular fa-calendar"></i>
                        <span>{{ $post->published_at->format('d M Y') }}</span>
                    </span>
                @endif
                <span class="rj-post-meta-item">
                    <i class="fa-regular fa-clock"></i>
                    <span>{{ max(1, ceil(str_word_count(strip_tags($post->content)) / 200)) }} min read</span>
                </span>
            </div>
        </div>

        @if($post->image_url)
            <div class="rj-post-featured">
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}">
                <div class="rj-post-featured-veil"></div>
            </div>
        @endif

    </div>
</section>

<article class="rj-post-body">
    <div class="rj-post-grid-bg"></div>
    <div class="rj-post-inner">

        <div class="rj-post-content">
            {!! $post->content !!}
        </div>

        <div class="rj-post-footer">
            <div class="rj-post-divider"></div>

            <div class="rj-post-actions">
                <a href="{{ route('blog.index') }}" class="rj-post-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Back to blog</span>
                </a>

                <div class="rj-post-share">
                    <span class="rj-post-share-label">Share</span>
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="rj-post-share-btn" aria-label="Twitter">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="rj-post-share-btn" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="rj-post-share-btn" aria-label="LinkedIn">
                        <i class="fa-brands fa-linkedin-in"></i>
                    </a>
                    <button type="button" class="rj-post-share-btn" onclick="navigator.clipboard.writeText('{{ url()->current() }}'); this.querySelector('i').className='fa-solid fa-check'; setTimeout(()=>this.querySelector('i').className='fa-solid fa-link', 1500);" aria-label="Copy link">
                        <i class="fa-solid fa-link"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</article>

@if($related->count())
<section class="rj-post-related">
    <div class="rj-post-grid-bg"></div>
    <div class="rj-post-inner">

        <div class="rj-post-related-head">
            <span class="rj-post-eyebrow-line"></span>
            <span class="rj-post-eyebrow-text">Continue Reading</span>
            <span class="rj-post-eyebrow-line"></span>
        </div>

        <div class="rj-post-related-grid">
            @foreach($related as $item)
                <a href="{{ route('blog.show', $item->slug) }}" class="rj-post-related-card">
                    <div class="rj-post-related-img">
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                        @else
                            <div class="rj-post-related-img-placeholder">
                                <i class="fa-regular fa-newspaper"></i>
                            </div>
                        @endif
                    </div>
                    <div class="rj-post-related-body">
                        @if($item->published_at)
                            <span class="rj-post-related-date">{{ $item->published_at->format('d M Y') }}</span>
                        @endif
                        <h3 class="rj-post-related-title">{{ $item->title }}</h3>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>
@endif

<style>
    .rj-post-hero,
    .rj-post-body,
    .rj-post-related {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }

    .rj-post-hero {
        padding: 4rem 0 3rem;
    }
    @media (min-width: 768px) {
        .rj-post-hero { padding: 6rem 0 4rem; }
    }

    .rj-post-body {
        padding: 2rem 0 4rem;
    }

    .rj-post-related {
        padding: 4rem 0 6rem;
    }

    .rj-post-grid-bg {
        position: absolute;
        inset: 0;
        opacity: 0.025;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-post-orb {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        filter: blur(120px);
        pointer-events: none;
    }
    .rj-post-orb-a {
        top: 0;
        left: 20%;
        background: rgba(99, 102, 241, 0.07);
    }
    .rj-post-orb-b {
        bottom: 10%;
        right: 20%;
        background: rgba(236, 72, 153, 0.07);
    }

    .rj-post-inner {
        position: relative;
        max-width: 48rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) {
        .rj-post-inner { padding: 0 2rem; }
    }

    .rj-post-hero-content {
        text-align: left;
    }

    .rj-post-back {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
        text-decoration: none;
        margin-bottom: 2rem;
        transition: color 0.3s ease;
    }

    .rj-post-back:hover {
        color: #a5b4fc;
    }

    .rj-post-back i {
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    .rj-post-back:hover i {
        transform: translateX(-3px);
    }

    .rj-post-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .rj-post-eyebrow-line {
        width: 1.25rem;
        height: 1px;
        background: rgba(244, 114, 182, 0.6);
    }

    .rj-post-eyebrow-text {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }

    .rj-post-title {
        font-size: clamp(1.75rem, 4.5vw, 3rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 0 0 1.25rem;
    }

    .rj-post-excerpt {
        font-size: 1.0625rem;
        color: #9ca3af;
        line-height: 1.7;
        margin: 0 0 1.5rem;
        font-weight: 300;
    }

    .rj-post-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1.5rem;
    }

    .rj-post-meta-item {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
    }

    .rj-post-meta-item i {
        color: #818cf8;
        font-size: 11px;
    }

    .rj-post-featured {
        position: relative;
        margin-top: 3rem;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.08);
        aspect-ratio: 16 / 9;
    }

    .rj-post-featured img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .rj-post-featured-veil {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(5, 3, 15, 0.4), transparent 50%);
        pointer-events: none;
    }

    .rj-post-content {
        color: #d1d5db;
        font-size: 1.0625rem;
        line-height: 1.85;
    }

    .rj-post-content h1,
    .rj-post-content h2,
    .rj-post-content h3,
    .rj-post-content h4 {
        color: #ffffff;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .rj-post-content h1 { font-size: 2rem; }
    .rj-post-content h2 { font-size: 1.625rem; }
    .rj-post-content h3 { font-size: 1.25rem; margin-top: 2rem; }
    .rj-post-content h4 { font-size: 1.0625rem; }

    .rj-post-content p {
        margin-bottom: 1.25rem;
        color: #9ca3af;
    }

    .rj-post-content strong {
        color: #ffffff;
        font-weight: 700;
    }

    .rj-post-content em {
        color: #c7d2fe;
        font-style: italic;
    }

    .rj-post-content a {
        color: #818cf8;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.2s ease;
    }

    .rj-post-content a:hover {
        color: #c7d2fe;
    }

    .rj-post-content ul,
    .rj-post-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
        color: #9ca3af;
    }

    .rj-post-content ul { list-style: disc; }
    .rj-post-content ol { list-style: decimal; }
    .rj-post-content li { margin-bottom: 0.5rem; }
    .rj-post-content li strong { color: #fff; }

    .rj-post-content img {
        border-radius: 12px;
        margin: 1.5rem 0;
        max-width: 100%;
        height: auto;
    }

    .rj-post-content blockquote {
        border-left: 3px solid #6366f1;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background: rgba(99, 102, 241, 0.05);
        border-radius: 0 12px 12px 0;
        color: #c7d2fe;
        font-style: italic;
    }

    .rj-post-content hr {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.4), transparent);
        margin: 2rem 0;
    }

    .rj-post-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }

    .rj-post-content th {
        background: rgba(99, 102, 241, 0.08);
        color: #fff;
        font-weight: 700;
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .rj-post-content td {
        padding: 0.75rem 1rem;
        color: #9ca3af;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .rj-post-content tr:last-child td {
        border-bottom: none;
    }

    .rj-post-footer {
        margin-top: 3rem;
    }

    .rj-post-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.4), transparent);
        margin-bottom: 2rem;
    }

    .rj-post-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .rj-post-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        padding: 0.65rem 1.25rem;
        border-radius: 9999px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .rj-post-btn:hover {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .rj-post-btn i {
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    .rj-post-btn:hover i {
        transform: translateX(-3px);
    }

    .rj-post-share {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .rj-post-share-label {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #6b7280;
        margin-right: 0.5rem;
    }

    .rj-post-share-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 2.25rem;
        height: 2.25rem;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: #9ca3af;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .rj-post-share-btn:hover {
        background: rgba(99, 102, 241, 0.15);
        border-color: rgba(99, 102, 241, 0.4);
        color: #fff;
        transform: translateY(-2px);
    }

    .rj-post-related-head {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 2.5rem;
    }

    .rj-post-related-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.25rem;
    }
    @media (min-width: 640px) {
        .rj-post-related-grid { grid-template-columns: repeat(3, 1fr); }
    }

    .rj-post-related-card {
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .rj-post-related-card:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-3px);
        box-shadow: 0 16px 32px -12px rgba(99, 102, 241, 0.25);
    }

    .rj-post-related-img {
        aspect-ratio: 16 / 10;
        overflow: hidden;
        background: #0a0715;
    }

    .rj-post-related-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 900ms cubic-bezier(0.16, 1, 0.3, 1);
    }

    .rj-post-related-card:hover .rj-post-related-img img {
        transform: scale(1.06);
    }

    .rj-post-related-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.05);
        font-size: 2rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(236, 72, 153, 0.1));
    }

    .rj-post-related-body {
        padding: 1.25rem;
    }

    .rj-post-related-date {
        display: block;
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #818cf8;
        margin-bottom: 0.5rem;
    }

    .rj-post-related-title {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        line-height: 1.35;
        letter-spacing: -0.01em;
        margin: 0;
        transition: color 0.3s ease;
    }

    .rj-post-related-card:hover .rj-post-related-title {
        color: #a5b4fc;
    }
</style>

@endsection

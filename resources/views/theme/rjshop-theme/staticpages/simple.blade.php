@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<section class="rj-page-hero">
    <div class="rj-page-grid-bg"></div>
    <div class="rj-page-orb rj-page-orb-a"></div>
    <div class="rj-page-orb rj-page-orb-b"></div>

    <div class="rj-page-inner">
        <div class="rj-page-hero-content">
            <div class="rj-page-eyebrow">
                <span class="rj-page-eyebrow-line"></span>
                <span class="rj-page-eyebrow-text">Legal</span>
            </div>

            <h1 class="rj-page-title">{{ $page->title }}</h1>

            @if($page->excerpt)
                <p class="rj-page-excerpt">{{ $page->excerpt }}</p>
            @endif
        </div>
    </div>
</section>

<article class="rj-page-body">
    <div class="rj-page-grid-bg"></div>
    <div class="rj-page-inner">

        @if($page->content)
            <div class="rj-page-content">
                {!! $page->content !!}
            </div>
        @else
            <div class="rj-page-empty">
                <div class="rj-page-empty-icon">
                    <i class="fa-regular fa-file-lines"></i>
                </div>
                <p class="rj-page-empty-title">No content yet</p>
                <p class="rj-page-empty-text">This page will be updated soon.</p>
            </div>
        @endif

        <div class="rj-page-footer">
            <div class="rj-page-divider"></div>

            <div class="rj-page-actions">
                <p class="rj-page-updated">
                    <i class="fa-regular fa-clock"></i>
                    <span>Last updated: {{ $page->updated_at ? $page->updated_at->format('d M Y') : 'recently' }}</span>
                </p>

                <a href="{{ url('contact-us') }}" class="rj-page-btn">
                    <span>Questions? Contact us</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>
</article>

<style>
    .rj-page-hero,
    .rj-page-body {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }

    .rj-page-hero {
        padding: 4rem 0 3rem;
    }
    @media (min-width: 768px) {
        .rj-page-hero { padding: 6rem 0 3.5rem; }
    }

    .rj-page-body {
        padding: 2rem 0 5rem;
    }

    .rj-page-grid-bg {
        position: absolute;
        inset: 0;
        opacity: 0.025;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-page-orb {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        filter: blur(120px);
        pointer-events: none;
    }
    .rj-page-orb-a {
        top: 0;
        left: 20%;
        background: rgba(99, 102, 241, 0.07);
    }
    .rj-page-orb-b {
        bottom: 10%;
        right: 20%;
        background: rgba(236, 72, 153, 0.07);
    }

    .rj-page-inner {
        position: relative;
        max-width: 48rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) {
        .rj-page-inner { padding: 0 2rem; }
    }

    .rj-page-hero-content {
        text-align: left;
    }

    .rj-page-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .rj-page-eyebrow-line {
        width: 1.25rem;
        height: 1px;
        background: rgba(244, 114, 182, 0.6);
    }

    .rj-page-eyebrow-text {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }

    .rj-page-title {
        font-size: clamp(1.75rem, 4.5vw, 3rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 0 0 1rem;
    }

    .rj-page-excerpt {
        font-size: 1.0625rem;
        color: #9ca3af;
        line-height: 1.7;
        margin: 0;
        font-weight: 300;
    }

    .rj-page-content {
        color: #d1d5db;
        font-size: 1.0625rem;
        line-height: 1.85;
    }

    .rj-page-content h1,
    .rj-page-content h2,
    .rj-page-content h3,
    .rj-page-content h4 {
        color: #ffffff;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.2;
        margin-top: 2.5rem;
        margin-bottom: 1rem;
    }

    .rj-page-content h1 { font-size: 2rem; }
    .rj-page-content h2 { font-size: 1.5rem; }
    .rj-page-content h3 { font-size: 1.25rem; margin-top: 2rem; }
    .rj-page-content h4 { font-size: 1.0625rem; }

    .rj-page-content h2:first-child,
    .rj-page-content h3:first-child {
        margin-top: 0;
    }

    .rj-page-content p {
        margin-bottom: 1.25rem;
        color: #9ca3af;
    }

    .rj-page-content strong {
        color: #ffffff;
        font-weight: 700;
    }

    .rj-page-content em {
        color: #c7d2fe;
        font-style: italic;
    }

    .rj-page-content a {
        color: #818cf8;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.2s ease;
    }

    .rj-page-content a:hover {
        color: #c7d2fe;
    }

    .rj-page-content ul,
    .rj-page-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1.25rem;
        color: #9ca3af;
    }

    .rj-page-content ul { list-style: disc; }
    .rj-page-content ol { list-style: decimal; }
    .rj-page-content li { margin-bottom: 0.5rem; }
    .rj-page-content li strong { color: #fff; }

    .rj-page-content img {
        border-radius: 12px;
        margin: 1.5rem 0;
        max-width: 100%;
        height: auto;
    }

    .rj-page-content blockquote {
        border-left: 3px solid #6366f1;
        padding: 1rem 1.5rem;
        margin: 1.5rem 0;
        background: rgba(99, 102, 241, 0.05);
        border-radius: 0 12px 12px 0;
        color: #c7d2fe;
        font-style: italic;
    }

    .rj-page-content hr {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.4), transparent);
        margin: 2rem 0;
    }

    .rj-page-content table {
        width: 100%;
        border-collapse: collapse;
        margin: 1.5rem 0;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        overflow: hidden;
    }

    .rj-page-content th {
        background: rgba(99, 102, 241, 0.08);
        color: #fff;
        font-weight: 700;
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .rj-page-content td {
        padding: 0.75rem 1rem;
        color: #9ca3af;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
    }

    .rj-page-content tr:last-child td {
        border-bottom: none;
    }

    .rj-page-empty {
        max-width: 28rem;
        margin: 0 auto;
        text-align: center;
        padding: 3rem 0;
    }

    .rj-page-empty-icon {
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

    .rj-page-empty-title {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .rj-page-empty-text {
        font-size: 12px;
        color: #4b5563;
    }

    .rj-page-footer {
        margin-top: 3rem;
    }

    .rj-page-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.4), transparent);
        margin-bottom: 2rem;
    }

    .rj-page-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
    }

    .rj-page-updated {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
        margin: 0;
    }

    .rj-page-updated i {
        color: #818cf8;
        font-size: 11px;
    }

    .rj-page-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: #fff;
        color: #05030f;
        font-weight: 600;
        font-size: 13px;
        padding: 0.75rem 1.5rem;
        border-radius: 9999px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .rj-page-btn:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.35);
    }

    .rj-page-btn i {
        font-size: 10px;
        transition: transform 0.3s ease;
    }

    .rj-page-btn:hover i {
        transform: translateX(4px);
    }
</style>

@endsection

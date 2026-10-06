@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<section class="rj-faq-hero">
    <div class="rj-faq-grid-bg"></div>
    <div class="rj-faq-orb rj-faq-orb-a"></div>
    <div class="rj-faq-orb rj-faq-orb-b"></div>

    <div class="rj-faq-inner">
        <div class="rj-faq-hero-content">
            <div class="rj-faq-eyebrow">
                <span class="rj-faq-eyebrow-line"></span>
                <span class="rj-faq-eyebrow-text">Help Center</span>
                <span class="rj-faq-eyebrow-line"></span>
            </div>

            <h1 class="rj-faq-title">
                {{ $page->title ?? 'Frequently Asked Questions' }}
            </h1>

            @if($page->excerpt)
                <p class="rj-faq-subtitle">{{ $page->excerpt }}</p>
            @else
                <p class="rj-faq-subtitle">Everything you need to know about our products and services.</p>
            @endif
        </div>
    </div>
</section>

@if($faqs->count())
<section class="rj-faq-list-section" x-data="{ open: 0, q: '' }">
    <div class="rj-faq-grid-bg"></div>
    <div class="rj-faq-inner">

        <div class="rj-faq-search-wrap">
            <i class="fa-solid fa-magnifying-glass rj-faq-search-icon"></i>
            <input type="text"
                   x-model="q"
                   placeholder="Search questions..."
                   class="rj-faq-search-input">
            <button type="button" x-show="q !== ''" x-cloak @click="q = ''" class="rj-faq-search-clear">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="rj-faq-layout">

            <aside class="rj-faq-sidebar">
                <div class="rj-faq-sidebar-inner">
                    <div class="rj-faq-sidebar-label">// Index</div>
                    <nav class="rj-faq-sidebar-nav">
                        @foreach($faqs as $index => $faq)
                            <button type="button"
                                    x-show="q === '' || '{{ strtolower(addslashes($faq->question)) }}'.includes(q.toLowerCase())"
                                    @click="open = {{ $index }}; document.getElementById('rj-faq-{{ $index }}').scrollIntoView({behavior: 'smooth', block: 'center'})"
                                    class="rj-faq-sidebar-item"
                                    :class="open === {{ $index }} ? 'rj-faq-sidebar-item-active' : ''">
                                <span class="rj-faq-sidebar-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ Str::limit($faq->question, 38) }}</span>
                            </button>
                        @endforeach
                    </nav>
                </div>
            </aside>

            <div class="rj-faq-main">
                <div class="rj-faq-items">
                    @foreach($faqs as $index => $faq)
                        <div id="rj-faq-{{ $index }}"
                             class="rj-faq-item"
                             x-show="q === '' || '{{ strtolower(addslashes($faq->question)) }}'.includes(q.toLowerCase())"
                             :class="open === {{ $index }} ? 'rj-faq-item-open' : ''">

                            <button type="button"
                                    @click="open = open === {{ $index }} ? null : {{ $index }}"
                                    class="rj-faq-question">

                                <span class="rj-faq-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>

                                <span class="rj-faq-qtext">{{ $faq->question }}</span>

                                <span class="rj-faq-icon">
                                    <i class="fa-solid fa-plus" :class="open === {{ $index }} ? 'rj-faq-icon-rot' : ''"></i>
                                </span>
                            </button>

                            <div x-show="open === {{ $index }}" x-collapse x-cloak>
                                <div class="rj-faq-answer-wrap">
                                    <div class="rj-faq-answer">
                                        {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li><h3><h4><blockquote>'))) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div x-show="q !== ''" x-cloak class="rj-faq-filter-tag">
                    <i class="fa-solid fa-filter"></i>
                    <span>Filtering: <strong x-text="q"></strong></span>
                </div>
            </div>

        </div>

    </div>
</section>
@else
<section class="rj-faq-list-section">
    <div class="rj-faq-inner">
        <div class="rj-faq-empty">
            <div class="rj-faq-empty-icon">
                <i class="fa-regular fa-circle-question"></i>
            </div>
            <p class="rj-faq-empty-title">No FAQs yet</p>
            <p class="rj-faq-empty-text">Questions will appear here once added.</p>
        </div>
    </div>
</section>
@endif

<section class="rj-faq-cta-section">
    <div class="rj-faq-grid-bg"></div>
    <div class="rj-faq-inner">
        <div class="rj-faq-cta">
            <div class="rj-faq-eyebrow">
                <span class="rj-faq-eyebrow-line"></span>
                <span class="rj-faq-eyebrow-text">Still Curious?</span>
                <span class="rj-faq-eyebrow-line"></span>
            </div>

            <h3 class="rj-faq-cta-title">Didn't find your answer?</h3>
            <p class="rj-faq-cta-text">Our team is ready to help with anything else you need.</p>

            <a href="{{ url('contact-us') }}" class="rj-faq-cta-btn">
                <span>Contact Us</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<style>
    .rj-faq-hero,
    .rj-faq-list-section,
    .rj-faq-cta-section {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
    }

    .rj-faq-hero {
        padding: 5rem 0 3rem;
    }
    @media (min-width: 768px) {
        .rj-faq-hero { padding: 7rem 0 4rem; }
    }

    .rj-faq-list-section {
        padding-bottom: 5rem;
    }
    @media (min-width: 768px) {
        .rj-faq-list-section { padding-bottom: 7rem; }
    }

    .rj-faq-cta-section {
        padding: 4rem 0 5rem;
    }
    @media (min-width: 768px) {
        .rj-faq-cta-section { padding: 5rem 0; }
    }

    .rj-faq-grid-bg {
        position: absolute;
        inset: 0;
        opacity: 0.025;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-faq-orb {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        filter: blur(120px);
        pointer-events: none;
    }
    .rj-faq-orb-a {
        top: 0;
        left: 25%;
        background: rgba(99, 102, 241, 0.07);
    }
    .rj-faq-orb-b {
        bottom: 0;
        right: 25%;
        background: rgba(236, 72, 153, 0.07);
    }

    .rj-faq-inner {
        position: relative;
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) {
        .rj-faq-inner { padding: 0 3rem; }
    }

    .rj-faq-hero-content {
        max-width: 48rem;
        margin: 0 auto;
        text-align: center;
    }

    .rj-faq-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .rj-faq-eyebrow-line {
        width: 1.25rem;
        height: 1px;
        background: rgba(244, 114, 182, 0.6);
    }

    .rj-faq-eyebrow-text {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }

    .rj-faq-title {
        font-size: clamp(1.75rem, 4vw, 3rem);
        font-weight: 900;
        line-height: 1.08;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 0 0 1rem;
    }

    .rj-faq-subtitle {
        font-size: 0.9375rem;
        color: #6b7280;
        line-height: 1.7;
        max-width: 36rem;
        margin: 0 auto;
        font-weight: 300;
    }
    @media (min-width: 768px) {
        .rj-faq-subtitle { font-size: 1.0625rem; }
    }

    .rj-faq-search-wrap {
        position: relative;
        max-width: 36rem;
        margin: 0 auto 2.5rem;
    }

    .rj-faq-search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        font-size: 0.875rem;
        pointer-events: none;
    }

    .rj-faq-search-input {
        width: 100%;
        padding: 0.875rem 3rem 0.875rem 3rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        font-size: 0.875rem;
        color: #fff;
        font-family: inherit;
        transition: all 0.3s ease;
        outline: none;
    }

    .rj-faq-search-input::placeholder {
        color: #4b5563;
    }

    .rj-faq-search-input:focus {
        border-color: rgba(99, 102, 241, 0.6);
        background: rgba(99, 102, 241, 0.03);
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.25);
    }

    .rj-faq-search-clear {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.5rem;
        height: 1.5rem;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
        border: none;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        font-size: 0.625rem;
    }

    .rj-faq-search-clear:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .rj-faq-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    @media (min-width: 1024px) {
        .rj-faq-layout {
            grid-template-columns: 240px 1fr;
            gap: 2.5rem;
        }
    }

    .rj-faq-sidebar {
        display: none;
    }
    @media (min-width: 1024px) {
        .rj-faq-sidebar { display: block; }
    }

    .rj-faq-sidebar-inner {
        position: sticky;
        top: 7rem;
    }

    .rj-faq-sidebar-label {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3em;
        color: #818cf8;
        margin-bottom: 1rem;
    }

    .rj-faq-sidebar-nav {
        border-left: 1px solid rgba(255, 255, 255, 0.07);
        padding-left: 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
    }

    .rj-faq-sidebar-item {
        display: block;
        width: 100%;
        text-align: left;
        font-size: 12px;
        color: #6b7280;
        line-height: 1.4;
        padding: 0.25rem 0;
        background: transparent;
        border: none;
        cursor: pointer;
        transition: color 0.2s ease;
        font-family: inherit;
    }

    .rj-faq-sidebar-item:hover {
        color: #a5b4fc;
    }

    .rj-faq-sidebar-item-active {
        color: #a5b4fc;
    }

    .rj-faq-sidebar-num {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 9px;
        color: #4b5563;
        margin-right: 0.5rem;
    }

    .rj-faq-items {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rj-faq-item {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .rj-faq-item:hover {
        border-color: rgba(255, 255, 255, 0.12);
    }

    .rj-faq-item-open {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 0 30px -8px rgba(99, 102, 241, 0.4);
    }

    .rj-faq-question {
        width: 100%;
        text-align: left;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        background: transparent;
        border: none;
        color: inherit;
        font-family: inherit;
    }
    @media (min-width: 768px) {
        .rj-faq-question { padding: 1.5rem 1.75rem; }
    }

    .rj-faq-num {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 11px;
        font-weight: 700;
        color: rgba(129, 140, 248, 0.8);
        letter-spacing: 0.1em;
        width: 2rem;
        flex-shrink: 0;
    }

    .rj-faq-qtext {
        flex: 1;
        font-size: 15px;
        font-weight: 600;
        color: #fff;
        line-height: 1.4;
        padding-right: 0.5rem;
    }
    @media (min-width: 768px) {
        .rj-faq-qtext { font-size: 16px; }
    }

    .rj-faq-icon {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.04);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: background 0.3s ease;
    }

    .rj-faq-question:hover .rj-faq-icon {
        background: rgba(99, 102, 241, 0.15);
    }

    .rj-faq-icon i {
        font-size: 11px;
        color: #9ca3af;
        transition: all 0.3s ease;
    }

    .rj-faq-question:hover .rj-faq-icon i {
        color: #a5b4fc;
    }

    .rj-faq-icon-rot {
        transform: rotate(45deg);
        color: #a5b4fc !important;
    }

    .rj-faq-answer-wrap {
        padding: 0 1.5rem 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    @media (min-width: 768px) {
        .rj-faq-answer-wrap { padding: 0 1.75rem 1.75rem; padding-top: 1.25rem; }
    }

    .rj-faq-answer {
        margin-left: 3rem;
        font-size: 14px;
        line-height: 1.7;
        color: #9ca3af;
    }
    @media (min-width: 768px) {
        .rj-faq-answer { font-size: 15px; }
    }

    .rj-faq-answer p { margin-bottom: 0.75rem; }
    .rj-faq-answer p:last-child { margin-bottom: 0; }
    .rj-faq-answer strong { color: #fff; font-weight: 600; }
    .rj-faq-answer a {
        color: #818cf8;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .rj-faq-answer ul, .rj-faq-answer ol {
        padding-left: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .rj-faq-answer ul { list-style: disc; }
    .rj-faq-answer ol { list-style: decimal; }
    .rj-faq-answer li { margin-bottom: 0.25rem; }

    .rj-faq-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        margin-top: 2rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 9999px;
    }

    .rj-faq-filter-tag i {
        color: #818cf8;
        font-size: 11px;
    }

    .rj-faq-filter-tag span {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #6b7280;
    }

    .rj-faq-filter-tag strong {
        color: #818cf8;
        font-weight: 600;
        text-transform: none;
        letter-spacing: normal;
    }

    .rj-faq-empty {
        max-width: 28rem;
        margin: 0 auto;
        text-align: center;
        padding: 4rem 0;
    }

    .rj-faq-empty-icon {
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

    .rj-faq-empty-title {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .rj-faq-empty-text {
        font-size: 12px;
        color: #4b5563;
    }

    .rj-faq-cta {
        max-width: 48rem;
        margin: 0 auto;
        text-align: center;
    }

    .rj-faq-cta-title {
        font-size: clamp(1.5rem, 3vw, 2.25rem);
        font-weight: 900;
        color: #fff;
        letter-spacing: -0.02em;
        line-height: 1.1;
        margin: 0 0 1rem;
    }

    .rj-faq-cta-text {
        font-size: 0.9375rem;
        color: #6b7280;
        max-width: 32rem;
        margin: 0 auto 2rem;
        line-height: 1.7;
    }

    .rj-faq-cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.625rem;
        background: #fff;
        color: #05030f;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.875rem 1.75rem;
        border-radius: 9999px;
        transition: all 300ms ease;
        text-decoration: none;
    }

    .rj-faq-cta-btn:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.35);
    }

    .rj-faq-cta-btn i {
        font-size: 0.75rem;
        transition: transform 300ms ease;
    }

    .rj-faq-cta-btn:hover i {
        transform: translateX(4px);
    }
</style>

@endsection

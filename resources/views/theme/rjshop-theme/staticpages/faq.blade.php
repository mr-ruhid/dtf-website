@php
    $title = $widget->getSetting('title', 'Frequently Asked Questions');
    $subtitle = $widget->getSetting('subtitle', 'Quick answers to common questions');
    $limit = (int) $widget->getSetting('limit', 6);
    $buttonText = $widget->getSetting('button_text');
    $buttonUrl = $widget->getSetting('button_url');

    $faqs = \App\Models\Faq::where('status', 1)
        ->orderBy('sort_order')
        ->limit($limit)
        ->get();
@endphp

@if($faqs->count())
<section class="rj-fp-section">
    <div class="rj-fp-grid-bg"></div>
    <div class="rj-fp-orb rj-fp-orb-a"></div>
    <div class="rj-fp-orb rj-fp-orb-b"></div>

    <div class="rj-fp-inner">
        <div class="rj-fp-content">

            <div class="rj-fp-head">
                @if($title)
                    <h2 class="rj-fp-title">{{ $title }}</h2>
                @endif

                @if($subtitle)
                    <p class="rj-fp-subtitle">{{ $subtitle }}</p>
                @endif
            </div>

            <div class="rj-fp-list" x-data="{ open: null }">
                @foreach($faqs as $index => $faq)
                    <div class="rj-fp-item" :class="open === {{ $index }} ? 'rj-fp-item-open' : ''">

                        <button type="button"
                                @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="rj-fp-question">

                            <span class="rj-fp-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>

                            <span class="rj-fp-qtext">{{ $faq->question }}</span>

                            <span class="rj-fp-icon">
                                <i class="fa-solid fa-plus" :class="open === {{ $index }} ? 'rj-fp-icon-rot' : ''"></i>
                            </span>
                        </button>

                        <div x-show="open === {{ $index }}" x-collapse x-cloak>
                            <div class="rj-fp-answer-wrap">
                                <div class="rj-fp-answer">
                                    {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li><h3><h4><blockquote>'))) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($buttonText && $buttonUrl)
                <div class="rj-fp-actions">
                    <a href="{{ $buttonUrl }}" class="rj-fp-btn">
                        <span>{{ $buttonText }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endif

        </div>
    </div>
</section>

<style>
    .rj-fp-section {
        position: relative;
        background: #05030f;
        color: #fff;
        overflow: hidden;
        padding: 4rem 0;
    }
    @media (min-width: 768px) {
        .rj-fp-section { padding: 6rem 0; }
    }

    .rj-fp-grid-bg {
        position: absolute;
        inset: 0;
        opacity: 0.025;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-fp-orb {
        position: absolute;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        filter: blur(120px);
        pointer-events: none;
    }
    .rj-fp-orb-a {
        top: 0;
        left: 25%;
        background: rgba(99, 102, 241, 0.07);
    }
    .rj-fp-orb-b {
        bottom: 0;
        right: 25%;
        background: rgba(236, 72, 153, 0.07);
    }

    .rj-fp-inner {
        position: relative;
        max-width: 80rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) {
        .rj-fp-inner { padding: 0 3rem; }
    }

    .rj-fp-content {
        max-width: 48rem;
        margin: 0 auto;
    }

    .rj-fp-head {
        text-align: center;
        margin-bottom: 3rem;
    }

    .rj-fp-title {
        font-size: clamp(1.5rem, 3.5vw, 3rem);
        font-weight: 900;
        line-height: 1.08;
        letter-spacing: -0.02em;
        color: #fff;
        margin: 0 0 0.75rem;
    }

    .rj-fp-subtitle {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.7;
        max-width: 40rem;
        margin: 0 auto;
        font-weight: 300;
    }
    @media (min-width: 768px) {
        .rj-fp-subtitle { font-size: 1rem; }
    }

    .rj-fp-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rj-fp-item {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .rj-fp-item:hover {
        border-color: rgba(255, 255, 255, 0.12);
    }

    .rj-fp-item-open {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 0 30px -8px rgba(99, 102, 241, 0.4);
    }

    .rj-fp-question {
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
        .rj-fp-question { padding: 1.5rem 1.75rem; }
    }

    .rj-fp-num {
        font-family: ui-monospace, SFMono-Regular, monospace;
        font-size: 11px;
        font-weight: 700;
        color: rgba(129, 140, 248, 0.8);
        letter-spacing: 0.1em;
        width: 2rem;
        flex-shrink: 0;
    }

    .rj-fp-qtext {
        flex: 1;
        font-size: 15px;
        font-weight: 600;
        color: #fff;
        line-height: 1.4;
        padding-right: 0.5rem;
    }
    @media (min-width: 768px) {
        .rj-fp-qtext { font-size: 16px; }
    }

    .rj-fp-icon {
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

    .rj-fp-question:hover .rj-fp-icon {
        background: rgba(99, 102, 241, 0.15);
    }

    .rj-fp-icon i {
        font-size: 11px;
        color: #9ca3af;
        transition: all 0.3s ease;
    }

    .rj-fp-question:hover .rj-fp-icon i {
        color: #a5b4fc;
    }

    .rj-fp-icon-rot {
        transform: rotate(45deg);
        color: #a5b4fc !important;
    }

    .rj-fp-answer-wrap {
        padding: 0 1.5rem 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.25rem;
        margin-top: 0;
    }
    @media (min-width: 768px) {
        .rj-fp-answer-wrap { padding: 0 1.75rem 1.75rem; padding-top: 1.25rem; }
    }

    .rj-fp-answer {
        margin-left: 3rem;
        font-size: 14px;
        line-height: 1.7;
        color: #9ca3af;
    }
    @media (min-width: 768px) {
        .rj-fp-answer { font-size: 15px; }
    }

    .rj-fp-answer p { margin-bottom: 0.75rem; }
    .rj-fp-answer p:last-child { margin-bottom: 0; }
    .rj-fp-answer strong { color: #fff; font-weight: 600; }
    .rj-fp-answer a {
        color: #818cf8;
        text-decoration: underline;
        text-underline-offset: 2px;
    }
    .rj-fp-answer ul, .rj-fp-answer ol {
        padding-left: 1.25rem;
        margin-bottom: 0.75rem;
    }
    .rj-fp-answer ul { list-style: disc; }
    .rj-fp-answer ol { list-style: decimal; }
    .rj-fp-answer li { margin-bottom: 0.25rem; }

    .rj-fp-actions {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .rj-fp-btn {
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

    .rj-fp-btn:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.35);
    }

    .rj-fp-btn i {
        font-size: 0.75rem;
        transition: transform 300ms ease;
    }

    .rj-fp-btn:hover i {
        transform: translateX(4px);
    }
</style>
@endif

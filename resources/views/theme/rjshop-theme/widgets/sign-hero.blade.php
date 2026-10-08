@php
    $eyebrow = $widget->getSetting('eyebrow', 'Large-Format Print');
    $title = $widget->getSetting('title', "Custom Signs, Vinyl\n& Banners");
    $subtitle = $widget->getSetting('subtitle', "Send the artwork. We print it.\nYou collect.");

    $imgUrl = function ($path) {
        if (!$path) return null;
        return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
    };

    $mainImageUrl = $imgUrl($widget->getSetting('main_image'));
    $image2Url = $imgUrl($widget->getSetting('image_2'));
    $image3Url = $imgUrl($widget->getSetting('image_3'));

    $step1 = $widget->getSetting('step1_text', 'Send the file');
    $step2 = $widget->getSetting('step2_text', 'We print it');
    $step3 = $widget->getSetting('step3_text', 'You collect');
@endphp

<section class="rj-sh" id="rjHero">
    <div class="rj-sh-inner">

        <div class="rj-sh-grid">

            <div class="rj-sh-text">
                @if($eyebrow)
                    <div class="rj-sh-eyebrow">{{ $eyebrow }}</div>
                @endif

                @if($title)
                    <h1 class="rj-sh-title">{!! nl2br(e($title)) !!}</h1>
                @endif

                @if($subtitle)
                    <p class="rj-sh-subtitle">{!! nl2br(e($subtitle)) !!}</p>
                @endif
            </div>

            <div class="rj-sh-gallery">
                <div class="rj-sh-gallery-main">
                    @if($mainImageUrl)
                        <img src="{{ $mainImageUrl }}" alt="{{ $eyebrow }}">
                    @else
                        <div class="rj-sh-placeholder">
                            <i class="fa-regular fa-image"></i>
                            <span>Main image</span>
                        </div>
                    @endif
                </div>

                <div class="rj-sh-gallery-side">
                    <div class="rj-sh-gallery-small">
                        @if($image2Url)
                            <img src="{{ $image2Url }}" alt="">
                        @else
                            <div class="rj-sh-placeholder">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif
                    </div>

                    <div class="rj-sh-gallery-small">
                        @if($image3Url)
                            <img src="{{ $image3Url }}" alt="">
                        @else
                            <div class="rj-sh-placeholder">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        @if($step1 || $step2 || $step3)
            <div class="rj-sh-steps">
                @if($step1)
                    <div class="rj-sh-step">
                        <span class="rj-sh-step-num">1</span>
                        <span class="rj-sh-step-text">{{ $step1 }}</span>
                    </div>
                @endif
                @if($step2)
                    <div class="rj-sh-step">
                        <span class="rj-sh-step-num">2</span>
                        <span class="rj-sh-step-text">{{ $step2 }}</span>
                    </div>
                @endif
                @if($step3)
                    <div class="rj-sh-step">
                        <span class="rj-sh-step-num">3</span>
                        <span class="rj-sh-step-text">{{ $step3 }}</span>
                    </div>
                @endif
            </div>
        @endif

    </div>
</section>

<style>
    .rj-sh {
        position: relative;
        background: #faf8f5;
        color: #111;
        padding: 2.5rem 0 3rem;
    }
    @media (min-width: 768px) { .rj-sh { padding: 4rem 0 4.5rem; } }

    .rj-sh-inner {
        max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sh-inner { padding: 0 3rem; } }

    .rj-sh-grid {
        display: grid; grid-template-columns: 1fr; gap: 2.5rem;
        align-items: center;
        margin-bottom: 3rem;
    }
    @media (min-width: 900px) {
        .rj-sh-grid {
            grid-template-columns: 1fr 1.15fr;
            gap: 4rem;
            margin-bottom: 4rem;
        }
    }

    .rj-sh-eyebrow {
        display: inline-block;
        font-family: ui-monospace, "SF Mono", Menlo, monospace;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.22em;
        color: #ea580c;
        margin-bottom: 1.5rem;
    }

    .rj-sh-title {
        font-size: clamp(2.25rem, 5.5vw, 4.25rem);
        font-weight: 900;
        line-height: 1.02;
        letter-spacing: -0.035em;
        color: #111;
        margin: 0 0 1.5rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .rj-sh-subtitle {
        font-size: clamp(1rem, 1.4vw, 1.25rem);
        color: #4b5563;
        line-height: 1.55;
        margin: 0;
        max-width: 28rem;
        font-weight: 400;
    }

    .rj-sh-gallery {
        display: grid;
        grid-template-columns: 1.55fr 1fr;
        gap: 0.75rem;
        aspect-ratio: 16 / 11;
        min-height: 280px;
    }
    @media (min-width: 768px) {
        .rj-sh-gallery { gap: 0.875rem; min-height: 400px; }
    }

    .rj-sh-gallery-main {
        border-radius: 10px;
        overflow: hidden;
        background: #ece7dd;
        position: relative;
    }

    .rj-sh-gallery-side {
        display: grid;
        grid-template-rows: 1fr 1fr;
        gap: 0.75rem;
    }
    @media (min-width: 768px) {
        .rj-sh-gallery-side { gap: 0.875rem; }
    }

    .rj-sh-gallery-small {
        border-radius: 10px;
        overflow: hidden;
        background: #ece7dd;
        position: relative;
    }

    .rj-sh-gallery img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sh-gallery-main:hover img,
    .rj-sh-gallery-small:hover img {
        transform: scale(1.04);
    }

    .rj-sh-placeholder {
        width: 100%; height: 100%;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 0.5rem;
        color: #b8b0a3;
        font-size: 1.75rem;
    }
    .rj-sh-placeholder span {
        font-size: 11px;
        font-family: ui-monospace, monospace;
        text-transform: uppercase;
        letter-spacing: 0.15em;
    }

    .rj-sh-steps {
        display: grid; grid-template-columns: 1fr; gap: 1.25rem;
        padding-top: 2rem;
        border-top: 1px solid #e5e1d9;
    }
    @media (min-width: 768px) {
        .rj-sh-steps {
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            padding-top: 2.5rem;
        }
    }

    .rj-sh-step {
        display: flex; align-items: center; gap: 0.875rem;
    }

    .rj-sh-step-num {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: #ea580c;
        color: #fff;
        font-weight: 900;
        font-size: 15px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .rj-sh-step-text {
        font-size: 1.0625rem;
        font-weight: 700;
        color: #111;
        letter-spacing: -0.01em;
        line-height: 1.3;
    }
</style>

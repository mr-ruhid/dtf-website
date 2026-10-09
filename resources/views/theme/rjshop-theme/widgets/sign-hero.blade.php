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

<section class="q-widget rj-sh" id="rjHero">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a"></div>
    <div class="q-widget-orb-b"></div>

    <div class="q-widget-inner rj-sh-pad">

        <div class="rj-sh-layout">

            <div class="rj-sh-text">
                @if($eyebrow)
                    <div class="q-widget-eyebrow">
                        <span class="q-widget-eyebrow-line"></span>
                        <span class="q-widget-eyebrow-text">{{ $eyebrow }}</span>
                    </div>
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
                            <span>Main Image</span>
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
    .rj-sh-pad {
        padding-top: 2.5rem;
        padding-bottom: 3rem;
    }
    @media (min-width: 768px) {
        .rj-sh-pad { padding-top: 4rem; padding-bottom: 4.5rem; }
    }

    .rj-sh-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2.5rem;
        align-items: center;
        margin-bottom: 3rem;
    }
    @media (min-width: 900px) {
        .rj-sh-layout {
            grid-template-columns: 1fr 1.35fr;
            gap: 3.5rem;
            margin-bottom: 4rem;
        }
    }

    .rj-sh-title {
        font-size: clamp(2.25rem, 5vw, 4rem);
        font-weight: 900;
        line-height: 1.02;
        letter-spacing: -0.035em;
        color: var(--q-text);
        margin: 0 0 1.5rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .rj-sh-subtitle {
        font-size: clamp(1rem, 1.4vw, 1.125rem);
        color: var(--q-text-dim);
        line-height: 1.6;
        margin: 0;
        max-width: 26rem;
        font-weight: 300;
    }

    .rj-sh-gallery {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 0.75rem;
    }
    @media (min-width: 768px) {
        .rj-sh-gallery { gap: 0.875rem; }
    }

    .rj-sh-gallery-main {
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--q-border);
        position: relative;
        transition: all 400ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sh-gallery-main:hover {
        border-color: var(--q-border-accent);
        box-shadow: 0 20px 48px -16px rgba(var(--q-accent-1-rgb), 0.35);
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
        aspect-ratio: 4 / 3;
        border-radius: 14px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--q-border);
        position: relative;
        transition: all 400ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sh-gallery-small:hover {
        border-color: var(--q-border-accent);
        box-shadow: 0 20px 48px -16px rgba(var(--q-accent-1-rgb), 0.35);
    }

    .rj-sh-gallery img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sh-gallery-main:hover img,
    .rj-sh-gallery-small:hover img {
        transform: scale(1.05);
    }

    .rj-sh-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--q-text-dark);
        font-size: 1.5rem;
        background:
            linear-gradient(135deg, rgba(var(--q-accent-1-rgb), 0.06), rgba(var(--q-accent-3-rgb), 0.04));
    }
    .rj-sh-placeholder span {
        font-size: 10px;
        font-family: ui-monospace, SFMono-Regular, monospace;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--q-text-dim);
    }

    .rj-sh-steps {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        padding-top: 2rem;
        border-top: 1px solid var(--q-border);
    }
    @media (min-width: 768px) {
        .rj-sh-steps {
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            padding-top: 2.5rem;
        }
    }

    .rj-sh-step {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }

    .rj-sh-step-num {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--q-accent-1), var(--q-accent-2));
        color: #fff;
        font-weight: 900;
        font-size: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        box-shadow:
            0 8px 24px -8px rgba(var(--q-accent-1-rgb), 0.7),
            0 0 0 1px rgba(var(--q-accent-1-rgb), 0.2);
    }

    .rj-sh-step-text {
        font-size: 1.0625rem;
        font-weight: 700;
        color: var(--q-text);
        letter-spacing: -0.01em;
        line-height: 1.3;
    }

    @media (max-width: 640px) {
        .rj-sh-gallery {
            grid-template-columns: 1fr 1fr;
        }
        .rj-sh-gallery-side {
            grid-template-rows: auto auto;
        }
    }
</style>

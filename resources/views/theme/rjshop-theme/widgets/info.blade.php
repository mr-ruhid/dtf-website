@php
    $title = $widget->getSetting('title');
    $content = $widget->getSetting('content');
    $buttonText = $widget->getSetting('button_text');
    $buttonUrl = $widget->getSetting('button_url');
@endphp

@if($title || $content || $buttonText)
<section class="q-widget py-16 md:py-24">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a"></div>
    <div class="q-widget-orb-b"></div>

    <div class="q-widget-inner">

        <div class="max-w-4xl mx-auto">

            @if($title)
                <div class="q-widget-head q-widget-head-center mb-8">
                    <h2 class="q-widget-title">{{ $title }}</h2>
                </div>
            @endif

            <div class="relative bg-white/[0.015] border border-white/[0.07] rounded-2xl p-6 md:p-10 rj-info-card">

                @if($content)
                    <div class="rj-info-content q-widget-content">
                        {!! $content !!}
                    </div>
                @endif

                @if($buttonText && $buttonUrl)
                    <div class="mt-8 flex justify-center">
                        <button type="button"
                                onclick="(function(btn){ var wrap = btn.closest('.rj-info-card'); var body = wrap.querySelector('.rj-info-content'); body.classList.toggle('rj-info-expanded'); var lbl = btn.querySelector('span'); lbl.textContent = body.classList.contains('rj-info-expanded') ? 'Read Less' : 'Read More'; btn.querySelector('i').style.transform = body.classList.contains('rj-info-expanded') ? 'rotate(180deg)' : 'rotate(0deg)'; })(this)"
                                class="q-widget-btn-outline">
                            <span>{{ $buttonText }}</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                    </div>
                @endif

            </div>

        </div>

    </div>
</section>

<style>
    .rj-info-content {
        position: relative;
        max-height: 320px;
        overflow: hidden;
        transition: max-height 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .rj-info-content::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 140px;
        background: linear-gradient(to bottom, transparent, #05030f);
        pointer-events: none;
        transition: opacity 0.4s ease;
    }

    .rj-info-content.rj-info-expanded {
        max-height: 8000px;
    }

    .rj-info-content.rj-info-expanded::after {
        opacity: 0;
    }

    .rj-info-card .q-widget-btn-outline i {
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
</style>
@endif

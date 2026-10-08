@php
    $eyebrow = $widget->getSetting('eyebrow', 'Custom Signage Studio');
    $title = $widget->getSetting('title', 'Signs that make your brand unmissable');
    $titleHighlight = $widget->getSetting('title_highlight', 'unmissable');
    $description = $widget->getSetting('description', 'Premium vinyl, banners and fully custom signs — designed, printed and installed by professionals.');

    $btn1Text = $widget->getSetting('btn1_text', 'Get a Free Quote');
    $btn1Url = $widget->getSetting('btn1_url', '/contact-us');
    $btn2Text = $widget->getSetting('btn2_text', 'View Portfolio');
    $btn2Url = $widget->getSetting('btn2_url', '#portfolio');

    $stats = [];
    for ($i = 1; $i <= 3; $i++) {
        $v = $widget->getSetting("stat{$i}_value");
        $l = $widget->getSetting("stat{$i}_label");
        if ($v || $l) {
            $stats[] = ['value' => $v, 'label' => $l];
        }
    }

    $cards = [];
    for ($i = 1; $i <= 3; $i++) {
        $icon = $widget->getSetting("card{$i}_icon");
        $t = $widget->getSetting("card{$i}_title");
        $m = $widget->getSetting("card{$i}_meta");
        if ($icon || $t) {
            $cards[] = ['icon' => $icon ?: 'fa-solid fa-cube', 'title' => $t, 'meta' => $m];
        }
    }

    $titleHtml = e($title);
    if ($titleHighlight) {
        $titleHtml = str_replace(
            e($titleHighlight),
            '<span class="rj-sh-grad">' . e($titleHighlight) . '</span>',
            $titleHtml
        );
    }
@endphp

<section class="rj-sh" id="rjHero">
    <div class="rj-sh-grid-bg"></div>
    <div class="rj-sh-orb rj-sh-orb-a"></div>
    <div class="rj-sh-orb rj-sh-orb-b"></div>

    <div class="rj-sh-inner">
        <div class="rj-sh-grid">

            <div class="rj-sh-text">
                @if($eyebrow)
                    <div class="rj-sh-eyebrow">
                        <span class="rj-sh-eyebrow-line"></span>
                        <span class="rj-sh-eyebrow-text">{{ $eyebrow }}</span>
                    </div>
                @endif

                @if($title)
                    <h1 class="rj-sh-title">{!! $titleHtml !!}</h1>
                @endif

                @if($description)
                    <p class="rj-sh-desc">{{ $description }}</p>
                @endif

                <div class="rj-sh-actions">
                    @if($btn1Text)
                        <a href="{{ $btn1Url }}" class="rj-sh-btn rj-sh-btn-primary">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                            <span>{{ $btn1Text }}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @endif

                    @if($btn2Text)
                        <a href="{{ $btn2Url }}" class="rj-sh-btn rj-sh-btn-outline">
                            <i class="fa-solid fa-images"></i>
                            <span>{{ $btn2Text }}</span>
                        </a>
                    @endif
                </div>

                @if(count($stats))
                    <div class="rj-sh-stats">
                        @foreach($stats as $index => $stat)
                            @if($index > 0)
                                <div class="rj-sh-stat-div"></div>
                            @endif
                            <div class="rj-sh-stat">
                                <span class="rj-sh-stat-value">{{ $stat['value'] }}</span>
                                <span class="rj-sh-stat-label">{{ $stat['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="rj-sh-visual">
                @if(count($cards))
                    @foreach($cards as $index => $card)
                        <div class="rj-sh-card rj-sh-card-{{ $index + 1 }}">
                            <div class="rj-sh-card-icon">
                                <i class="{{ $card['icon'] }}"></i>
                            </div>
                            @if($card['title'])
                                <p class="rj-sh-card-title">{{ $card['title'] }}</p>
                            @endif
                            @if($card['meta'])
                                <p class="rj-sh-card-meta">{{ $card['meta'] }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif

                <div class="rj-sh-mockup">
                    <div class="rj-sh-mockup-inner">
                        <div class="rj-sh-mockup-sign">
                            <span>YOUR BRAND</span>
                        </div>
                        <div class="rj-sh-mockup-sub">Premium Vinyl Signage</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    .rj-sh {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 2rem 0 5rem;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sh { padding: 3rem 0 7rem; } }

    .rj-sh-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-sh-orb {
        position: absolute; width: 500px; height: 500px; border-radius: 50%;
        filter: blur(140px); pointer-events: none;
    }
    .rj-sh-orb-a { top: 0; left: 15%; background: rgba(99, 102, 241, 0.09); }
    .rj-sh-orb-b { bottom: 0; right: 15%; background: rgba(236, 72, 153, 0.08); }

    .rj-sh-inner {
        position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sh-inner { padding: 0 3rem; } }

    .rj-sh-grid {
        display: grid; grid-template-columns: 1fr; gap: 3rem;
        align-items: center;
    }
    @media (min-width: 900px) {
        .rj-sh-grid { grid-template-columns: 1.15fr 1fr; gap: 4rem; }
    }

    .rj-sh-eyebrow {
        display: inline-flex; align-items: center; gap: 0.75rem;
        margin-bottom: 1rem;
    }
    .rj-sh-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-sh-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }

    .rj-sh-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900; line-height: 1.05; letter-spacing: -0.03em;
        color: #fff; margin: 0 0 1.25rem;
    }
    .rj-sh-grad {
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .rj-sh-desc {
        font-size: 1.0625rem; color: #9ca3af; line-height: 1.75;
        margin: 0 0 2rem; max-width: 32rem;
    }

    .rj-sh-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 2.5rem; }

    .rj-sh-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem; font-weight: 600; font-size: 0.9375rem;
        padding: 1rem 1.75rem; border-radius: 9999px;
        text-decoration: none; transition: all 0.3s ease;
        cursor: pointer; border: none; font-family: inherit;
    }
    .rj-sh-btn i { font-size: 12px; }
    .rj-sh-btn-primary {
        background: #fff; color: #05030f;
    }
    .rj-sh-btn-primary:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.4);
        transform: translateY(-1px);
    }
    .rj-sh-btn-primary i:last-child { transition: transform 0.3s; }
    .rj-sh-btn-primary:hover i:last-child { transform: translateX(4px); }
    .rj-sh-btn-outline {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .rj-sh-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .rj-sh-stats {
        display: flex; align-items: center; gap: 1.5rem;
        padding-top: 1.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        flex-wrap: wrap;
    }
    .rj-sh-stat { display: flex; flex-direction: column; gap: 2px; }
    .rj-sh-stat-value {
        font-family: ui-monospace, monospace;
        font-size: 1.5rem; font-weight: 900;
        color: #fff; letter-spacing: -0.02em;
    }
    .rj-sh-stat-label {
        font-family: ui-monospace, monospace;
        font-size: 9px; text-transform: uppercase;
        letter-spacing: 0.15em; color: #6b7280;
    }
    .rj-sh-stat-div { width: 1px; height: 32px; background: rgba(255, 255, 255, 0.08); }

    .rj-sh-visual {
        position: relative;
        aspect-ratio: 1;
        min-height: 320px;
    }

    .rj-sh-mockup {
        position: absolute;
        inset: 8% 12%;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(236, 72, 153, 0.06));
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }
    .rj-sh-mockup::before {
        content: '';
        position: absolute;
        inset: -20px;
        border-radius: 28px;
        border: 1px dashed rgba(99, 102, 241, 0.15);
    }
    .rj-sh-mockup-inner { text-align: center; padding: 2rem; }
    .rj-sh-mockup-sign {
        display: inline-block;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        border-radius: 12px;
        font-weight: 900;
        font-size: 1.25rem;
        letter-spacing: 0.15em;
        color: #fff;
        box-shadow: 0 20px 50px -10px rgba(168, 85, 247, 0.5);
        margin-bottom: 1rem;
    }
    .rj-sh-mockup-sub {
        font-family: ui-monospace, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3em;
        color: #818cf8;
    }

    .rj-sh-card {
        position: absolute;
        background: rgba(10, 7, 21, 0.9);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 160px;
        z-index: 2;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6);
        animation: rj-sh-float 6s ease-in-out infinite;
    }
    .rj-sh-card-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(236, 72, 153, 0.2));
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 13px;
        margin-bottom: 4px;
    }
    .rj-sh-card-title {
        font-size: 13px; font-weight: 700; color: #fff;
        margin: 0;
    }
    .rj-sh-card-meta {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #818cf8;
        margin: 0; letter-spacing: 0.05em;
    }
    .rj-sh-card-1 { top: 5%; left: -5%; animation-delay: 0s; }
    .rj-sh-card-2 { top: 42%; right: -8%; animation-delay: 2s; }
    .rj-sh-card-3 { bottom: 5%; left: 8%; animation-delay: 4s; }

    @keyframes rj-sh-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
</style>

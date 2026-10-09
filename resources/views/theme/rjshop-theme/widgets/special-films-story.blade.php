@php
    $stats = $widget->getSetting('stats', [
        ['value' => '310°F', 'sub' => '155°C', 'label' => 'Temperature'],
        ['value' => 'Medium', 'sub' => '', 'label' => 'Pressure'],
        ['value' => '12–15 sec', 'sub' => '', 'label' => 'Press time'],
        ['value' => '~5 sec', 'sub' => '', 'label' => 'Then peel'],
        ['value' => '5–10 sec', 'sub' => '', 'label' => 'Second press'],
    ]);

    $storyEyebrow = $widget->getSetting('story_eyebrow', 'THE STORY');
    $storyTitle = $widget->getSetting('story_title', 'Want it. Press it. Send the file.');

    $steps = $widget->getSetting('steps', [
        [
            'title' => 'You wanted sparkle',
            'description' => 'Glitter DTF is a standard DTF transfer with a durable glitter layer built into the print. It does not flake like loose glitter vinyl. Chunky sparkle stays in the film - no glitter fallout on the press. Dance, cheer, birthday, and statement apparel.',
        ],
        [
            'title' => 'You press it the way you already know',
            'description' => 'Same settings as standard DTF: 310°F / 155°C, medium pressure, 12–15 seconds. Peel after about 5 seconds, then a second press of 5–10 seconds. Works on cotton, blends, and the blanks you already buy from us.',
        ],
        [
            'title' => 'Then you send the art',
            'description' => 'Upload your art on the Glitter DTF Gang Sheet and we print it the same day. Glitter DTF is the only specialty film we sell right now. No minimums.',
        ],
    ]);

    if (!is_array($stats)) $stats = [];
    if (!is_array($steps)) $steps = [];
@endphp

<section class="rj-sfs">
    <div class="rj-sfs-inner">

        @if(count($stats))
            <div class="rj-sfs-stats">
                @foreach($stats as $stat)
                    <div class="rj-sfs-stat">
                        <p class="rj-sfs-stat-value">{{ $stat['value'] ?? '' }}</p>
                        @if(!empty($stat['sub']))
                            <p class="rj-sfs-stat-sub">{{ $stat['sub'] }}</p>
                        @endif
                        @if(!empty($stat['label']))
                            <p class="rj-sfs-stat-label">{{ $stat['label'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        @if($storyEyebrow || $storyTitle || count($steps))
            <div class="rj-sfs-story">
                @if($storyEyebrow)
                    <p class="rj-sfs-eyebrow">{{ $storyEyebrow }}</p>
                @endif

                @if($storyTitle)
                    <h2 class="rj-sfs-title">{{ $storyTitle }}</h2>
                @endif

                @if(count($steps))
                    <ol class="rj-sfs-steps">
                        @foreach($steps as $i => $step)
                            <li class="rj-sfs-step">
                                <div class="rj-sfs-step-num">{{ $i + 1 }}</div>
                                <div class="rj-sfs-step-body">
                                    @if(!empty($step['title']))
                                        <h3 class="rj-sfs-step-title">{{ $step['title'] }}</h3>
                                    @endif
                                    @if(!empty($step['description']))
                                        <p class="rj-sfs-step-desc">{{ $step['description'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        @endif

    </div>
</section>

<style>
    .rj-sfs {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 4rem 0 4rem;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sfs { padding: 5rem 0 5rem; } }

    .rj-sfs-inner {
        max-width: 76rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sfs-inner { padding: 0 2.5rem; } }

    .rj-sfs-stats {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.875rem;
        margin-bottom: 4rem;
    }
    @media (min-width: 640px) { .rj-sfs-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 900px) { .rj-sfs-stats { grid-template-columns: repeat(5, 1fr); gap: 1rem; } }

    .rj-sfs-stat {
        padding: 1.125rem 1.25rem 1.25rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sfs-stat:hover {
        transform: translateY(-3px);
        border-color: rgba(168, 85, 247, 0.4);
        box-shadow: 0 12px 32px -16px rgba(168, 85, 247, 0.5);
        background: rgba(168, 85, 247, 0.03);
    }

    .rj-sfs-stat-value {
        font-size: 1.375rem;
        font-weight: 800;
        color: #fff;
        margin: 0;
        letter-spacing: -0.02em;
        line-height: 1.1;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .rj-sfs-stat-sub {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        color: #a855f7;
        margin: 0.25rem 0 0;
        font-weight: 600;
    }

    .rj-sfs-stat-label {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        color: #6b7280;
        margin: 0.5rem 0 0;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .rj-sfs-story {
        max-width: 56rem;
    }

    .rj-sfs-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #c084fc;
        margin: 0 0 0.875rem;
        font-weight: 700;
    }

    .rj-sfs-title {
        font-size: clamp(1.75rem, 3.2vw, 2.5rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.025em;
        color: #fff;
        margin: 0 0 3rem;
    }

    .rj-sfs-steps {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 2.5rem;
        position: relative;
    }

    .rj-sfs-step {
        display: grid;
        grid-template-columns: 48px 1fr;
        gap: 1.25rem;
        align-items: flex-start;
        position: relative;
    }

    .rj-sfs-step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 23px;
        top: 52px;
        bottom: -2.5rem;
        width: 2px;
        background: linear-gradient(180deg, rgba(168, 85, 247, 0.4), rgba(168, 85, 247, 0.05));
    }

    .rj-sfs-step-num {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #a855f7, #ec4899);
        color: #fff;
        font-family: -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 18px;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow:
            0 8px 24px -8px rgba(168, 85, 247, 0.7),
            0 0 0 1px rgba(168, 85, 247, 0.25);
        flex-shrink: 0;
        position: relative;
        z-index: 2;
    }

    .rj-sfs-step-body {
        padding-top: 0.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.625rem;
    }

    .rj-sfs-step-title {
        font-size: 1.25rem;
        font-weight: 800;
        color: #fff;
        letter-spacing: -0.02em;
        margin: 0;
        line-height: 1.25;
    }

    .rj-sfs-step-desc {
        font-size: 0.9375rem;
        color: #9ca3af;
        line-height: 1.65;
        margin: 0;
        font-weight: 400;
    }

    @media (max-width: 640px) {
        .rj-sfs-step {
            grid-template-columns: 40px 1fr;
            gap: 1rem;
        }
        .rj-sfs-step-num {
            width: 40px;
            height: 40px;
            font-size: 16px;
        }
        .rj-sfs-step:not(:last-child)::after {
            left: 19px;
            top: 44px;
        }
        .rj-sfs-step-title { font-size: 1.0625rem; }
    }
</style>

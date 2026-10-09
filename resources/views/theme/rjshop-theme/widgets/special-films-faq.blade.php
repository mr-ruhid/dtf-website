@php
    $eyebrow = $widget->getSetting('eyebrow', 'THE SAME ANSWERS, IF YOU NEED THEM');
    $title = $widget->getSetting('title', 'Open a question');

    $items = $widget->getSetting('items', [
        ['question' => 'What specialty film does DTF Town sell?', 'answer' => ''],
        ['question' => 'What is Glitter DTF?', 'answer' => ''],
        ['question' => 'How do I press Glitter DTF?', 'answer' => ''],
        ['question' => 'Where is Glitter DTF printed?', 'answer' => ''],
    ]);

    if (!is_array($items)) $items = [];
@endphp

<section class="rj-sff">
    <div class="rj-sff-inner">

        @if($eyebrow || $title)
            <div class="rj-sff-head">
                @if($eyebrow)
                    <p class="rj-sff-eyebrow">{{ $eyebrow }}</p>
                @endif
                @if($title)
                    <h2 class="rj-sff-title">{{ $title }}</h2>
                @endif
            </div>
        @endif

        @if(count($items))
            <div class="rj-sff-list" x-data="{ open: null }">
                @foreach($items as $index => $item)
                    @if(!empty($item['question']))
                        <div class="rj-sff-item" :class="open === {{ $index }} ? 'is-open' : ''">
                            <button type="button"
                                    class="rj-sff-q"
                                    @click="open = open === {{ $index }} ? null : {{ $index }}">
                                <span class="rj-sff-q-text">{{ $item['question'] }}</span>
                                <span class="rj-sff-q-icon">
                                    <i class="fa-solid fa-plus" :class="open === {{ $index }} ? 'is-rot' : ''"></i>
                                </span>
                            </button>

                            @if(!empty($item['answer']))
                                <div x-show="open === {{ $index }}" x-collapse x-cloak class="rj-sff-a-wrap">
                                    <div class="rj-sff-a">{{ $item['answer'] }}</div>
                                </div>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

    </div>
</section>

<style>
    .rj-sff {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 4rem 0 5rem;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sff { padding: 5rem 0 6rem; } }

    .rj-sff-inner {
        max-width: 76rem;
        margin: 0 auto;
        padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-sff-inner { padding: 0 2.5rem; } }

    .rj-sff-head {
        margin-bottom: 2.5rem;
        max-width: 48rem;
    }

    .rj-sff-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #c084fc;
        margin: 0 0 0.875rem;
        font-weight: 700;
    }

    .rj-sff-title {
        font-size: clamp(1.75rem, 3.2vw, 2.5rem);
        font-weight: 900;
        line-height: 1.1;
        letter-spacing: -0.025em;
        color: #fff;
        margin: 0;
    }

    .rj-sff-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .rj-sff-item {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sff-item:hover {
        border-color: rgba(168, 85, 247, 0.35);
        background: rgba(168, 85, 247, 0.03);
    }
    .rj-sff-item.is-open {
        border-color: rgba(168, 85, 247, 0.5);
        background: rgba(168, 85, 247, 0.05);
        box-shadow: 0 12px 32px -16px rgba(168, 85, 247, 0.4);
    }

    .rj-sff-q {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.25rem 1.5rem;
        background: transparent;
        border: none;
        color: #fff;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        text-align: left;
        cursor: pointer;
        transition: color 0.2s;
    }
    .rj-sff-q:hover { color: #e9d5ff; }

    .rj-sff-q-text {
        flex: 1;
        min-width: 0;
    }

    .rj-sff-q-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(168, 85, 247, 0.12);
        border: 1px solid rgba(168, 85, 247, 0.25);
        color: #c084fc;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 11px;
        transition: all 0.3s;
    }
    .rj-sff-q-icon i {
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-sff-q-icon i.is-rot {
        transform: rotate(45deg);
    }
    .rj-sff-item.is-open .rj-sff-q-icon {
        background: linear-gradient(135deg, #a855f7, #ec4899);
        border-color: transparent;
        color: #fff;
    }

    .rj-sff-a-wrap {
        padding: 0 1.5rem 1.25rem;
    }

    .rj-sff-a {
        font-size: 0.9375rem;
        color: #9ca3af;
        line-height: 1.7;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        padding-top: 1rem;
    }

    @media (max-width: 640px) {
        .rj-sff-q { padding: 1.125rem 1.25rem; font-size: 0.9375rem; }
        .rj-sff-a-wrap { padding: 0 1.25rem 1.125rem; }
    }
</style>

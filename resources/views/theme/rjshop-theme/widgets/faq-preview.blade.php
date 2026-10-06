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
<section class="q-widget py-16 md:py-24">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a"></div>
    <div class="q-widget-orb-b"></div>

    <div class="q-widget-inner">

        <div class="max-w-3xl mx-auto">

            @if($title || $subtitle)
                <div class="q-widget-head q-widget-head-center">
                    @if($title)
                        <h2 class="q-widget-title">{{ $title }}</h2>
                    @endif

                    @if($subtitle)
                        <p class="q-widget-subtitle mx-auto">{{ $subtitle }}</p>
                    @endif
                </div>
            @endif

            <div class="space-y-3" x-data="{ open: null }">
                @foreach($faqs as $index => $faq)
                    <div class="rj-faq bg-white/[0.015] border border-white/[0.07] rounded-xl overflow-hidden transition-all duration-300"
                         :class="open === {{ $index }} ? 'bg-white/[0.03] border-indigo-500/30' : 'hover:border-white/[0.12]'">

                        <button type="button"
                                @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="w-full text-left px-5 md:px-6 py-4 md:py-5 flex items-center gap-4 group">

                            <span class="font-mono text-[10px] font-bold text-indigo-400/80 tracking-widest shrink-0 w-6">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <span class="flex-1 text-[15px] md:text-base font-semibold text-white leading-snug pr-2">
                                {{ $faq->question }}
                            </span>

                            <span class="shrink-0 w-7 h-7 rounded-full bg-white/[0.04] group-hover:bg-indigo-500/15 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-plus text-[10px] text-gray-400 group-hover:text-indigo-300 transition-all duration-300"
                                   :class="open === {{ $index }} ? 'rotate-45 text-indigo-300' : ''"></i>
                            </span>
                        </button>

                        <div x-show="open === {{ $index }}"
                             x-collapse
                             x-cloak>
                            <div class="px-5 md:px-6 pb-5 md:pb-6 pt-0">
                                <div class="pl-10 pr-4 text-[14px] leading-relaxed text-gray-400 rj-faq-answer q-widget-content">
                                    {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li><h3><h4><blockquote>'))) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($buttonText && $buttonUrl)
                <div class="q-widget-actions">
                    <a href="{{ $buttonUrl }}" class="q-widget-btn-outline">
                        <span>{{ $buttonText }}</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            @endif

        </div>

    </div>
</section>

<style>
    .rj-faq-answer p { margin-bottom: 0.75rem; }
    .rj-faq-answer p:last-child { margin-bottom: 0; }
</style>
@endif

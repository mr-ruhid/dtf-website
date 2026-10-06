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
<section class="relative bg-[#f8f7f4] overflow-hidden py-16 md:py-24">
    <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-indigo-100/30 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-pink-100/30 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-3xl mx-auto px-6">

        <div class="text-center mb-12">
            @if($title)
                <h2 class="text-2xl md:text-4xl lg:text-[2.5rem] font-black leading-[1.15] tracking-tight text-gray-900 mb-3">
                    {{ $title }}
                </h2>
            @endif

            @if($subtitle)
                <p class="text-sm md:text-base text-gray-500 leading-relaxed max-w-xl mx-auto font-light">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="space-y-3" x-data="{ open: null }">
            @foreach($faqs as $index => $faq)
                <div class="rj-faq bg-white border border-gray-200/70 rounded-xl overflow-hidden transition-all duration-300 hover:border-gray-300"
                     :class="open === {{ $index }} ? 'shadow-[0_4px_24px_-8px_rgba(0,0,0,0.1)] border-gray-300' : ''">

                    <button type="button"
                            @click="open = open === {{ $index }} ? null : {{ $index }}"
                            class="w-full text-left px-5 md:px-6 py-4 md:py-5 flex items-center gap-4 group">

                        <span class="font-mono text-[10px] font-bold text-indigo-400 tracking-widest shrink-0 w-6">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </span>

                        <span class="flex-1 text-[15px] md:text-base font-semibold text-gray-900 leading-snug pr-2">
                            {{ $faq->question }}
                        </span>

                        <span class="shrink-0 w-7 h-7 rounded-full bg-gray-50 group-hover:bg-indigo-50 flex items-center justify-center transition-colors">
                            <i class="fa-solid fa-plus text-[10px] text-gray-400 group-hover:text-indigo-600 transition-all duration-300"
                               :class="open === {{ $index }} ? 'rotate-45 text-indigo-600' : ''"></i>
                        </span>
                    </button>

                    <div x-show="open === {{ $index }}"
                         x-collapse
                         x-cloak>
                        <div class="px-5 md:px-6 pb-5 md:pb-6 pt-0">
                            <div class="pl-10 pr-4 text-[14px] leading-relaxed text-gray-600 rj-faq-answer">
                                {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li><h3><h4><blockquote>'))) !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($buttonText && $buttonUrl)
            <div class="mt-10 flex justify-center">
                <a href="{{ $buttonUrl }}"
                   class="group inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-6 py-3 rounded-lg transition-all duration-300">
                    <span>{{ $buttonText }}</span>
                    <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        @endif

    </div>
</section>

<style>
    .rj-faq-answer p { margin-bottom: 0.75rem; }
    .rj-faq-answer p:last-child { margin-bottom: 0; }
    .rj-faq-answer strong { color: #111827; font-weight: 600; }
    .rj-faq-answer a { color: #4f46e5; text-decoration: underline; text-underline-offset: 2px; }
    .rj-faq-answer ul,
    .rj-faq-answer ol { padding-left: 1.25rem; margin-bottom: 0.75rem; }
    .rj-faq-answer ul { list-style: disc; }
    .rj-faq-answer ol { list-style: decimal; }
    .rj-faq-answer li { margin-bottom: 0.25rem; }
</style>
@endif

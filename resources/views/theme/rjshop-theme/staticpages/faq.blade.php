@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<section class="q-widget pt-20 pb-16 md:pt-28 md:pb-20">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a"></div>
    <div class="q-widget-orb-b"></div>

    <div class="q-widget-inner">
        <div class="max-w-3xl mx-auto text-center">
            <div class="q-widget-eyebrow justify-center">
                <span class="q-widget-eyebrow-line"></span>
                <span class="q-widget-eyebrow-text">Help Center</span>
                <span class="q-widget-eyebrow-line"></span>
            </div>

            <h1 class="q-widget-title mb-4">
                {{ $page->title ?? 'Frequently Asked Questions' }}
            </h1>

            @if($page->excerpt)
                <p class="q-widget-subtitle mx-auto">{{ $page->excerpt }}</p>
            @else
                <p class="q-widget-subtitle mx-auto">
                    Everything you need to know about our products and services.
                </p>
            @endif

            @if($faqs->count())
                <div class="mt-8 max-w-xl mx-auto relative" x-data="{ q: '' }">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                        <input type="text"
                               x-model="q"
                               placeholder="Search questions..."
                               class="w-full pl-12 pr-4 py-3.5 bg-white/[0.03] border border-white/10 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500/60 focus:bg-indigo-500/[0.03] focus:shadow-[0_0_20px_rgba(99,102,241,0.25)] transition-all">
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

@if($faqs->count())
<section class="q-widget pb-20 md:pb-28">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a" style="opacity:.5;"></div>
    <div class="q-widget-orb-b" style="opacity:.5;"></div>

    <div class="q-widget-inner">
        <div class="max-w-4xl mx-auto" x-data="{ open: 0, q: '' }">

            <div class="space-y-3">
                @foreach($faqs as $index => $faq)
                    <div class="rj-faq bg-white/[0.015] border border-white/[0.07] rounded-xl overflow-hidden transition-all duration-300"
                         x-show="q === '' || '{{ strtolower(addslashes($faq->question)) }}'.includes(q.toLowerCase())"
                         :class="open === {{ $index }} ? 'bg-white/[0.03] border-indigo-500/30' : 'hover:border-white/[0.12]'">

                        <button type="button"
                                @click="open = open === {{ $index }} ? null : {{ $index }}"
                                class="w-full text-left px-5 md:px-7 py-5 md:py-6 flex items-center gap-4 group">

                            <span class="font-mono text-[11px] font-bold text-indigo-400/80 tracking-widest shrink-0 w-8">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </span>

                            <span class="flex-1 text-[15px] md:text-base font-semibold text-white leading-snug pr-2">
                                {{ $faq->question }}
                            </span>

                            <span class="shrink-0 w-8 h-8 rounded-full bg-white/[0.04] group-hover:bg-indigo-500/15 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-plus text-[11px] text-gray-400 group-hover:text-indigo-300 transition-all duration-300"
                                   :class="open === {{ $index }} ? 'rotate-45 text-indigo-300' : ''"></i>
                            </span>
                        </button>

                        <div x-show="open === {{ $index }}"
                             x-collapse
                             x-cloak>
                            <div class="px-5 md:px-7 pb-6 md:pb-7 pt-0 border-t border-white/[0.05]">
                                <div class="pl-12 pt-5 pr-2 text-[14px] md:text-[15px] leading-relaxed text-gray-400 rj-faq-answer q-widget-content">
                                    {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li><h3><h4><blockquote>'))) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div x-show="q !== ''" x-cloak class="mt-6 text-center font-mono text-[10px] uppercase tracking-[0.25em] text-gray-600">
                Filtering by "<span x-text="q" class="text-indigo-400"></span>"
            </div>

        </div>
    </div>
</section>
@else
<section class="q-widget pb-24">
    <div class="q-widget-inner">
        <div class="max-w-md mx-auto text-center py-16">
            <div class="w-16 h-16 rounded-full bg-white/[0.03] border border-white/10 flex items-center justify-center mx-auto mb-5">
                <i class="fa-regular fa-circle-question text-gray-600 text-2xl"></i>
            </div>
            <p class="text-gray-500 text-sm mb-1">No FAQs yet</p>
            <p class="text-gray-600 text-xs">Questions will appear here once added.</p>
        </div>
    </div>
</section>
@endif

<section class="q-widget py-16 md:py-20">
    <div class="q-widget-inner">
        <div class="max-w-3xl mx-auto bg-gradient-to-br from-white/[0.02] to-white/[0.01] border border-white/[0.07] rounded-2xl p-8 md:p-10 text-center">
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-indigo-500/30">
                <i class="fa-solid fa-headset text-white text-lg"></i>
            </div>
            <h3 class="text-xl md:text-2xl font-bold text-white mb-2">Still have questions?</h3>
            <p class="text-sm text-gray-500 mb-6 max-w-md mx-auto">Our support team is here to help. Reach out and we'll get back to you quickly.</p>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ url('contact-us') }}" class="q-widget-btn">
                    <span>Contact Support</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
                <a href="https://wa.me/994506636031" target="_blank" class="q-widget-btn-outline">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .rj-faq-answer p { margin-bottom: 0.75rem; }
    .rj-faq-answer p:last-child { margin-bottom: 0; }
    .rj-faq-answer ul,
    .rj-faq-answer ol { padding-left: 1.25rem; margin-bottom: 0.75rem; }
    .rj-faq-answer ul { list-style: disc; }
    .rj-faq-answer ol { list-style: decimal; }
    .rj-faq-answer li { margin-bottom: 0.25rem; }
</style>

@endsection

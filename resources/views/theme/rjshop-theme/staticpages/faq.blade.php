@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<section class="q-widget pt-20 pb-12 md:pt-28 md:pb-16">
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
        </div>
    </div>
</section>

@if($faqs->count())
<section class="q-widget pb-20 md:pb-28" x-data="{ open: 0, q: '' }">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a" style="opacity:.4;"></div>
    <div class="q-widget-orb-b" style="opacity:.4;"></div>

    <div class="q-widget-inner">

        <div class="max-w-xl mx-auto mb-10 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 text-sm pointer-events-none"></i>
            <input type="text"
                   x-model="q"
                   placeholder="Search questions..."
                   class="w-full pl-12 pr-12 py-3.5 bg-white/[0.03] border border-white/10 rounded-xl text-sm text-white placeholder-gray-600 focus:outline-none focus:border-indigo-500/60 focus:bg-indigo-500/[0.03] focus:shadow-[0_0_20px_rgba(99,102,241,0.25)] transition-all">
            <button x-show="q !== ''" x-cloak type="button" @click="q = ''"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-6 h-6 rounded-full bg-white/[0.06] hover:bg-white/[0.12] flex items-center justify-center text-gray-400 hover:text-white transition">
                <i class="fa-solid fa-xmark text-[10px]"></i>
            </button>
        </div>

        <div class="max-w-4xl mx-auto">

            <div class="grid grid-cols-12 gap-6">

                <aside class="hidden lg:block lg:col-span-3">
                    <div class="sticky top-28">
                        <div class="font-mono text-[10px] uppercase tracking-[0.3em] text-indigo-400 mb-4">// Index</div>
                        <nav class="space-y-1 border-l border-white/[0.07] pl-4">
                            @foreach($faqs as $index => $faq)
                                <button type="button"
                                        x-show="q === '' || '{{ strtolower(addslashes($faq->question)) }}'.includes(q.toLowerCase())"
                                        @click="open = {{ $index }}; document.getElementById('faq-{{ $index }}').scrollIntoView({behavior: 'smooth', block: 'center'})"
                                        class="block w-full text-left text-[12px] text-gray-500 hover:text-indigo-300 transition py-1 leading-snug"
                                        :class="open === {{ $index }} ? 'text-indigo-300' : ''">
                                    <span class="font-mono text-[9px] text-gray-600 mr-2">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    {{ Str::limit($faq->question, 40) }}
                                </button>
                            @endforeach
                        </nav>
                    </div>
                </aside>

                <div class="col-span-12 lg:col-span-9">
                    <div class="space-y-3">
                        @foreach($faqs as $index => $faq)
                            <div id="faq-{{ $index }}"
                                 class="rj-faq bg-white/[0.015] border border-white/[0.07] rounded-xl overflow-hidden transition-all duration-300"
                                 x-show="q === '' || '{{ strtolower(addslashes($faq->question)) }}'.includes(q.toLowerCase())"
                                 :class="open === {{ $index }} ? 'bg-white/[0.03] border-indigo-500/30 shadow-[0_0_30px_-8px_rgba(99,102,241,0.4)]' : 'hover:border-white/[0.12]'">

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
                                    <div class="px-5 md:px-7 pb-6 md:pb-7 pt-0">
                                        <div class="pl-12 pt-5 pr-2 border-t border-white/[0.05] text-[14px] md:text-[15px] leading-relaxed text-gray-400 rj-faq-answer q-widget-content">
                                            {!! nl2br(e(strip_tags($faq->answer, '<p><br><strong><em><a><ul><ol><li><h3><h4><blockquote>'))) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div x-show="q !== ''" x-cloak class="mt-8 text-center">
                        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/[0.03] border border-white/10">
                            <i class="fa-solid fa-filter text-indigo-400 text-xs"></i>
                            <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-gray-500">
                                Filtering: <span x-text="q" class="text-indigo-400 normal-case tracking-normal font-semibold"></span>
                            </span>
                        </div>
                    </div>

                    <div x-show="q !== '' && !Array.from(document.querySelectorAll('.rj-faq')).some(el => el.style.display !== 'none')" x-cloak class="mt-8 text-center">
                        <div class="py-12">
                            <div class="w-14 h-14 rounded-full bg-white/[0.03] border border-white/10 flex items-center justify-center mx-auto mb-4">
                                <i class="fa-solid fa-magnifying-glass text-gray-600"></i>
                            </div>
                            <p class="text-sm text-gray-500">No matching questions</p>
                            <p class="text-xs text-gray-600 mt-1">Try a different keyword</p>
                        </div>
                    </div>
                </div>

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
    <div class="q-widget-grid"></div>
    <div class="q-widget-inner">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-flex items-center gap-3 mb-5">
                <span class="q-widget-eyebrow-line"></span>
                <span class="q-widget-eyebrow-text">Still Curious?</span>
                <span class="q-widget-eyebrow-line"></span>
            </div>

            <h3 class="text-2xl md:text-4xl font-black text-white tracking-tight mb-4 leading-tight">
                Didn't find your answer?
            </h3>
            <p class="text-sm md:text-base text-gray-500 mb-8 max-w-lg mx-auto leading-relaxed">
                Our team is ready to help with anything else you need.
            </p>

            <a href="{{ url('contact-us') }}" class="q-widget-btn">
                <span>Contact Us</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
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

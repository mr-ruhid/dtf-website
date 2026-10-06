@php
    $title = $widget->getSetting('title');
    $content = $widget->getSetting('content');
    $buttonText = $widget->getSetting('button_text');
    $buttonUrl = $widget->getSetting('button_url');
@endphp

@if($title || $content || $buttonText)
<section class="relative bg-[#f8f7f4] overflow-hidden py-16 md:py-24">
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-100/40 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-pink-100/30 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-12">

        <div class="rj-info-card bg-white border border-gray-200/70 rounded-2xl p-8 md:p-12 lg:p-14 shadow-[0_4px_40px_-10px_rgba(0,0,0,0.08)]">

            @if($title)
                <h2 class="text-2xl md:text-4xl lg:text-[2.5rem] font-black leading-[1.15] tracking-tight text-gray-900 mb-6 text-center">
                    {{ $title }}
                </h2>
            @endif

            @if($content)
                <div class="rj-info-content">
                    {!! $content !!}
                </div>
            @endif

            @if($buttonText && $buttonUrl)
                <div class="mt-8 flex justify-center">
                    <button type="button" onclick="this.closest('section').querySelector('.rj-info-content').classList.toggle('rj-info-expanded'); this.querySelector('span').textContent = this.querySelector('span').textContent === 'Read More' ? 'Read Less' : 'Read More';"
                            class="rj-info-toggle inline-flex items-center gap-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold px-6 py-3 rounded-lg transition-all duration-300">
                        <span>{{ $buttonText }}</span>
                        <i class="fa-solid fa-chevron-down text-xs transition-transform"></i>
                    </button>
                </div>
            @endif

        </div>

    </div>
</section>

<style>
    .rj-info-content {
        color: #4b5563;
        font-size: 1rem;
        line-height: 1.85;
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
        height: 120px;
        background: linear-gradient(to bottom, transparent, #fff);
        pointer-events: none;
        transition: opacity 0.4s ease;
    }
    .rj-info-content.rj-info-expanded {
        max-height: 5000px;
    }
    .rj-info-content.rj-info-expanded::after {
        opacity: 0;
    }

    .rj-info-toggle i {
        transition: transform 0.3s ease;
    }
    .rj-info-card:has(.rj-info-expanded) .rj-info-toggle i {
        transform: rotate(180deg);
    }

    .rj-info-content h1,
    .rj-info-content h2,
    .rj-info-content h3,
    .rj-info-content h4 {
        color: #111827;
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.25;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .rj-info-content h1 { font-size: 1.75rem; }
    .rj-info-content h2 { font-size: 1.5rem; }
    .rj-info-content h3 { font-size: 1.25rem; margin-top: 2rem; }
    .rj-info-content h4 { font-size: 1.125rem; }
    .rj-info-content p {
        margin-bottom: 1rem;
        color: #4b5563;
    }
    .rj-info-content strong { color: #111827; font-weight: 700; }
    .rj-info-content em { color: #4f46e5; font-style: italic; }
    .rj-info-content a {
        color: #4f46e5;
        text-decoration: underline;
        text-underline-offset: 3px;
        transition: color 0.2s;
    }
    .rj-info-content a:hover { color: #4338ca; }
    .rj-info-content ul,
    .rj-info-content ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
        color: #4b5563;
    }
    .rj-info-content ul { list-style: disc; }
    .rj-info-content ol { list-style: decimal; }
    .rj-info-content li { margin-bottom: 0.35rem; }
    .rj-info-content img {
        border-radius: 0.75rem;
        margin: 1rem 0;
        max-width: 100%;
        height: auto;
        box-shadow: 0 4px 20px -8px rgba(0,0,0,0.15);
    }
    .rj-info-content blockquote {
        border-left: 3px solid #6366f1;
        padding: 0.75rem 1.25rem;
        margin: 1.25rem 0;
        background: rgba(99, 102, 241, 0.05);
        border-radius: 0 0.5rem 0.5rem 0;
        color: #4f46e5;
        font-style: italic;
    }
    .rj-info-content hr {
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(99, 102, 241, 0.3), transparent);
        margin: 1.5rem 0;
    }
</style>
@endif

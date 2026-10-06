@php
    $eyebrow = $widget->getSetting('eyebrow');
    $title = $widget->getSetting('title');
    $subtitle = $widget->getSetting('subtitle');
    $buttonText = $widget->getSetting('button_text');
    $buttonUrl = $widget->getSetting('button_url');
    $items = $widget->getSetting('items', []);
    $count = count($items);
@endphp

@if($count)
<section class="relative bg-[#05030f] text-white overflow-hidden py-16 md:py-20">
    <div class="absolute inset-0 opacity-[0.025]" style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-indigo-600/[0.07] rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-pink-600/[0.07] rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-12">

        <div class="max-w-2xl mb-12 md:mb-14">
            @if($eyebrow)
                <div class="flex items-center gap-3 mb-4">
                    <span class="w-5 h-px bg-pink-400/60"></span>
                    <span class="font-mono text-[9px] uppercase tracking-[0.35em] text-pink-300/90">{{ $eyebrow }}</span>
                </div>
            @endif

            @if($title)
                <h2 class="text-2xl md:text-4xl lg:text-5xl font-black leading-[1.08] tracking-tight mb-3">
                    {{ $title }}
                </h2>
            @endif

            @if($subtitle)
                <p class="text-sm md:text-base text-gray-500 leading-relaxed max-w-xl font-light">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $count >= 5 ? '5' : '4' }} gap-3.5 mb-12">
            @foreach($items as $index => $item)
                <div class="rj-step group relative flex flex-col bg-white/[0.015] border border-white/[0.07] rounded-2xl overflow-hidden transition-all duration-500 hover:border-indigo-500/40 hover:bg-white/[0.03] hover:-translate-y-1">

                    <div class="px-4 pt-4 pb-3 flex items-center gap-2.5">
                        <span class="font-mono text-[9px] font-medium text-indigo-400/80 tracking-widest">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex-1 h-px bg-gradient-to-r from-white/10 to-transparent"></span>
                    </div>

                    <div class="relative aspect-[4/3] mx-4 rounded-lg overflow-hidden bg-[#0a0715] border border-white/5">
                        @if(!empty($item['image']))
                            @php
                                $imgUrl = str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']);
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $item['title'] ?? '' }}" class="rj-step-img w-full h-full object-cover transition-transform duration-[900ms] ease-out">

                            <div class="rj-step-veil absolute inset-0 bg-gradient-to-t from-[#05030f] via-[#05030f]/40 to-transparent opacity-60"></div>

                            <div class="rj-step-shine absolute inset-0 pointer-events-none"></div>

                            <div class="rj-step-corner absolute top-2 right-2 w-6 h-6 pointer-events-none">
                                <span class="absolute top-0 right-0 w-3 h-px bg-indigo-400"></span>
                                <span class="absolute top-0 right-0 w-px h-3 bg-indigo-400"></span>
                            </div>
                            <div class="rj-step-corner absolute bottom-2 left-2 w-6 h-6 pointer-events-none">
                                <span class="absolute bottom-0 left-0 w-3 h-px bg-pink-400"></span>
                                <span class="absolute bottom-0 left-0 w-px h-3 bg-pink-400"></span>
                            </div>

                            <div class="absolute inset-x-0 bottom-0 p-3 translate-y-full opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-500 ease-out">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono text-[8px] uppercase tracking-[0.25em] text-indigo-300">Step {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 8px 2px rgba(52,211,153,0.9);"></span>
                                </div>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/[0.06]">
                                <i class="fa-regular fa-image text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        @if(!empty($item['title']))
                            <h3 class="text-[15px] font-semibold text-white mb-1.5 tracking-tight leading-snug">{{ $item['title'] }}</h3>
                        @endif

                        @if(!empty($item['description']))
                            <p class="text-[12.5px] text-gray-500 leading-relaxed mb-4 flex-1">{{ $item['description'] }}</p>
                        @endif

                        @if(!empty($item['link_text']) && !empty($item['link_url']))
                            <a href="{{ $item['link_url'] }}" class="group/link inline-flex items-center gap-1.5 text-[11px] font-medium text-gray-400 hover:text-indigo-400 transition mt-auto">
                                <span>{{ $item['link_text'] }}</span>
                                <span class="inline-block transition-transform duration-300 group-hover/link:translate-x-0.5">→</span>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($buttonText && $buttonUrl)
            <div class="flex justify-center">
                <a href="{{ $buttonUrl }}"
                   class="group inline-flex items-center gap-2.5 bg-white text-[#05030f] font-semibold text-sm px-7 py-3.5 rounded-full transition-all duration-300 hover:bg-indigo-50 hover:shadow-[0_0_40px_rgba(168,85,247,0.35)]">
                    <span>{{ $buttonText }}</span>
                    <i class="fa-solid fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                </a>
            </div>
        @endif

    </div>
</section>

<style>
    .rj-step-img {
        transform: scale(1);
        filter: saturate(0.85) brightness(0.95);
        transition: transform 900ms cubic-bezier(0.16, 1, 0.3, 1), filter 700ms ease;
    }
    .rj-step:hover .rj-step-img {
        transform: scale(1.08);
        filter: saturate(1.1) brightness(1.05);
    }

    .rj-step-veil {
        transition: opacity 500ms ease;
    }
    .rj-step:hover .rj-step-veil {
        opacity: 0.35;
    }

    .rj-step-shine {
        background: linear-gradient(115deg, transparent 30%, rgba(255,255,255,0.14) 50%, transparent 70%);
        transform: translateX(-120%);
        opacity: 0;
        transition: transform 900ms cubic-bezier(0.16, 1, 0.3, 1), opacity 300ms ease;
    }
    .rj-step:hover .rj-step-shine {
        transform: translateX(120%);
        opacity: 1;
    }

    .rj-step-corner span {
        opacity: 0;
        transition: opacity 400ms ease, transform 400ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-step-corner span:first-child { transform: scaleX(0); transform-origin: right; }
    .rj-step-corner span:last-child { transform: scaleY(0); transform-origin: top; }
    .rj-step:hover .rj-step-corner span { opacity: 1; }
    .rj-step:hover .rj-step-corner span:first-child { transform: scaleX(1); }
    .rj-step:hover .rj-step-corner span:last-child { transform: scaleY(1); }

    .rj-step::before {
        content: '';
        position: absolute;
        inset: -1px;
        border-radius: 16px;
        background: radial-gradient(400px circle at var(--mx, 50%) var(--my, 50%), rgba(99,102,241,0.15), transparent 60%);
        opacity: 0;
        pointer-events: none;
        transition: opacity 400ms ease;
        z-index: 0;
    }
    .rj-step:hover::before { opacity: 1; }
</style>

<script>
(function() {
    document.querySelectorAll('.rj-step').forEach(function(card) {
        card.addEventListener('mousemove', function(e) {
            var r = card.getBoundingClientRect();
            var x = ((e.clientX - r.left) / r.width) * 100;
            var y = ((e.clientY - r.top) / r.height) * 100;
            card.style.setProperty('--mx', x + '%');
            card.style.setProperty('--my', y + '%');
        });
    });
})();
</script>
@endif

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
<section class="relative bg-[#05030f] text-white overflow-hidden py-20 md:py-28">
    <div class="absolute inset-0 opacity-[0.025]" style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-indigo-600/[0.07] rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-pink-600/[0.07] rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-12">

        <div class="max-w-3xl mb-16 md:mb-20">
            @if($eyebrow)
                <div class="flex items-center gap-3 mb-6">
                    <span class="w-6 h-px bg-pink-400/60"></span>
                    <span class="font-mono text-[10px] uppercase tracking-[0.35em] text-pink-300/90">{{ $eyebrow }}</span>
                </div>
            @endif

            @if($title)
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-black leading-[1.05] tracking-tight mb-6">
                    {{ $title }}
                </h2>
            @endif

            @if($subtitle)
                <p class="text-base md:text-lg text-gray-500 leading-relaxed max-w-2xl font-light">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $count >= 5 ? '5' : '4' }} gap-4 mb-16">
            @foreach($items as $index => $item)
                <div class="group relative flex flex-col bg-white/[0.015] border border-white/[0.07] rounded-2xl overflow-hidden hover:border-indigo-500/30 hover:bg-white/[0.03] transition-all duration-500">

                    <div class="px-5 pt-5 pb-4 flex items-center gap-3">
                        <span class="font-mono text-[10px] font-medium text-indigo-400/80 tracking-widest">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="flex-1 h-px bg-gradient-to-r from-white/10 to-transparent"></span>
                    </div>

                    <div class="relative aspect-[4/3] mx-5 rounded-lg overflow-hidden bg-[#0a0715] border border-white/5">
                        @if(!empty($item['image']))
                            @php
                                $imgUrl = str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']);
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition-transform duration-700 ease-out">
                            <div class="absolute inset-0 ring-1 ring-inset ring-white/5 rounded-lg pointer-events-none"></div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/[0.06]">
                                <i class="fa-regular fa-image text-2xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        @if(!empty($item['title']))
                            <h3 class="text-[17px] font-semibold text-white mb-2 tracking-tight leading-snug">{{ $item['title'] }}</h3>
                        @endif

                        @if(!empty($item['description']))
                            <p class="text-[13px] text-gray-500 leading-relaxed mb-5 flex-1">{{ $item['description'] }}</p>
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
@endif

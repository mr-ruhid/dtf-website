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
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 50px 50px;"></div>

    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="max-w-3xl mb-14 md:mb-16">
            @if($eyebrow)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-5">
                    <span class="w-1.5 h-1.5 bg-pink-400 rounded-full" style="box-shadow: 0 0 8px 2px rgba(244,114,182,0.9);"></span>
                    <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-pink-300">{{ $eyebrow }}</span>
                </div>
            @endif

            @if($title)
                <h2 class="text-4xl md:text-6xl lg:text-7xl font-black leading-[1.05] tracking-tight mb-5">
                    {{ $title }}
                </h2>
            @endif

            @if($subtitle)
                <p class="text-lg md:text-xl text-gray-400 leading-relaxed max-w-2xl font-light">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $count >= 5 ? '5' : '4' }} gap-5 mb-14">
            @foreach($items as $index => $item)
                <div class="group relative flex flex-col bg-white/[0.02] border border-white/10 rounded-2xl overflow-hidden hover:border-indigo-500/40 transition-all duration-500 hover:-translate-y-1 hover:bg-white/[0.04]">

                    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-indigo-500/0 to-transparent group-hover:via-indigo-500/60 transition-all duration-500"></div>

                    <div class="relative px-5 pt-5 pb-3 flex items-center justify-between">
                        <div class="w-8 h-8 rounded-full border border-indigo-500/40 bg-indigo-500/10 flex items-center justify-center">
                            <span class="font-mono text-[11px] font-bold text-indigo-300">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        @if(!empty($item['image']))
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 8px 2px rgba(52,211,153,0.7);"></span>
                        @endif
                    </div>

                    <div class="relative aspect-[4/3] mx-5 rounded-xl overflow-hidden border border-white/5 bg-[#0a0715]">
                        @if(!empty($item['image']))
                            @php
                                $imgUrl = str_starts_with($item['image'], 'http') ? $item['image'] : asset('storage/' . $item['image']);
                            @endphp
                            <img src="{{ $imgUrl }}" alt="{{ $item['title'] ?? '' }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#05030f]/60 via-transparent to-transparent"></div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-white/10">
                                <i class="fa-solid fa-image text-4xl"></i>
                            </div>
                        @endif
                    </div>

                    <div class="relative p-5 flex-1 flex flex-col">
                        @if(!empty($item['title']))
                            <h3 class="text-xl font-bold text-white mb-2 tracking-tight">{{ $item['title'] }}</h3>
                        @endif

                        @if(!empty($item['description']))
                            <p class="text-sm text-gray-400 leading-relaxed mb-4 flex-1">{{ $item['description'] }}</p>
                        @endif

                        @if(!empty($item['link_text']) && !empty($item['link_url']))
                            <a href="{{ $item['link_url'] }}" class="group/link inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.2em] text-indigo-400 hover:text-indigo-300 transition mt-auto">
                                <span>{{ $item['link_text'] }}</span>
                                <i class="fa-solid fa-arrow-right text-[9px] group-hover/link:translate-x-1 transition"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if($buttonText && $buttonUrl)
            <div class="flex justify-center">
                <a href="{{ $buttonUrl }}"
                   class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-10 py-5 rounded-full overflow-hidden transition-all duration-300 hover:scale-105"
                   style="box-shadow: 0 0 40px rgba(168,85,247,0.4);">
                    <span class="absolute inset-0 bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>
                    <i class="fa-solid fa-bolt relative z-10"></i>
                    <span class="relative z-10">{{ $buttonText }}</span>
                    <i class="fa-solid fa-arrow-right relative z-10 group-hover:translate-x-1 transition"></i>
                </a>
            </div>
        @endif

    </div>
</section>
@endif

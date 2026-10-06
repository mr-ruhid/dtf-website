@php
    $title = $widget->getSetting('title');
    $subtitle = $widget->getSetting('subtitle');
    $items = $widget->getSetting('items', []);
    $count = count($items);
@endphp

@if($count)
<section class="q-widget py-16 md:py-24">
    <div class="q-widget-grid"></div>
    <div class="q-widget-orb-a"></div>
    <div class="q-widget-orb-b"></div>

    <div class="q-widget-inner">

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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $count >= 5 ? '5' : ($count === 4 ? '4' : '3') }} gap-x-8 gap-y-10">

            @foreach($items as $index => $item)
                <div class="rj-feature group relative text-center">

                    <div class="relative inline-flex items-center justify-center mb-5">
                        <div class="rj-feature-ring absolute inset-0 rounded-full border border-white/[0.06] group-hover:border-indigo-500/40 transition-colors duration-500"></div>
                        <div class="rj-feature-ring-2 absolute inset-1 rounded-full border border-transparent group-hover:border-pink-500/30 transition-colors duration-700"></div>

                        <div class="relative w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500/10 to-pink-500/10 flex items-center justify-center text-indigo-300 transition-transform duration-500 group-hover:scale-110">
                            <i class="fa-solid {{ $item['icon'] ?? 'fa-check' }} text-base transition-colors duration-300 group-hover:text-pink-300"></i>
                        </div>
                    </div>

                    @if(!empty($item['title']))
                        <h3 class="text-base font-bold text-white tracking-tight mb-2 leading-snug">
                            {{ $item['title'] }}
                        </h3>
                    @endif

                    @if(!empty($item['description']))
                        <p class="text-[13px] text-gray-500 leading-relaxed max-w-[240px] mx-auto">
                            {{ $item['description'] }}
                        </p>
                    @endif

                </div>
            @endforeach

        </div>

    </div>
</section>

<style>
    .rj-feature-ring,
    .rj-feature-ring-2 {
        transition: transform 700ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-feature:hover .rj-feature-ring {
        transform: scale(1.35);
    }
    .rj-feature:hover .rj-feature-ring-2 {
        transform: scale(1.15);
    }
</style>
@endif

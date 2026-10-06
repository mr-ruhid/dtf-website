@php
    $title = $widget->getSetting('title');
    $subtitle = $widget->getSetting('subtitle');
    $items = $widget->getSetting('items', []);
    $count = count($items);
@endphp

@if($count)
<section class="relative bg-white overflow-hidden py-16 md:py-24">
    <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-indigo-100/40 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-pink-100/30 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-6 lg:px-12">

        @if($title || $subtitle)
            <div class="max-w-3xl mx-auto text-center mb-14 md:mb-16">
                @if($title)
                    <h2 class="text-2xl md:text-4xl lg:text-5xl font-black leading-[1.1] tracking-tight text-gray-900 mb-4">
                        {{ $title }}
                    </h2>
                @endif

                @if($subtitle)
                    <p class="text-sm md:text-base text-gray-500 leading-relaxed max-w-2xl mx-auto font-light">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-{{ $count >= 5 ? '5' : ($count === 4 ? '4' : '3') }} gap-x-8 gap-y-10">

            @foreach($items as $index => $item)
                <div class="rj-feature group relative text-center">

                    <div class="relative inline-flex items-center justify-center mb-5">
                        <div class="rj-feature-ring absolute inset-0 rounded-full border border-indigo-100 group-hover:border-indigo-300 transition-colors duration-500"></div>
                        <div class="rj-feature-ring-2 absolute inset-1 rounded-full border border-transparent group-hover:border-pink-200/60 transition-colors duration-700"></div>

                        <div class="relative w-12 h-12 rounded-full bg-gradient-to-br from-indigo-50 to-pink-50 flex items-center justify-center text-indigo-600 transition-transform duration-500 group-hover:scale-110">
                            <i class="fa-solid {{ $item['icon'] ?? 'fa-check' }} text-base transition-colors duration-300 group-hover:text-pink-600"></i>
                        </div>
                    </div>

                    @if(!empty($item['title']))
                        <h3 class="text-base font-bold text-gray-900 tracking-tight mb-2 leading-snug">
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

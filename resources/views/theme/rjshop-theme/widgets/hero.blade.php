@php
    $slider = \App\Models\Slider::findByLocation($widget->getSetting('location', 'home_hero'));
@endphp

@if($slider && $slider->activeItems->count())
    <section class="relative overflow-hidden bg-[#05030f]">
        <div x-data="{
                current: 0,
                total: {{ $slider->activeItems->count() }},
                next() { this.current = (this.current + 1) % this.total },
                prev() { this.current = (this.current - 1 + this.total) % this.total },
                goTo(i) { this.current = i }
             }"
             x-init="setInterval(() => next(), 6000)"
             class="relative h-[600px] md:h-[700px]">

            @foreach($slider->activeItems as $index => $item)
                <div x-show="current === {{ $index }}"
                     x-transition:enter="transition ease-out duration-1000"
                     x-transition:enter-start="opacity-0 scale-105"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-700"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-105"
                     class="absolute inset-0">

                    <div class="absolute inset-0">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#05030f] via-transparent to-transparent"></div>
                    </div>

                    <div class="relative h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center">
                        <div class="max-w-2xl {{ $item->text_position === 'center' ? 'mx-auto text-center' : ($item->text_position === 'right' ? 'ml-auto text-right' : '') }}">
                            @if($item->subtitle)
                                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-6">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                                    <span class="text-xs font-mono uppercase tracking-widest text-white">{{ $item->subtitle }}</span>
                                </div>
                            @endif

                            @if($item->title)
                                <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.05] tracking-tight mb-6">
                                    {{ $item->title }}
                                </h1>
                            @endif

                            @if($item->description)
                                <p class="text-lg md:text-xl text-gray-300 leading-relaxed mb-8 max-w-xl {{ $item->text_position === 'center' ? 'mx-auto' : '' }}">
                                    {{ $item->description }}
                                </p>
                            @endif

                            @if($item->button_text && $item->button_link)
                                <a href="{{ $item->button_link }}"
                                   class="group inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full hover:shadow-[0_0_50px_rgba(168,85,247,0.6)] hover:scale-105 transition-all duration-300">
                                    <span>{{ $item->button_text }}</span>
                                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($slider->activeItems->count() > 1)
                <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-3 z-10">
                    @foreach($slider->activeItems as $index => $item)
                        <button @click="goTo({{ $index }})"
                                :class="current === {{ $index }} ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/70'"
                                class="h-1.5 rounded-full transition-all duration-300"
                                aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <button @click="prev()" class="absolute left-4 md:left-8 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 transition z-10" aria-label="Previous">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button @click="next()" class="absolute right-4 md:right-8 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 transition z-10" aria-label="Next">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            @endif
        </div>
    </section>
@endif

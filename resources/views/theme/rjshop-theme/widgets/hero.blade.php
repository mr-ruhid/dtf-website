@php
    $slider = \App\Models\Slider::findByLocation($widget->getSetting('location', 'home_hero'));
@endphp

@if($slider && $slider->activeItems->count())
    @php $items = $slider->activeItems; $total = $items->count(); @endphp

    <section class="relative bg-[#05030f]" style="height: {{ $total * 100 + 100 }}vh;">
        <div class="sticky top-0 h-screen w-full overflow-hidden"
             x-data="{
                current: 0,
                total: {{ $total }},
                progress: 0,
                init() {
                    window.addEventListener('scroll', () => {
                        const rect = this.$el.parentElement.getBoundingClientRect();
                        const total = this.$el.parentElement.offsetHeight - window.innerHeight;
                        const scrolled = -rect.top;
                        const p = Math.max(0, Math.min(1, scrolled / total));
                        this.progress = p;
                        const idx = Math.min(this.total - 1, Math.floor(p * this.total));
                        this.current = idx;
                    });
                }
             }">

            <div class="absolute inset-0">
                <div class="absolute inset-0 opacity-[0.04]" style="background-image: linear-gradient(rgba(99,102,241,0.6) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.6) 1px, transparent 1px); background-size: 60px 60px;"></div>
            </div>

            <div class="absolute top-0 left-0 right-0 h-1 z-30 bg-white/5">
                <div class="h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-150"
                     :style="'width: ' + (progress * 100) + '%'"></div>
            </div>

            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none z-0">
                <div class="relative w-[600px] h-[600px] md:w-[900px] md:h-[900px]">
                    <div class="absolute inset-0 rounded-full border border-indigo-500/10 animate-orbit-slow"></div>
                    <div class="absolute inset-20 rounded-full border border-purple-500/10 animate-orbit-reverse"></div>
                    <div class="absolute inset-40 rounded-full border border-pink-500/10 animate-orbit-slow"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] animate-pulse-slow"></div>
                </div>
            </div>

            <div class="relative h-full w-full z-10">
                @foreach($items as $index => $item)
                    <div x-show="current === {{ $index }}"
                         x-transition:enter="transition ease-out duration-[1200ms]"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition ease-in duration-700"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">

                        <div class="absolute inset-0">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}"
                                 class="w-full h-full object-cover"
                                 :style="'transform: scale(' + (1 + progress * 0.15) + ') translateY(' + (progress * -20) + 'px)'">
                            <div class="absolute inset-0 bg-gradient-to-r from-[#05030f] via-[#05030f]/70 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-[#05030f] via-transparent to-[#05030f]/40"></div>
                        </div>

                        <div class="relative h-full max-w-7xl mx-auto px-6 lg:px-12 flex items-center">
                            <div class="max-w-2xl"
                                 x-show="current === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-1000 delay-300"
                                 x-transition:enter-start="opacity-0 translate-y-12"
                                 x-transition:enter-end="opacity-100 translate-y-0">

                                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-8 font-mono text-[11px] uppercase tracking-[0.25em] text-indigo-300">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_8px_2px_rgba(52,211,153,0.8)]"></span>
                                    <span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if($item->subtitle)
                                        <span class="w-px h-3 bg-white/20"></span>
                                        <span class="text-gray-400">{{ $item->subtitle }}</span>
                                    @endif
                                </div>

                                @if($item->title)
                                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight mb-6">
                                        {{ $item->title }}
                                    </h1>
                                @endif

                                @if($item->description)
                                    <p class="text-lg md:text-xl text-gray-400 leading-relaxed mb-10 max-w-xl font-light">
                                        {{ $item->description }}
                                    </p>
                                @endif

                                @if($item->button_text && $item->button_link)
                                    <div class="flex flex-wrap items-center gap-4">
                                        <a href="{{ $item->button_link }}"
                                           class="group inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full hover:shadow-[0_0_50px_rgba(168,85,247,0.6)] hover:scale-105 transition-all duration-300">
                                            <i class="fa-solid fa-atom group-hover:rotate-180 transition-transform duration-700"></i>
                                            <span>{{ $item->button_text }}</span>
                                            <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                                        </a>
                                    </div>
                                @endif

                                <div class="mt-12 flex items-center gap-6 font-mono text-[10px] text-gray-600 uppercase tracking-[0.3em]">
                                    <span class="flex items-center gap-2">
                                        <span class="w-6 h-px bg-indigo-500"></span>
                                        <span>Scroll to explore</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="absolute right-8 md:right-16 top-1/2 -translate-y-1/2 hidden lg:flex flex-col items-center gap-4 z-20">
                            <div class="font-mono text-[10px] text-gray-600 uppercase tracking-[0.3em] rotate-90 whitespace-nowrap mb-16">
                                Quantum Field
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-3 z-20">
                <div class="flex items-center gap-2">
                    @foreach($items as $index => $item)
                        <button @click="window.scrollTo({top: ({{ $index }} + 1) * window.innerHeight, behavior: 'smooth'})"
                                :class="current === {{ $index }} ? 'w-10 bg-gradient-to-r from-indigo-400 to-pink-400' : 'w-2 bg-white/30 hover:bg-white/60'"
                                class="h-1 rounded-full transition-all duration-500"
                                aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>
                <div class="font-mono text-[9px] text-gray-600 uppercase tracking-[0.3em]">
                    <span x-text="String(Math.round(progress * 100)).padStart(2, '0')"></span>%
                </div>
            </div>

            <div class="absolute bottom-10 right-8 md:right-16 hidden md:flex items-center gap-2 font-mono text-[10px] text-gray-600 uppercase tracking-[0.3em] z-20">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                <span>Live</span>
            </div>
        </div>
    </section>
@endif

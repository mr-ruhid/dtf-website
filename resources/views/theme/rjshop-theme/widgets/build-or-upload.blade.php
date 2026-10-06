@php
    $eyebrow = $widget->getSetting('eyebrow');
    $title = $widget->getSetting('title');
    $subtitle = $widget->getSetting('subtitle');
    $buildCard = $widget->getSetting('build_card', []);
    $uploadCard = $widget->getSetting('upload_card', []);
    $infoCard = $widget->getSetting('info_card', []);

    $imgUrl = function($img) {
        if (!$img) return '';
        return str_starts_with($img, 'http') ? $img : asset('storage/' . $img);
    };
@endphp

<section class="relative bg-[#05030f] text-white overflow-hidden py-16 md:py-20">
    <div class="absolute inset-0 opacity-[0.025]" style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="absolute top-0 left-1/4 w-[400px] h-[400px] bg-indigo-600/[0.07] rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[400px] h-[400px] bg-pink-600/[0.07] rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-6 lg:px-12">

        @if($eyebrow || $title || $subtitle)
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
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            <div class="lg:col-span-5 rj-bou-card group">
                <a href="{{ $buildCard['button_url'] ?? '#' }}" class="block relative h-full">
                    <div class="relative h-full bg-white/[0.015] border border-white/[0.07] rounded-2xl overflow-hidden transition-all duration-500 hover:border-indigo-500/40 hover:bg-white/[0.03]">

                        @if(!empty($buildCard['image']))
                            <div class="relative aspect-[16/10] overflow-hidden">
                                <img src="{{ $imgUrl($buildCard['image']) }}" alt="{{ $buildCard['title'] ?? '' }}"
                                     class="rj-bou-img w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#05030f] via-transparent to-transparent"></div>
                                <div class="rj-bou-shine absolute inset-0 pointer-events-none"></div>
                            </div>
                        @else
                            <div class="aspect-[16/10] bg-gradient-to-br from-indigo-600/10 to-pink-600/10 flex items-center justify-center">
                                <i class="fa-solid fa-wand-magic-sparkles text-white/[0.08] text-6xl"></i>
                            </div>
                        @endif

                        <div class="p-5">
                            @if(!empty($buildCard['badge']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/[0.06] border border-white/10 text-[9px] font-mono uppercase tracking-widest text-gray-400 mb-3">
                                    {{ $buildCard['badge'] }}
                                </span>
                            @endif

                            @if(!empty($buildCard['title']))
                                <h3 class="text-lg md:text-xl font-bold text-white mb-1.5 tracking-tight">{{ $buildCard['title'] }}</h3>
                            @endif

                            @if(!empty($buildCard['description']))
                                <p class="text-[13px] text-gray-500 leading-relaxed mb-4">{{ $buildCard['description'] }}</p>
                            @endif

                            @if(!empty($buildCard['button_text']))
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-300 group-hover:text-indigo-300 transition">
                                    <span>{{ $buildCard['button_text'] }}</span>
                                    <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">→</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="lg:col-span-1 flex items-center justify-center">
                <div class="w-9 h-9 rounded-full bg-white/[0.03] border border-white/10 flex items-center justify-center">
                    <span class="font-mono text-[9px] text-gray-500 uppercase tracking-widest">or</span>
                </div>
            </div>

            <div class="lg:col-span-3 rj-bou-card group">
                <a href="{{ $uploadCard['button_url'] ?? '#' }}" class="block relative h-full">
                    <div class="relative h-full bg-white/[0.015] border border-white/[0.07] rounded-2xl overflow-hidden transition-all duration-500 hover:border-indigo-500/40 hover:bg-white/[0.03]">

                        @if(!empty($uploadCard['image']))
                            <div class="relative aspect-[16/10] overflow-hidden">
                                <img src="{{ $imgUrl($uploadCard['image']) }}" alt="{{ $uploadCard['title'] ?? '' }}"
                                     class="rj-bou-img w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#05030f] via-transparent to-transparent"></div>
                                <div class="rj-bou-shine absolute inset-0 pointer-events-none"></div>
                            </div>
                        @else
                            <div class="aspect-[16/10] bg-gradient-to-br from-purple-600/10 to-pink-600/10 flex items-center justify-center">
                                <i class="fa-solid fa-cloud-arrow-up text-white/[0.08] text-6xl"></i>
                            </div>
                        @endif

                        <div class="p-5">
                            @if(!empty($uploadCard['badge']))
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-white/[0.06] border border-white/10 text-[9px] font-mono uppercase tracking-widest text-gray-400 mb-3">
                                    {{ $uploadCard['badge'] }}
                                </span>
                            @endif

                            @if(!empty($uploadCard['title']))
                                <h3 class="text-lg md:text-xl font-bold text-white mb-1.5 tracking-tight">{{ $uploadCard['title'] }}</h3>
                            @endif

                            @if(!empty($uploadCard['description']))
                                <p class="text-[13px] text-gray-500 leading-relaxed mb-4">{{ $uploadCard['description'] }}</p>
                            @endif

                            @if(!empty($uploadCard['button_text']))
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-gray-300 group-hover:text-indigo-300 transition">
                                    <span>{{ $uploadCard['button_text'] }}</span>
                                    <span class="inline-block transition-transform duration-300 group-hover:translate-x-1">→</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>

            <div class="lg:col-span-3 rj-bou-card rj-bou-info group">
                <div class="relative h-full bg-white/[0.015] border border-white/[0.07] rounded-2xl overflow-hidden p-5 flex flex-col transition-all duration-500 hover:border-amber-500/30 hover:bg-white/[0.03]">

                    @if(!empty($infoCard['badge']))
                        <span class="self-end inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gradient-to-r from-indigo-500 to-pink-500 text-[9px] font-mono uppercase tracking-widest text-white mb-3">
                            {{ $infoCard['badge'] }}
                        </span>
                    @endif

                    @if(!empty($infoCard['number']))
                        <div class="rj-bou-number text-4xl md:text-5xl font-black text-transparent mb-3" style="-webkit-text-stroke: 1px rgba(255,255,255,0.15);">{{ $infoCard['number'] }}</div>
                    @endif

                    @if(!empty($infoCard['title']))
                        <h3 class="text-lg md:text-xl font-bold text-white mb-2 tracking-tight">{{ $infoCard['title'] }}</h3>
                    @endif

                    @if(!empty($infoCard['description']))
                        <p class="text-[13px] text-gray-500 leading-relaxed mt-auto">{{ $infoCard['description'] }}</p>
                    @endif
                </div>
            </div>

        </div>

    </div>
</section>

<style>
    .rj-bou-img {
        transform: scale(1);
        filter: saturate(0.85) brightness(0.95);
        transition: transform 900ms cubic-bezier(0.16, 1, 0.3, 1), filter 700ms ease;
    }
    .rj-bou-card:hover .rj-bou-img {
        transform: scale(1.05);
        filter: saturate(1.1) brightness(1.05);
    }
    .rj-bou-shine {
        background: linear-gradient(115deg, transparent 30%, rgba(255,255,255,0.1) 50%, transparent 70%);
        transform: translateX(-120%);
        opacity: 0;
        transition: transform 900ms cubic-bezier(0.16, 1, 0.3, 1), opacity 300ms ease;
    }
    .rj-bou-card:hover .rj-bou-shine {
        transform: translateX(120%);
        opacity: 1;
    }
    .rj-bou-number {
        font-family: ui-monospace, monospace;
    }
</style>

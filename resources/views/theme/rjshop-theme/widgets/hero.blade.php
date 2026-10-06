@php
    $slider = \App\Models\Slider::findByLocation($widget->getSetting('location', 'home_hero'));
@endphp

@if($slider && $slider->activeItems->count())
    @php
        $items = $slider->activeItems;
        $total = $items->count();
        $lastItem = $items->last();
        $totalScreens = $total + 1;
    @endphp

    <section id="rjHero" class="relative bg-[#05030f]" style="height: {{ $totalScreens * 100 }}vh;">

        <div class="rj-stage sticky top-0 h-screen w-full overflow-hidden">

            {{-- BACKGROUNDS: clip-path circle reveal, stacked --}}
            @foreach($items as $index => $item)
                <div class="rj-bg absolute inset-0" data-index="{{ $index }}" style="z-index: {{ $index + 1 }};">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rj-bg-img w-full h-full object-cover" data-index="{{ $index }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#05030f]/95 via-[#05030f]/55 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#05030f]/80 via-transparent to-transparent"></div>
                </div>
            @endforeach

            <div class="rj-bg absolute inset-0" data-index="{{ $total }}" style="z-index: {{ $total + 1 }};">
                <img src="{{ $lastItem->image_url }}" alt="Design your own" class="rj-bg-img w-full h-full object-cover" data-index="{{ $total }}" style="filter: blur(3px) saturate(1.2);">
                <div class="absolute inset-0 bg-[#05030f]/75"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#05030f]/60 via-[#05030f]/30 to-[#05030f]/85"></div>
            </div>

            {{-- Ambient layers --}}
            <div class="rj-orb rj-orb-a"></div>
            <div class="rj-orb rj-orb-b"></div>
            <div class="rj-spot"></div>
            <div class="rj-grid"></div>
            <canvas id="rjHeroCanvas" class="absolute inset-0 w-full h-full pointer-events-none" style="opacity: 0.55; z-index: 20;"></canvas>
            <div class="rj-curtain"></div>

            {{-- SLIDES --}}
            @foreach($items as $index => $item)
                <div class="rj-slide absolute inset-0 flex items-center" data-index="{{ $index }}">
                    <div class="rj-ghost" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="max-w-7xl mx-auto px-6 lg:px-12 w-full relative">
                        <div class="max-w-2xl">
                            <div class="rj-el rj-badge inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-8">
                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 10px 3px rgba(52,211,153,0.9); animation: rjPulse 2s ease-in-out infinite;"></span>
                                <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-indigo-300">State {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                @if($item->subtitle)
                                    <span class="w-px h-3 bg-white/20"></span>
                                    <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-gray-400">{{ $item->subtitle }}</span>
                                @endif
                            </div>

                            @if($item->title)
                                <h1 data-split class="rj-el rj-title text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight mb-6">{{ $item->title }}</h1>
                            @endif

                            @if($item->description)
                                <p class="rj-el text-lg md:text-xl text-gray-300 leading-relaxed mb-10 max-w-xl font-light">{{ $item->description }}</p>
                            @endif

                            @if($item->button_text && $item->button_link)
                                <a href="{{ $item->button_link }}" class="rj-el rj-btn rj-mag pointer-events-auto inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full">
                                    <i class="fa-solid fa-atom rj-spin-icon"></i>
                                    <span>{{ $item->button_text }}</span>
                                    <i class="fa-solid fa-arrow-right rj-arrow"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="rj-slide rj-last absolute inset-0 flex items-center justify-center" data-index="{{ $total }}">
                <div class="rj-ring" aria-hidden="true"></div>
                <div class="max-w-4xl mx-auto px-6 text-center relative">
                    <div class="rj-el rj-badge inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-6">
                        <span class="w-1.5 h-1.5 bg-pink-400 rounded-full" style="box-shadow: 0 0 10px 3px rgba(244,114,182,0.9); animation: rjPulse 2s ease-in-out infinite;"></span>
                        <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-pink-300">Create Your Own</span>
                    </div>

                    <h2 data-split class="rj-el rj-title text-4xl md:text-6xl lg:text-7xl font-black leading-[1] tracking-tight mb-5 text-white">Design your own <span class="rj-grad">gang sheet</span></h2>

                    <p class="rj-el text-base md:text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto mb-8 font-light">
                        Upload your artwork, arrange it on the canvas, and get instant pricing.
                    </p>

                    <div class="rj-el flex flex-wrap gap-4 justify-center">
                        <a href="{{ url('design') }}" class="rj-btn rj-mag group pointer-events-auto inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full" style="box-shadow: 0 0 40px rgba(168,85,247,0.5);">
                            <i class="fa-solid fa-wand-magic-sparkles group-hover:rotate-12 transition-transform"></i>
                            <span>Start Designing</span>
                            <i class="fa-solid fa-arrow-right rj-arrow"></i>
                        </a>

                        <a href="{{ url('contact-us') }}" class="rj-mag pointer-events-auto inline-flex items-center gap-3 bg-white/5 backdrop-blur-md border border-white/15 text-white font-semibold px-7 py-4 rounded-full hover:bg-white/10 transition-colors">
                            <i class="fa-solid fa-comments"></i>
                            <span>Need Help?</span>
                        </a>
                    </div>

                    <div class="rj-el mt-10 flex flex-wrap items-center justify-center gap-6 md:gap-8 font-mono text-[10px] text-gray-400 uppercase tracking-[0.2em]">
                        <span class="flex items-center gap-2"><i class="fa-solid fa-bolt text-yellow-400"></i> Instant pricing</span>
                        <span class="flex items-center gap-2"><i class="fa-solid fa-cloud-arrow-up text-indigo-400"></i> Any format</span>
                        <span class="flex items-center gap-2"><i class="fa-solid fa-truck-fast text-pink-400"></i> Fast shipping</span>
                    </div>
                </div>
            </div>

            {{-- UI chrome --}}
            <div class="absolute top-0 left-0 right-0 h-[3px] bg-white/5 z-30">
                <div class="rj-progress h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" style="width: 0%; box-shadow: 0 0 14px rgba(168,85,247,0.8);"></div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3 z-30">
                @for($i = 0; $i < $totalScreens; $i++)
                    <button class="rj-dot h-1.5 rounded-full" data-index="{{ $i }}" aria-label="Screen {{ $i + 1 }}"></button>
                @endfor
            </div>

            <div class="absolute bottom-10 right-8 md:right-16 hidden md:flex items-end gap-2 z-30 pointer-events-none">
                <span class="rj-count-wrap"><span class="rj-counter font-mono text-3xl font-black text-white">01</span></span>
                <span class="font-mono text-[11px] text-gray-500 pb-1">/ {{ str_pad($totalScreens, 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="rj-scroll absolute bottom-24 right-1/2 translate-x-1/2 flex flex-col items-center gap-3 z-30 pointer-events-none">
                <span class="font-mono text-[9px] text-gray-400 uppercase tracking-[0.3em]">Scroll</span>
                <div class="rj-scroll-line"></div>
            </div>
        </div>
    </section>

    <style>
        #rjHero { --mx: 0; --my: 0; }

        /* ---------- Backgrounds ---------- */
        #rjHero .rj-bg {
            clip-path: circle(0% at 75% 50%);
            transition: clip-path 1.8s cubic-bezier(0.77, 0, 0.175, 1);
            will-change: clip-path;
        }
        #rjHero .rj-bg.on { clip-path: circle(150% at 75% 50%); }
        #rjHero .rj-bg[data-index="0"] { clip-path: circle(150% at 75% 50%); }
        #rjHero .rj-bg-img {
            --py: 0;
            transform: scale(1.15) translate3d(calc(var(--mx) * -22px), calc(var(--py) * 1px + var(--my) * -14px), 0);
            transition: transform 0.35s ease-out;
        }

        /* ---------- Ambient ---------- */
        #rjHero .rj-orb { position: absolute; border-radius: 9999px; filter: blur(90px); pointer-events: none; z-index: 12; mix-blend-mode: screen; }
        #rjHero .rj-orb-a { width: 38vw; height: 38vw; left: -8vw; top: 5vh; background: rgba(99,102,241,0.35); animation: rjFloatA 14s ease-in-out infinite; }
        #rjHero .rj-orb-b { width: 32vw; height: 32vw; right: -6vw; bottom: -6vh; background: rgba(236,72,153,0.28); animation: rjFloatB 17s ease-in-out infinite; }
        #rjHero .rj-spot {
            position: absolute; inset: 0; z-index: 13; pointer-events: none;
            background: radial-gradient(520px circle at calc(50% + var(--mx) * 50%) calc(50% + var(--my) * 50%), rgba(139,92,246,0.18), transparent 60%);
        }
        #rjHero .rj-grid {
            position: absolute; inset: 0; z-index: 14; pointer-events: none; opacity: 0.5;
            background-image: linear-gradient(rgba(255,255,255,0.035) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.035) 1px, transparent 1px);
            background-size: 64px 64px;
            -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%);
            mask-image: radial-gradient(ellipse at center, #000 30%, transparent 75%);
        }

        /* Transition flash curtain */
        #rjHero .rj-curtain {
            position: absolute; inset: 0; z-index: 21; pointer-events: none; opacity: 0;
            background: linear-gradient(105deg, transparent 30%, rgba(139,92,246,0.35) 50%, transparent 70%);
            transform: translateX(-100%);
        }
        #rjHero .rj-curtain.go { animation: rjSweep 1.6s cubic-bezier(0.77, 0, 0.175, 1); }

        /* ---------- Slides ---------- */
        #rjHero .rj-slide {
            z-index: 25; opacity: 0; visibility: hidden; pointer-events: none; padding: 4.5rem 0 7.5rem;
            transition: opacity 0.7s ease, visibility 0s linear 0.7s;
        }
        #rjHero .rj-slide.active { opacity: 1; visibility: visible; transition-delay: 0s, 0s; }
        #rjHero .rj-slide.active a, #rjHero .rj-slide.active button { pointer-events: auto; }

        #rjHero .rj-el { opacity: 0; transform: translateY(40px); }
        #rjHero .rj-slide.active .rj-el { animation: rjWaveIn 1.4s cubic-bezier(0.16, 1, 0.3, 1) both; animation-delay: calc(var(--d, 0) * 0.18s + 0.4s); }
        #rjHero .rj-slide:not(.active) .rj-el { opacity: 0; }

        /* Split title: words rise from a mask */
        #rjHero .rj-title { opacity: 1; transform: none; }
        #rjHero .rj-slide.active .rj-title { animation: none; }
        #rjHero .rj-w { display: inline-block; overflow: hidden; vertical-align: top; padding: 0.04em 0.04em 0.22em; margin-bottom: -0.18em; margin-right: 0.18em; }
        #rjHero .rj-w > span { display: inline-block; transform: translateY(115%) rotate(6deg); transform-origin: left bottom; }
        #rjHero .rj-slide.active .rj-w > span {
            animation: rjWord 1.4s cubic-bezier(0.16, 1, 0.3, 1) both;
            animation-delay: calc(0.35s + var(--i) * 0.1s);
        }
        #rjHero .rj-grad {
            background: linear-gradient(90deg, #818cf8, #c084fc, #f472b6, #818cf8);
            background-size: 250% 100%;
            -webkit-background-clip: text; background-clip: text; color: transparent;
            animation: rjShift 5s linear infinite;
        }
        #rjHero .rj-grad .rj-w > span { color: transparent; }

        /* Ghost number */
        #rjHero .rj-ghost {
            position: absolute; right: 4vw; top: 50%; transform: translateY(-50%) translateX(80px);
            font-weight: 900; font-size: clamp(14rem, 36vw, 36rem); line-height: 1; letter-spacing: -0.05em;
            color: transparent; -webkit-text-stroke: 1.5px rgba(255,255,255,0.09);
            opacity: 0; pointer-events: none; user-select: none;
        }
        #rjHero .rj-slide.active .rj-ghost { animation: rjGhost 1.8s cubic-bezier(0.16, 1, 0.3, 1) 0.3s both; }

        /* Last-screen ring */
        #rjHero .rj-ring {
            position: absolute; width: min(80vmin, 760px); height: min(80vmin, 760px); border-radius: 9999px;
            border: 1px dashed rgba(168,85,247,0.35); opacity: 0; transform: scale(0.6) rotate(0);
        }
        #rjHero .rj-ring::before, #rjHero .rj-ring::after {
            content: ''; position: absolute; border-radius: 9999px; inset: 8%;
            border: 1px solid rgba(236,72,153,0.25);
        }
        #rjHero .rj-ring::after { inset: 18%; border-style: dotted; border-color: rgba(129,140,248,0.35); }
        #rjHero .rj-slide.active .rj-ring { animation: rjRing 1.8s cubic-bezier(0.16, 1, 0.3, 1) 0.2s both, rjSpin 40s linear 2s infinite; }

        /* ---------- Buttons ---------- */
        #rjHero .rj-btn { position: relative; overflow: hidden; transition: box-shadow 0.3s ease, filter 0.3s ease; }
        #rjHero .rj-btn::after {
            content: ''; position: absolute; top: 0; left: -120%; width: 60%; height: 100%;
            background: linear-gradient(105deg, transparent, rgba(255,255,255,0.45), transparent);
            transform: skewX(-20deg); transition: left 0.7s ease;
        }
        #rjHero .rj-btn:hover::after { left: 140%; }
        #rjHero .rj-btn:hover { filter: brightness(1.12); box-shadow: 0 0 50px rgba(168,85,247,0.7) !important; }
        #rjHero .rj-btn .rj-arrow { transition: transform 0.3s ease; }
        #rjHero .rj-btn:hover .rj-arrow { transform: translateX(6px); }
        #rjHero .rj-btn:hover .rj-spin-icon { animation: rjSpin 1.2s linear infinite; }
        #rjHero .rj-mag { transition: transform 0.25s ease-out, box-shadow 0.3s ease, filter 0.3s ease, background-color 0.3s ease; will-change: transform; }

        /* ---------- Chrome ---------- */
        #rjHero .rj-progress { transition: width 0.15s linear; }
        #rjHero .rj-dot {
            width: 8px; background: rgba(255,255,255,0.2); cursor: pointer;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1), background 0.4s ease, box-shadow 0.4s ease;
        }
        #rjHero .rj-dot:hover { background: rgba(255,255,255,0.45); }
        #rjHero .rj-dot.on { width: 44px; background: linear-gradient(90deg, #818cf8, #ec4899); box-shadow: 0 0 14px rgba(168,85,247,0.7); }
        #rjHero .rj-count-wrap { display: inline-block; line-height: 1; padding: 0.15em 0.1em; }
        #rjHero .rj-counter { display: inline-block; }
        #rjHero .rj-counter.tick { animation: rjTick 0.9s cubic-bezier(0.16, 1, 0.3, 1); }
        #rjHero .rj-scroll { transition: opacity 0.6s ease; }
        #rjHero .rj-scroll-line { position: relative; width: 1px; height: 48px; background: rgba(129,140,248,0.2); overflow: hidden; }
        #rjHero .rj-scroll-line::after {
            content: ''; position: absolute; left: 0; top: -100%; width: 100%; height: 100%;
            background: linear-gradient(to bottom, transparent, #a78bfa, transparent);
            animation: rjDrip 1.8s ease-in-out infinite;
        }

        /* ---------- Keyframes ---------- */
        @keyframes rjSpin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
        @keyframes rjPulse { 0%,100% { opacity: 0.5; transform: scale(1); } 50% { opacity: 1; transform: scale(1.15); } }
        @keyframes rjWaveIn {
            0%   { opacity: 0; transform: translateY(60px) scale(0.92); filter: blur(12px); }
            60%  { filter: blur(0); }
            100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
        }
        @keyframes rjWord { 0% { transform: translateY(115%) rotate(6deg); } 100% { transform: translateY(0) rotate(0); } }
        @keyframes rjGhost { 0% { opacity: 0; transform: translateY(-50%) translateX(120px); } 100% { opacity: 1; transform: translateY(-50%) translateX(0); } }
        @keyframes rjRing { 0% { opacity: 0; transform: scale(0.6) rotate(-40deg); } 100% { opacity: 1; transform: scale(1) rotate(0); } }
        @keyframes rjSweep { 0% { opacity: 1; transform: translateX(-100%); } 100% { opacity: 0; transform: translateX(100%); } }
        @keyframes rjShift { to { background-position: 250% 0; } }
        @keyframes rjTick { 0% { transform: translateY(14px); opacity: 0; } 100% { transform: translateY(0); opacity: 1; } }
        @keyframes rjDrip { 0% { top: -100%; } 100% { top: 100%; } }
        @keyframes rjFloatA { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(8vw, 6vh) scale(1.15); } }
        @keyframes rjFloatB { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-7vw, -8vh) scale(1.1); } }

        @media (max-height: 760px) {
            #rjHero .rj-last h2 { font-size: clamp(2rem, 7vh, 3.5rem); margin-bottom: 0.75rem; }
            #rjHero .rj-last p { margin-bottom: 1rem; font-size: 0.95rem; }
            #rjHero .rj-last .rj-badge { margin-bottom: 0.75rem; }
            #rjHero .rj-last .mt-10 { margin-top: 1.25rem; }
        }

        @media (prefers-reduced-motion: reduce) {
            #rjHero *, #rjHero *::before, #rjHero *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
        }
    </style>

    <script>
    (function() {
        var hero = document.getElementById('rjHero');
        if (!hero) return;

        var bgSlides = hero.querySelectorAll('.rj-bg');
        var bgImages = hero.querySelectorAll('.rj-bg-img');
        var textSlides = hero.querySelectorAll('.rj-slide');
        var dots = hero.querySelectorAll('.rj-dot');
        var progressBar = hero.querySelector('.rj-progress');
        var counter = hero.querySelector('.rj-counter');
        var curtain = hero.querySelector('.rj-curtain');
        var scrollHint = hero.querySelector('.rj-scroll');
        var totalScreens = textSlides.length;
        var current = -1;
        var textTimer, snapTimer;
        var SNAP = false; // true = scroll dayananda ən yaxın ekrana yapışsın

        // Split titles into words (masked rise animation) + stagger index for elements
        hero.querySelectorAll('[data-split]').forEach(function(el) {
            var n = 0;
            function wrap(node) {
                Array.prototype.slice.call(node.childNodes).forEach(function(child) {
                    if (child.nodeType === 3) {
                        var frag = document.createDocumentFragment();
                        child.textContent.split(/\s+/).filter(Boolean).forEach(function(word) {
                            var w = document.createElement('span');
                            w.className = 'rj-w';
                            var s = document.createElement('span');
                            s.style.setProperty('--i', n++);
                            s.textContent = word;
                            w.appendChild(s);
                            frag.appendChild(w);
                        });
                        node.replaceChild(frag, child);
                    } else if (child.nodeType === 1) {
                        wrap(child);
                    }
                });
            }
            wrap(el);
        });
        textSlides.forEach(function(slide) {
            slide.querySelectorAll('.rj-el').forEach(function(el, i) { el.style.setProperty('--d', i); });
        });

        function showSlide(idx) {
            if (idx === current) return;
            var prev = current;

            bgSlides.forEach(function(el, i) { el.classList.toggle('on', i <= idx); });
            // text waits until the slide is stable (no flicker during fast scroll)
            textSlides.forEach(function(el) { el.classList.remove('active'); });
            clearTimeout(textTimer);
            textTimer = setTimeout(function() { textSlides[idx].classList.add('active'); }, prev === -1 ? 0 : 90);
            dots.forEach(function(d, i) { d.classList.toggle('on', i === idx); });

            if (counter) {
                counter.textContent = String(idx + 1).padStart(2, '0');
                counter.classList.remove('tick');
                void counter.offsetWidth;
                counter.classList.add('tick');
            }
            if (scrollHint) scrollHint.style.opacity = idx === totalScreens - 1 ? '0' : '1';
            if (curtain && prev !== -1) {
                curtain.classList.remove('go');
                void curtain.offsetWidth;
                curtain.classList.add('go');
            }
            current = idx;
        }

        // Slides advance one at a time, even if the user scrolls fast
        var target = 0, stepping = false, STEP_MS = 1200;
        function step() {
            if (current === target) { stepping = false; return; }
            stepping = true;
            var next = current === -1 ? target : current + (target > current ? 1 : -1);
            showSlide(next);
            setTimeout(step, STEP_MS);
        }
        function goTo(idx) {
            target = idx;
            if (!stepping) step();
        }

        goTo(0);

        // Scroll-driven progress + parallax
        var ticking = false;
        function onScroll() {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(function() {
                var rect = hero.getBoundingClientRect();
                var scrollable = hero.offsetHeight - window.innerHeight;
                var scrolled = Math.max(0, -rect.top);
                var progress = Math.max(0, Math.min(1, scrolled / scrollable));

                if (progressBar) progressBar.style.width = (progress * 100) + '%';

                var idx = Math.min(totalScreens - 1, Math.floor(progress * totalScreens));
                goTo(idx);

                bgImages.forEach(function(img, i) {
                    var offset = (progress * 100) - (i * (100 / totalScreens));
                    img.style.setProperty('--py', (offset * -0.4).toFixed(2));
                });
                ticking = false;

                // snap to nearest screen when scrolling stops inside the hero
                clearTimeout(snapTimer);
                if (SNAP && rect.top <= 0 && scrolled < scrollable - 2) {
                    snapTimer = setTimeout(function() {
                        var target = hero.offsetTop + (idx / totalScreens) * scrollable + 10;
                        if (Math.abs(window.pageYOffset - target) > 6) {
                            window.scrollTo({ top: target, behavior: 'smooth' });
                        }
                    }, 160);
                }
            });
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var idx = parseInt(this.dataset.index);
                var scrollable = hero.offsetHeight - window.innerHeight;
                var targetScroll = hero.offsetTop + (idx / totalScreens) * scrollable + 10;
                window.scrollTo({ top: targetScroll, behavior: 'smooth' });
            });
        });

        // Mouse: spotlight, parallax, magnetic buttons
        var mouse = { x: -9999, y: -9999 };
        var stage = hero.querySelector('.rj-stage');
        var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (stage && !reduce) {
            stage.addEventListener('mousemove', function(e) {
                var r = stage.getBoundingClientRect();
                var nx = (e.clientX - r.left) / r.width - 0.5;
                var ny = (e.clientY - r.top) / r.height - 0.5;
                hero.style.setProperty('--mx', nx.toFixed(3));
                hero.style.setProperty('--my', ny.toFixed(3));
                mouse.x = e.clientX - r.left;
                mouse.y = e.clientY - r.top;
            });
            stage.addEventListener('mouseleave', function() {
                hero.style.setProperty('--mx', 0);
                hero.style.setProperty('--my', 0);
                mouse.x = mouse.y = -9999;
                hero.querySelectorAll('.rj-mag').forEach(function(b) { b.style.transform = ''; });
            });

            hero.querySelectorAll('.rj-mag').forEach(function(btn) {
                btn.addEventListener('mousemove', function(e) {
                    var r = btn.getBoundingClientRect();
                    var dx = (e.clientX - (r.left + r.width / 2)) * 0.25;
                    var dy = (e.clientY - (r.top + r.height / 2)) * 0.35;
                    btn.style.transform = 'translate(' + dx + 'px,' + dy + 'px)';
                });
                btn.addEventListener('mouseleave', function() { btn.style.transform = ''; });
            });
        }

        // Particle canvas (mouse-reactive)
        var canvas = document.getElementById('rjHeroCanvas');
        if (canvas) {
            var ctx = canvas.getContext('2d');
            var particles = [];
            var w, h, centerX, centerY, time = 0;

            function resize() {
                w = canvas.width = canvas.offsetWidth;
                h = canvas.height = canvas.offsetHeight;
                centerX = w / 2;
                centerY = h / 2;
            }

            function initParticles() {
                particles = [];
                var count = Math.min(70, Math.floor(w * h / 22000));
                for (var i = 0; i < count; i++) {
                    particles.push({
                        x: Math.random() * w, y: Math.random() * h,
                        vx: (Math.random() - 0.5) * 0.4, vy: (Math.random() - 0.5) * 0.4,
                        r: 1, baseR: Math.random() * 2 + 0.5,
                        hue: Math.random() * 80 + 220,
                        pulse: Math.random() * Math.PI * 2, pulseSpeed: Math.random() * 0.03 + 0.01
                    });
                }
            }

            function draw() {
                ctx.clearRect(0, 0, w, h);
                time += 0.005;

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    var dxc = centerX - p.x, dyc = centerY - p.y;
                    var distC = Math.sqrt(dxc * dxc + dyc * dyc);
                    if (distC > 0) {
                        p.vx += (dxc / distC) * 0.002;
                        p.vy += (dyc / distC) * 0.002;
                    }
                    // mouse repulsion
                    var mdx = p.x - mouse.x, mdy = p.y - mouse.y;
                    var md = Math.sqrt(mdx * mdx + mdy * mdy);
                    if (md < 140 && md > 0) {
                        var f = (1 - md / 140) * 0.6;
                        p.vx += (mdx / md) * f;
                        p.vy += (mdy / md) * f;
                    }
                    p.vx *= 0.97; p.vy *= 0.97;
                    p.x += p.vx; p.y += p.vy;

                    if (p.x < 0) { p.x = 0; p.vx *= -1; }
                    if (p.x > w) { p.x = w; p.vx *= -1; }
                    if (p.y < 0) { p.y = 0; p.vy *= -1; }
                    if (p.y > h) { p.y = h; p.vy *= -1; }

                    p.pulse += p.pulseSpeed;
                    p.r = p.baseR * (1 + Math.sin(p.pulse) * 0.4);
                    var distNorm = Math.min(1, distC / (Math.min(w, h) / 2));

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'hsla(' + p.hue + ', 85%, ' + (60 + distNorm * 20) + '%, ' + (0.75 - distNorm * 0.3) + ')';
                    ctx.fill();
                }

                for (var a = 0; a < particles.length; a++) {
                    for (var b = a + 1; b < particles.length; b++) {
                        var dx = particles[a].x - particles[b].x;
                        var dy = particles[a].y - particles[b].y;
                        var dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 130) {
                            ctx.beginPath();
                            ctx.moveTo(particles[a].x, particles[a].y);
                            ctx.lineTo(particles[b].x, particles[b].y);
                            ctx.strokeStyle = 'hsla(250, 85%, 70%, ' + ((1 - dist / 130) * 0.25) + ')';
                            ctx.lineWidth = 0.6;
                            ctx.stroke();
                        }
                    }
                }

                // links from cursor to nearby particles
                if (mouse.x > -9000) {
                    for (var k = 0; k < particles.length; k++) {
                        var ex = particles[k].x - mouse.x, ey = particles[k].y - mouse.y;
                        var ed = Math.sqrt(ex * ex + ey * ey);
                        if (ed < 180) {
                            ctx.beginPath();
                            ctx.moveTo(mouse.x, mouse.y);
                            ctx.lineTo(particles[k].x, particles[k].y);
                            ctx.strokeStyle = 'hsla(320, 90%, 70%, ' + ((1 - ed / 180) * 0.35) + ')';
                            ctx.lineWidth = 0.8;
                            ctx.stroke();
                        }
                    }
                }

                var maxR = Math.max(w, h) * 0.8;
                [[0, 250], [300, 320]].forEach(function(cfg) {
                    var rad = ((time * 200) + cfg[0]) % maxR;
                    var al = Math.max(0, 0.15 - rad / maxR * 0.15);
                    if (al > 0.01) {
                        ctx.beginPath();
                        ctx.arc(centerX, centerY, rad, 0, Math.PI * 2);
                        ctx.strokeStyle = 'hsla(' + cfg[1] + ', 85%, 70%, ' + al + ')';
                        ctx.lineWidth = 2;
                        ctx.stroke();
                    }
                });

                requestAnimationFrame(draw);
            }

            window.addEventListener('resize', function() { resize(); initParticles(); });
            resize();
            initParticles();
            draw();
        }
    })();
    </script>
@endif

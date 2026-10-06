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

    <section id="rjHero" class="relative bg-[#05030f]" style="height: {{ $totalScreens * 140 }}vh;">
        <div class="sticky top-0 h-screen w-full overflow-hidden">

            @foreach($items as $index => $item)
                <article class="rj-slide absolute inset-0" data-mode="{{ $index % 2 === 0 ? 'inset' : 'circle' }}" style="z-index: {{ $index + 1 }};">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rj-image absolute inset-0 w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#05030f]/95 via-[#05030f]/55 to-transparent pointer-events-none"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#05030f]/85 via-transparent to-transparent pointer-events-none"></div>

                    <div class="rj-ghost" aria-hidden="true">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>

                    <div class="rj-content absolute inset-0 flex items-center">
                        <div class="max-w-7xl mx-auto px-6 lg:px-12 w-full">
                            <div class="max-w-2xl">
                                <div data-reveal="0">
                                    <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-8">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 10px 3px rgba(52,211,153,0.9); animation: rjPulse 2s ease-in-out infinite;"></span>
                                        <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-indigo-300">State {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        @if($item->subtitle)
                                            <span class="w-px h-3 bg-white/20"></span>
                                            <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-gray-400">{{ $item->subtitle }}</span>
                                        @endif
                                    </div>
                                </div>

                                @if($item->title)
                                    <h1 data-split class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight mb-6">{{ $item->title }}</h1>
                                @endif

                                @if($item->description)
                                    <div data-reveal="1">
                                        <p class="text-lg md:text-xl text-gray-300 leading-relaxed mb-10 max-w-xl font-light">{{ $item->description }}</p>
                                    </div>
                                @endif

                                @if($item->button_text && $item->button_link)
                                    <div data-reveal="2">
                                        <a href="{{ $item->button_link }}" class="rj-btn pointer-events-auto inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full" style="box-shadow: 0 0 30px rgba(139,92,246,0.4);">
                                            <i class="fa-solid fa-atom"></i>
                                            <span>{{ $item->button_text }}</span>
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="rj-dim absolute inset-0 bg-[#05030f] pointer-events-none"></div>
                </article>
            @endforeach

            <article class="rj-slide absolute inset-0" data-mode="circle" style="z-index: {{ $total + 1 }};">
                <img src="{{ $lastItem->image_url }}" alt="" class="rj-image absolute inset-0 w-full h-full object-cover" style="filter: blur(3px);">
                <div class="absolute inset-0 bg-[#05030f]/75 pointer-events-none"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#05030f]/60 via-[#05030f]/30 to-[#05030f]/85 pointer-events-none"></div>

                <div class="rj-content absolute inset-0 flex items-center justify-center">
                    <div class="text-center px-6 max-w-4xl mx-auto">
                        <div data-reveal="0">
                            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-6">
                                <span class="w-1.5 h-1.5 bg-pink-400 rounded-full" style="box-shadow: 0 0 10px 3px rgba(244,114,182,0.9); animation: rjPulse 2s ease-in-out infinite;"></span>
                                <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-pink-300">Create Your Own</span>
                            </div>
                        </div>

                        <h2 data-split class="text-4xl md:text-6xl lg:text-7xl font-black leading-[1] tracking-tight mb-5 text-white">Design your own gang sheet</h2>

                        <div data-reveal="1">
                            <p class="text-base md:text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto mb-8 font-light">
                                Upload your artwork, arrange it on the canvas, and get instant pricing.
                            </p>
                        </div>

                        <div data-reveal="2">
                            <div class="flex flex-wrap gap-4 justify-center">
                                <a href="{{ url('design') }}" class="rj-btn pointer-events-auto inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full" style="box-shadow: 0 0 40px rgba(168,85,247,0.5);">
                                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                                    <span>Start Designing</span>
                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>
                                <a href="{{ url('contact-us') }}" class="pointer-events-auto inline-flex items-center gap-3 bg-white/5 backdrop-blur-md border border-white/15 text-white font-semibold px-7 py-4 rounded-full hover:bg-white/10 transition-colors">
                                    <i class="fa-solid fa-comments"></i>
                                    <span>Need Help?</span>
                                </a>
                            </div>
                        </div>

                        <div data-reveal="3">
                            <div class="mt-10 flex flex-wrap items-center justify-center gap-6 md:gap-8 font-mono text-[10px] text-gray-400 uppercase tracking-[0.2em]">
                                <span class="flex items-center gap-2"><i class="fa-solid fa-bolt text-yellow-400"></i> Instant pricing</span>
                                <span class="flex items-center gap-2"><i class="fa-solid fa-cloud-arrow-up text-indigo-400"></i> Any format</span>
                                <span class="flex items-center gap-2"><i class="fa-solid fa-truck-fast text-pink-400"></i> Fast shipping</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rj-dim absolute inset-0 bg-[#05030f] pointer-events-none"></div>
            </article>

            {{-- Atomic layer above backgrounds, below content --}}
            <div class="rj-atomic pointer-events-none" style="z-index: 22;">
                <div class="rj-orb rj-orb-a"></div>
                <div class="rj-orb rj-orb-b"></div>
                <div class="rj-grid-bg"></div>
                <canvas id="rjHeroCanvas" class="rj-canvas"></canvas>
                <div class="rj-orbit-wrap">
                    <div class="rj-orbit rj-orbit-1">
                        <span class="rj-electron"></span>
                    </div>
                    <div class="rj-orbit rj-orbit-2">
                        <span class="rj-electron"></span>
                    </div>
                    <div class="rj-orbit rj-orbit-3">
                        <span class="rj-electron"></span>
                    </div>
                    <div class="rj-core"></div>
                    <div class="rj-core-solid"></div>
                </div>
            </div>

            <div class="rj-hud absolute inset-0 pointer-events-none" style="z-index: 30;">
                <div class="absolute top-0 left-0 right-0 h-[3px] bg-white/5">
                    <div class="rj-progress h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 origin-left" style="transform: scaleX(0); box-shadow: 0 0 14px rgba(168,85,247,0.8);"></div>
                </div>

                <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3 pointer-events-auto">
                    @for($i = 0; $i < $totalScreens; $i++)
                        <button type="button" class="rj-dot h-1.5 rounded-full" data-index="{{ $i }}" aria-label="Screen {{ $i + 1 }}"></button>
                    @endfor
                </div>

                <div class="rj-counter absolute bottom-10 right-8 md:right-16 hidden md:flex items-end gap-2">
                    <span class="rj-current font-mono text-3xl font-black text-white">01</span>
                    <span class="font-mono text-[11px] text-gray-500 pb-1">/ {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</span>
                </div>

                <div class="rj-scroll absolute bottom-24 right-1/2 translate-x-1/2 flex flex-col items-center gap-3">
                    <span class="font-mono text-[9px] text-gray-400 uppercase tracking-[0.3em]">Scroll</span>
                    <div class="w-px h-12 bg-gradient-to-b from-indigo-500/60 to-transparent"></div>
                </div>
            </div>
        </div>
    </section>

    <style>
        #rjHero .rj-slide { visibility: hidden; }
        #rjHero .rj-image { transform: scale(1.25); will-change: transform, filter; transform-origin: center; }
        #rjHero .rj-ghost {
            position: absolute; right: 4vw; top: 50%;
            font-weight: 900; font-size: clamp(14rem, 36vw, 36rem); line-height: 1;
            letter-spacing: -0.05em; color: transparent;
            -webkit-text-stroke: 1.5px rgba(255,255,255,0.09);
            pointer-events: none; user-select: none;
            will-change: transform, opacity;
        }
        #rjHero .rj-content { will-change: opacity, transform, filter; }
        #rjHero .rj-dim { opacity: 0; will-change: opacity; }
        #rjHero .sc-word { display: inline-block; overflow: hidden; vertical-align: top; padding: 0.04em 0.04em 0.22em; margin-bottom: -0.18em; }
        #rjHero .sc-word > span { display: inline-block; will-change: transform, opacity; }
        #rjHero [data-reveal] { will-change: opacity, transform, filter; }
        #rjHero .rj-dot {
            width: 8px; background: rgba(255,255,255,0.2); cursor: pointer; border: none;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1), background 0.4s ease, box-shadow 0.4s ease;
        }
        #rjHero .rj-dot:hover { background: rgba(255,255,255,0.45); }
        #rjHero .rj-dot.on { width: 44px; background: linear-gradient(90deg, #818cf8, #ec4899); box-shadow: 0 0 14px rgba(168,85,247,0.7); }
        #rjHero .rj-counter, #rjHero .rj-scroll { transition: opacity 0.4s ease; }
        #rjHero .rj-btn { position: relative; overflow: hidden; transition: box-shadow 0.3s ease, filter 0.3s ease; }
        #rjHero .rj-btn::after {
            content: ''; position: absolute; top: 0; left: -120%; width: 60%; height: 100%;
            background: linear-gradient(105deg, transparent, rgba(255,255,255,0.45), transparent);
            transform: skewX(-20deg); transition: left 0.7s ease;
        }
        #rjHero .rj-btn:hover::after { left: 140%; }
        #rjHero .rj-btn:hover { filter: brightness(1.12); }

        /* ---------- ATOMIC LAYER ---------- */
        #rjHero .rj-atomic { position: absolute; inset: 0; overflow: hidden; }

        #rjHero .rj-orb {
            position: absolute; border-radius: 9999px;
            filter: blur(90px); mix-blend-mode: screen;
        }
        #rjHero .rj-orb-a {
            width: 38vw; height: 38vw; left: -8vw; top: 5vh;
            background: rgba(99,102,241,0.4);
            animation: rjFloatA 14s ease-in-out infinite;
        }
        #rjHero .rj-orb-b {
            width: 32vw; height: 32vw; right: -6vw; bottom: -6vh;
            background: rgba(236,72,153,0.32);
            animation: rjFloatB 17s ease-in-out infinite;
        }
        #rjHero .rj-grid-bg {
            position: absolute; inset: 0; opacity: 0.55;
            background-image:
                linear-gradient(rgba(99,102,241,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99,102,241,0.06) 1px, transparent 1px);
            background-size: 60px 60px;
            -webkit-mask-image: radial-gradient(ellipse at center, #000 25%, transparent 72%);
            mask-image: radial-gradient(ellipse at center, #000 25%, transparent 72%);
        }
        #rjHero .rj-canvas {
            position: absolute; inset: 0; width: 100%; height: 100%;
            opacity: 0.75;
        }

        /* Central atomic orbit — sits above backgrounds, feels like a hub */
        #rjHero .rj-orbit-wrap {
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            width: min(85vmin, 760px); height: min(85vmin, 760px);
            pointer-events: none;
        }
        #rjHero .rj-orbit {
            position: absolute; inset: 0; border-radius: 50%;
            border: 1px solid rgba(129,140,248,0.16);
            animation: rjSpin 26s linear infinite;
        }
        #rjHero .rj-orbit-2 {
            inset: 14%; border-color: rgba(192,132,252,0.16);
            animation-duration: 18s; animation-direction: reverse;
        }
        #rjHero .rj-orbit-3 {
            inset: 28%; border-color: rgba(244,114,182,0.16);
            animation-duration: 12s;
        }
        #rjHero .rj-electron {
            position: absolute; top: -5px; left: 50%;
            width: 10px; height: 10px; border-radius: 50%;
            transform: translateX(-50%);
            background: #818cf8;
            box-shadow: 0 0 22px 6px rgba(129,140,248,0.85);
        }
        #rjHero .rj-orbit-2 .rj-electron {
            width: 8px; height: 8px; background: #c084fc;
            box-shadow: 0 0 18px 5px rgba(192,132,252,0.85);
        }
        #rjHero .rj-orbit-3 .rj-electron {
            width: 7px; height: 7px; background: #f472b6;
            box-shadow: 0 0 16px 5px rgba(244,114,182,0.85);
        }
        #rjHero .rj-core {
            position: absolute; top: 50%; left: 50%;
            width: 42%; aspect-ratio: 1; border-radius: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(99,102,241,0.55), rgba(168,85,247,0.35), transparent 72%);
            filter: blur(60px);
            animation: rjPulse 4s ease-in-out infinite;
        }
        #rjHero .rj-core-solid {
            position: absolute; top: 50%; left: 50%;
            width: 14%; aspect-ratio: 1; border-radius: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(135deg, #818cf8, #ec4899);
            box-shadow: 0 0 60px 12px rgba(168,85,247,0.5);
            animation: rjPulse 5s ease-in-out infinite;
        }

        @keyframes rjSpin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
        @keyframes rjPulse { 0%,100% { opacity: 0.55; transform: translate(-50%,-50%) scale(1); } 50% { opacity: 1; transform: translate(-50%,-50%) scale(1.08); } }
        @keyframes rjFloatA { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(8vw, 6vh) scale(1.15); } }
        @keyframes rjFloatB { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-7vw, -8vh) scale(1.1); } }
    </style>

    <script>
    (function() {
        var root = document.getElementById('rjHero');
        if (!root) return;

        var slideEls = Array.from(root.querySelectorAll('.rj-slide'));
        var S = slideEls.length;
        if (S < 2) return;

        var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        var clamp = function(v, a, b) {
            a = a === undefined ? 0 : a;
            b = b === undefined ? 1 : b;
            return Math.min(Math.max(v, a), b);
        };
        var easeOut = function(t) { return 1 - Math.pow(1 - t, 3); };
        var easeInOut = function(t) { return t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t + 2, 3) / 2; };
        var easeOutExpo = function(t) { return t === 1 ? 1 : 1 - Math.pow(2, -10 * t); };
        var pad = function(n) { return n < 10 ? '0' + n : String(n); };

        root.querySelectorAll('[data-split]').forEach(function(el) {
            var text = el.textContent.trim();
            el.setAttribute('aria-label', text);
            el.textContent = '';
            text.split(/\s+/).forEach(function(word, idx, arr) {
                var outer = document.createElement('span');
                outer.className = 'sc-word';
                outer.setAttribute('aria-hidden', 'true');
                var inner = document.createElement('span');
                inner.textContent = word;
                outer.appendChild(inner);
                el.appendChild(outer);
                if (idx < arr.length - 1) el.appendChild(document.createTextNode(' '));
            });
        });

        var items = slideEls.map(function(el, i) {
            return {
                el: el,
                i: i,
                mode: el.getAttribute('data-mode') || 'inset',
                image: el.querySelector('.rj-image'),
                dim: el.querySelector('.rj-dim'),
                ghost: el.querySelector('.rj-ghost'),
                content: el.querySelector('.rj-content'),
                words: Array.from(el.querySelectorAll('.sc-word > span')),
                reveals: Array.from(el.querySelectorAll('[data-reveal]'))
            };
        });

        var bar = root.querySelector('.rj-progress');
        var dots = Array.from(root.querySelectorAll('.rj-dot'));
        var counter = root.querySelector('.rj-counter');
        var currentLabel = root.querySelector('.rj-current');
        var scrollHint = root.querySelector('.rj-scroll');

        var target = 0;
        var current = 0;
        var introT = reduceMotion ? 1 : 0;
        var introStart = null;
        var rafId = null;

        function readTarget() {
            var rect = root.getBoundingClientRect();
            var total = root.offsetHeight - window.innerHeight;
            if (total <= 0) { target = 0; return; }
            var raw = clamp(-rect.top / total) * (S - 1);
            var base = Math.min(Math.floor(raw), S - 1);
            var frac = raw - base;
            target = base + easeInOut(clamp((frac - 0.1) / 0.8));
        }

        function render(pos) {
            var intro = easeOutExpo(introT);

            items.forEach(function(it) {
                var d = pos - it.i;

                if (d <= -1.05 || d >= 1.05) {
                    it.el.style.visibility = 'hidden';
                    it.el.style.pointerEvents = 'none';
                    return;
                }

                it.el.style.visibility = 'visible';
                it.el.style.pointerEvents = Math.abs(d) < 0.5 ? 'auto' : 'none';

                var enter = it.i === 0 ? intro : clamp(1 + d);
                var exit = clamp(d);

                var clip = 'none';
                if (it.i > 0 && enter < 1) {
                    if (it.mode === 'circle') {
                        var r = easeOut(enter) * 150;
                        clip = 'circle(' + r.toFixed(2) + '% at 50% 100%)';
                    } else {
                        var off = (1 - easeOut(enter)) * 100;
                        var rad = ((1 - enter) * 80).toFixed(1);
                        clip = 'inset(0 0 0 ' + off.toFixed(2) + '% round ' + rad + 'px 0 0 ' + rad + 'px)';
                    }
                }
                it.el.style.clipPath = clip;
                it.el.style.webkitClipPath = clip;

                if (it.image) {
                    var scale = (1.25 - 0.25 * enter) * (1 + exit * 0.15);
                    var tx = 0, ty = -exit * 6;
                    if (it.i > 0) {
                        if (it.mode === 'circle') ty += (1 - enter) * 10;
                        else tx = (1 - enter) * 12;
                    }
                    var blurAmt = exit * 14 + (it.i > 0 ? (1 - enter) * 6 : 0);
                    var bright = 1 - exit * 0.45 - (it.i > 0 ? (1 - enter) * 0.25 : 0);
                    var sat = 1 - exit * 0.3;

                    it.image.style.transform = 'translate3d(' + tx.toFixed(2) + '%,' + ty.toFixed(2) + '%,0) scale(' + scale.toFixed(4) + ')';
                    it.image.style.filter = 'blur(' + blurAmt.toFixed(2) + 'px) brightness(' + bright.toFixed(3) + ') saturate(' + sat.toFixed(3) + ')';
                }

                if (it.dim) it.dim.style.opacity = (exit * 0.7).toFixed(3);

                if (it.ghost) {
                    it.ghost.style.transform = 'translateY(-50%) translate3d(' + (-d * 30).toFixed(2) + 'vw,0,0)';
                    it.ghost.style.opacity = ((1 - Math.abs(d)) * (it.i === 0 ? intro : 1) * 0.9).toFixed(3);
                }

                if (it.content) {
                    var co = 1 - clamp(exit * 1.8);
                    it.content.style.opacity = co.toFixed(3);
                    it.content.style.transform = 'translate3d(0,' + (-exit * 80).toFixed(1) + 'px,0)';
                    it.content.style.filter = 'blur(' + (exit * 6).toFixed(2) + 'px)';
                }

                var wl = it.words.length;
                var step = 0.25 / Math.max(wl, 1);
                it.words.forEach(function(w, k) {
                    var local = clamp((enter - 0.35 - k * step) / 0.35);
                    var e = easeOutExpo(local);
                    w.style.transform = 'translate3d(0,' + ((1 - e) * 120).toFixed(2) + '%,0)';
                    w.style.opacity = e.toFixed(3);
                });

                it.reveals.forEach(function(r) {
                    var idx = parseInt(r.getAttribute('data-reveal'), 10) || 0;
                    var local = clamp((enter - 0.55 - idx * 0.05) / 0.3);
                    var e = easeOut(local);
                    r.style.opacity = e.toFixed(3);
                    r.style.transform = 'translate3d(0,' + ((1 - e) * 35).toFixed(1) + 'px,0)';
                    r.style.filter = 'blur(' + ((1 - e) * 4).toFixed(2) + 'px)';
                });
            });

            var idx = clamp(Math.round(pos), 0, S - 1);

            if (bar) bar.style.transform = 'scaleX(' + (pos / (S - 1)).toFixed(4) + ')';

            dots.forEach(function(dot, k) {
                dot.classList.toggle('on', k === idx);
            });

            if (counter && currentLabel) {
                var onSlide = idx < S - 1;
                counter.style.opacity = onSlide ? '1' : '0';
                if (onSlide) currentLabel.textContent = pad(idx + 1);
            }

            if (scrollHint) {
                scrollHint.style.opacity = idx >= S - 1 ? '0' : '1';
            }
        }

        function tick(now) {
            rafId = null;

            if (introT < 1) {
                if (introStart === null) introStart = now;
                introT = clamp((now - introStart) / 1600);
            }

            var k = reduceMotion ? 1 : 0.085;
            current += (target - current) * k;
            if (Math.abs(target - current) < 0.0004) current = target;

            render(current);

            if (current !== target || introT < 1) {
                rafId = requestAnimationFrame(tick);
            }
        }

        function request() {
            if (rafId === null) rafId = requestAnimationFrame(tick);
        }

        function onScroll() {
            readTarget();
            request();
        }

        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var k = parseInt(this.dataset.index, 10);
                var total = root.offsetHeight - window.innerHeight;
                var top = root.getBoundingClientRect().top + window.scrollY;
                window.scrollTo({
                    top: top + (k / (S - 1)) * total,
                    behavior: reduceMotion ? 'auto' : 'smooth'
                });
            });
        });

        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);

        readTarget();
        current = target;

        if (target > 0.01 || root.getBoundingClientRect().top < -50) introT = 1;

        render(current);
        request();

        /* ---------- Particle canvas (independent of scroll) ---------- */
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
                var count = Math.min(80, Math.floor(w * h / 20000));
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
                    p.vx *= 0.99; p.vy *= 0.99;
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

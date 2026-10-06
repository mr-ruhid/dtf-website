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

            @foreach($items as $index => $item)
                <div class="rj-bg absolute inset-0" data-index="{{ $index }}" style="z-index: {{ $index + 1 }}; clip-path: circle(0% at 75% 50%);">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rj-bg-img w-full h-full object-cover" data-index="{{ $index }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#05030f]/95 via-[#05030f]/55 to-transparent"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#05030f]/80 via-transparent to-transparent"></div>
                </div>
            @endforeach

            <div class="rj-bg absolute inset-0" data-index="{{ $total }}" style="z-index: {{ $total + 1 }}; clip-path: circle(0% at 75% 50%);">
                <img src="{{ $lastItem->image_url }}" alt="Design your own" class="rj-bg-img w-full h-full object-cover" data-index="{{ $total }}" style="filter: blur(3px) saturate(1.2);">
                <div class="absolute inset-0 bg-[#05030f]/75"></div>
                <div class="absolute inset-0 bg-gradient-to-b from-[#05030f]/60 via-[#05030f]/30 to-[#05030f]/85"></div>
            </div>

            <div class="rj-orb rj-orb-a"></div>
            <div class="rj-orb rj-orb-b"></div>
            <div class="rj-spot"></div>
            <div class="rj-grid"></div>
            <canvas id="rjHeroCanvas" class="absolute inset-0 w-full h-full pointer-events-none" style="opacity: 0.55; z-index: 20;"></canvas>

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
                                <h1 data-split class="rj-title text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight mb-6">{{ $item->title }}</h1>
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

                    <h2 data-split class="rj-title text-4xl md:text-6xl lg:text-7xl font-black leading-[1] tracking-tight mb-5 text-white">Design your own <span class="rj-grad">gang sheet</span></h2>

                    <p class="rj-el text-base md:text-lg text-gray-300 leading-relaxed max-w-2xl mx-auto mb-8 font-light">
                        Upload your artwork, arrange it on the canvas, and get instant pricing.
                    </p>

                    <div class="rj-el flex flex-wrap gap-4 justify-center">
                        <a href="{{ url('design') }}" class="rj-btn rj-mag pointer-events-auto inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full" style="box-shadow: 0 0 40px rgba(168,85,247,0.5);">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
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

            <div class="absolute top-0 left-0 right-0 h-[3px] bg-white/5 z-30">
                <div class="rj-progress h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500" style="width: 0%; box-shadow: 0 0 14px rgba(168,85,247,0.8);"></div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3 z-30">
                @for($i = 0; $i < $totalScreens; $i++)
                    <button class="rj-dot h-1.5 rounded-full" data-index="{{ $i }}" aria-label="Screen {{ $i + 1 }}"></button>
                @endfor
            </div>

            <div class="absolute bottom-10 right-8 md:right-16 hidden md:flex items-end gap-2 z-30 pointer-events-none">
                <span class="rj-counter font-mono text-3xl font-black text-white">01</span>
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

        #rjHero .rj-bg {
            will-change: clip-path;
        }
        #rjHero .rj-bg-img {
            --py: 0;
            transform: scale(1.15) translate3d(calc(var(--mx) * -22px), calc(var(--py) * 1px + var(--my) * -14px), 0);
            will-change: transform;
        }

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

        #rjHero .rj-slide {
            z-index: 25;
            will-change: opacity, transform;
            padding: 4.5rem 0 7.5rem;
            pointer-events: none;
        }
        #rjHero .rj-slide.primary { pointer-events: auto; }

        #rjHero .rj-el {
            will-change: opacity, transform, filter;
        }

        #rjHero .rj-title { will-change: transform; }
        #rjHero .rj-w { display: inline-block; overflow: hidden; vertical-align: top; padding: 0.04em 0.04em 0.22em; margin-bottom: -0.18em; margin-right: 0.18em; }
        #rjHero .rj-w > span { display: inline-block; transform: translateY(115%) rotate(6deg); transform-origin: left bottom; will-change: transform; }
        #rjHero .rj-grad {
            background: linear-gradient(90deg, #818cf8, #c084fc, #f472b6, #818cf8);
            background-size: 250% 100%;
            -webkit-background-clip: text; background-clip: text; color: transparent;
            animation: rjShift 5s linear infinite;
        }
        #rjHero .rj-grad .rj-w > span { color: transparent; }

        #rjHero .rj-ghost {
            position: absolute; right: 4vw; top: 50%;
            font-weight: 900; font-size: clamp(14rem, 36vw, 36rem); line-height: 1; letter-spacing: -0.05em;
            color: transparent; -webkit-text-stroke: 1.5px rgba(255,255,255,0.09);
            pointer-events: none; user-select: none;
            will-change: transform, opacity;
        }

        #rjHero .rj-ring {
            position: absolute; width: min(80vmin, 760px); height: min(80vmin, 760px); border-radius: 9999px;
            border: 1px dashed rgba(168,85,247,0.35);
            will-change: transform, opacity;
        }
        #rjHero .rj-ring::before, #rjHero .rj-ring::after {
            content: ''; position: absolute; border-radius: 9999px; inset: 8%;
            border: 1px solid rgba(236,72,153,0.25);
        }
        #rjHero .rj-ring::after { inset: 18%; border-style: dotted; border-color: rgba(129,140,248,0.35); }

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
        #rjHero .rj-mag { will-change: transform; }

        #rjHero .rj-dot {
            width: 8px; background: rgba(255,255,255,0.2); cursor: pointer;
            transition: width 0.6s cubic-bezier(0.16, 1, 0.3, 1), background 0.4s ease, box-shadow 0.4s ease;
        }
        #rjHero .rj-dot:hover { background: rgba(255,255,255,0.45); }
        #rjHero .rj-dot.on { width: 44px; background: linear-gradient(90deg, #818cf8, #ec4899); box-shadow: 0 0 14px rgba(168,85,247,0.7); }

        #rjHero .rj-counter { display: inline-block; will-change: transform; }
        #rjHero .rj-scroll { transition: opacity 0.4s ease; }
        #rjHero .rj-scroll-line { position: relative; width: 1px; height: 48px; background: rgba(129,140,248,0.2); overflow: hidden; }
        #rjHero .rj-scroll-line::after {
            content: ''; position: absolute; left: 0; top: -100%; width: 100%; height: 100%;
            background: linear-gradient(to bottom, transparent, #a78bfa, transparent);
            animation: rjDrip 1.8s ease-in-out infinite;
        }

        @keyframes rjSpin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
        @keyframes rjPulse { 0%,100% { opacity: 0.5; transform: scale(1); } 50% { opacity: 1; transform: scale(1.15); } }
        @keyframes rjShift { to { background-position: 250% 0; } }
        @keyframes rjDrip { 0% { top: -100%; } 100% { top: 100%; } }
        @keyframes rjFloatA { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(8vw, 6vh) scale(1.15); } }
        @keyframes rjFloatB { 0%,100% { transform: translate(0,0) scale(1); } 50% { transform: translate(-7vw, -8vh) scale(1.1); } }

        @media (prefers-reduced-motion: reduce) {
            #rjHero *, #rjHero *::before, #rjHero *::after { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; }
        }
    </style>

    <script>
    (function() {
        var hero = document.getElementById('rjHero');
        if (!hero) return;

        var bgs = hero.querySelectorAll('.rj-bg');
        var bgImages = hero.querySelectorAll('.rj-bg-img');
        var slides = hero.querySelectorAll('.rj-slide');
        var dots = hero.querySelectorAll('.rj-dot');
        var progressBar = hero.querySelector('.rj-progress');
        var counter = hero.querySelector('.rj-counter');
        var scrollHint = hero.querySelector('.rj-scroll');
        var N = slides.length;

        function clamp(v, a, b) { return v < a ? a : v > b ? b : v; }
        function lerp(a, b, t) { return a + (b - a) * t; }
        function smooth(t) { t = clamp(t, 0, 1); return t * t * (3 - 2 * t); }

        hero.querySelectorAll('[data-split]').forEach(function(el) {
            function wrap(node) {
                Array.prototype.slice.call(node.childNodes).forEach(function(child) {
                    if (child.nodeType === 3) {
                        var frag = document.createDocumentFragment();
                        child.textContent.split(/\s+/).filter(Boolean).forEach(function(word) {
                            var w = document.createElement('span');
                            w.className = 'rj-w';
                            var s = document.createElement('span');
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

        var slideData = [];
        slides.forEach(function(slide) {
            slideData.push({
                el: slide,
                els: slide.querySelectorAll('.rj-el'),
                words: slide.querySelectorAll('.rj-w > span'),
                ghost: slide.querySelector('.rj-ghost'),
                ring: slide.querySelector('.rj-ring')
            });
        });

        var lastCounterVal = -1;

        function update() {
            var rect = hero.getBoundingClientRect();
            var scrollable = hero.offsetHeight - window.innerHeight;
            var scrolled = clamp(-rect.top, 0, scrollable);
            var P = scrollable > 0 ? scrolled / scrollable : 0;

            var VP = P * (N - 1);

            if (progressBar) progressBar.style.width = (P * 100) + '%';

            slideData.forEach(function(data, i) {
                var lifeP = VP - i;
                var absP = Math.abs(lifeP);
                var opacity = clamp(1 - absP, 0, 1);
                var slideY = -lifeP * 80;

                data.el.style.opacity = opacity;
                data.el.style.transform = 'translateY(' + slideY + 'px)';
                data.el.style.visibility = opacity > 0.005 ? 'visible' : 'hidden';

                var enterP = clamp(lifeP + 1, 0, 1);

                data.els.forEach(function(el, j) {
                    var subStart = j * 0.12;
                    var subEnd = subStart + 0.35;
                    var subP = clamp((enterP - subStart) / (subEnd - subStart), 0, 1);
                    var eased = smooth(subP);
                    el.style.opacity = eased;
                    el.style.transform = 'translateY(' + ((1 - eased) * 50) + 'px) scale(' + (0.92 + eased * 0.08) + ')';
                    el.style.filter = 'blur(' + ((1 - eased) * 10) + 'px)';
                });

                data.words.forEach(function(w, k) {
                    var wStart = 0.15 + k * 0.05;
                    var wEnd = wStart + 0.3;
                    var wp = clamp((enterP - wStart) / (wEnd - wStart), 0, 1);
                    var we = smooth(wp);
                    w.style.transform = 'translateY(' + ((1 - we) * 115) + '%) rotate(' + ((1 - we) * 6) + 'deg)';
                });

                if (data.ghost) {
                    var gp = clamp((enterP - 0.1) / 0.5, 0, 1);
                    var ge = smooth(gp);
                    data.ghost.style.opacity = (ge * 0.9).toFixed(3);
                    data.ghost.style.transform = 'translateY(-50%) translateX(' + ((1 - ge) * 120) + 'px)';
                }

                if (data.ring) {
                    var rp = clamp((enterP - 0.1) / 0.6, 0, 1);
                    var re = smooth(rp);
                    data.ring.style.opacity = re.toFixed(3);
                    data.ring.style.transform = 'scale(' + (0.6 + re * 0.4) + ') rotate(' + ((1 - re) * -40) + 'deg)';
                }
            });

            bgs.forEach(function(bg, i) {
                var lifeP = VP - i;
                var reveal = clamp(lifeP + 1, 0, 1);
                var radius = reveal * 150;
                bg.style.clipPath = 'circle(' + radius + '% at 75% 50%)';
            });

            bgImages.forEach(function(img, i) {
                var lifeP = VP - i;
                var parallax = -lifeP * 40;
                img.style.setProperty('--py', parallax.toFixed(2));
            });

            var primaryIdx = clamp(Math.round(VP), 0, N - 1);
            slideData.forEach(function(data, i) {
                data.el.classList.toggle('primary', i === primaryIdx);
            });

            dots.forEach(function(d, i) {
                d.classList.toggle('on', i === primaryIdx);
            });

            if (counter && primaryIdx !== lastCounterVal) {
                lastCounterVal = primaryIdx;
                counter.textContent = String(primaryIdx + 1).padStart(2, '0');
            }

            if (scrollHint) {
                scrollHint.style.opacity = P > 0.95 ? '0' : '1';
            }
        }

        var ticking = false;
        function onScroll() {
            if (ticking) return;
            ticking = true;
            window.requestAnimationFrame(function() {
                update();
                ticking = false;
            });
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        window.addEventListener('resize', onScroll);
        update();

        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var idx = parseInt(this.dataset.index);
                var scrollable = hero.offsetHeight - window.innerHeight;
                var targetScroll = hero.offsetTop + (idx / (N - 1)) * scrollable;
                window.scrollTo({ top: targetScroll, behavior: 'smooth' });
            });
        });

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

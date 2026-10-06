@php
    $slider = \App\Models\Slider::findByLocation($widget->getSetting('location', 'home_hero'));
@endphp

@if($slider && $slider->activeItems->count())
    @php $items = $slider->activeItems; $total = $items->count(); @endphp

    <section id="rjHero" class="relative bg-[#05030f]" style="height: {{ $total * 100 }}vh;">

        <div class="sticky top-0 h-screen w-full overflow-hidden">

            @foreach($items as $index => $item)
                <div class="rj-bg absolute inset-0" data-index="{{ $index }}" style="opacity: {{ $index === 0 ? 1 : 0 }}; transition: opacity 1s ease;">
                    <img src="{{ $item->image_url }}" alt="{{ $item->title }}" class="rj-bg-img w-full h-full object-cover" data-index="{{ $index }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-[#05030f] via-[#05030f]/85 to-[#05030f]/30"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-[#05030f] via-transparent to-[#05030f]/60"></div>
                </div>
            @endforeach

            <canvas id="rjHeroCanvas" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>

            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
                <div class="relative w-[500px] h-[500px] md:w-[900px] md:h-[900px]">
                    <div class="absolute inset-0 rounded-full border border-indigo-500/15" style="animation: rjSpin 25s linear infinite;">
                        <div class="absolute -top-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-indigo-400 rounded-full" style="box-shadow: 0 0 20px 6px rgba(129,140,248,0.9);"></div>
                    </div>
                    <div class="absolute inset-[15%] rounded-full border border-purple-500/15" style="animation: rjSpin 18s linear infinite reverse;">
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-purple-400 rounded-full" style="box-shadow: 0 0 16px 5px rgba(192,132,252,0.9);"></div>
                    </div>
                    <div class="absolute inset-[30%] rounded-full border border-pink-500/15" style="animation: rjSpin 12s linear infinite;">
                        <div class="absolute -top-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-pink-400 rounded-full" style="box-shadow: 0 0 16px 5px rgba(244,114,182,0.9);"></div>
                    </div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 rounded-full" style="background: radial-gradient(circle, rgba(99,102,241,0.4), rgba(168,85,247,0.2), transparent 70%); filter: blur(60px); animation: rjPulse 4s ease-in-out infinite;"></div>
                </div>
            </div>

            <div class="relative h-full w-full z-10 pointer-events-none">
                @foreach($items as $index => $item)
                    <div class="rj-slide absolute inset-0 flex items-center" data-index="{{ $index }}" style="opacity: {{ $index === 0 ? 1 : 0 }}; transition: opacity 0.8s ease;">
                        <div class="max-w-7xl mx-auto px-6 lg:px-12 w-full">
                            <div class="max-w-2xl rj-slide-inner">
                                <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-8">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 10px 3px rgba(52,211,153,0.9); animation: rjPulse 2s ease-in-out infinite;"></span>
                                    <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-indigo-300">State {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    @if($item->subtitle)
                                        <span class="w-px h-3 bg-white/20"></span>
                                        <span class="text-[11px] font-mono uppercase tracking-[0.25em] text-gray-400">{{ $item->subtitle }}</span>
                                    @endif
                                </div>

                                @if($item->title)
                                    <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[0.95] tracking-tight mb-6">
                                        <span class="bg-gradient-to-r from-white via-indigo-200 to-white bg-clip-text text-transparent" style="background-size: 200% 100%; animation: rjShine 4s linear infinite;">
                                            {{ $item->title }}
                                        </span>
                                    </h1>
                                @endif

                                @if($item->description)
                                    <p class="text-lg md:text-xl text-gray-400 leading-relaxed mb-10 max-w-xl font-light">
                                        {{ $item->description }}
                                    </p>
                                @endif

                                @if($item->button_text && $item->button_link)
                                    <a href="{{ $item->button_link }}"
                                       class="group pointer-events-auto inline-flex items-center gap-3 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full transition-all duration-300"
                                       style="box-shadow: 0 0 30px rgba(139,92,246,0.4);"
                                       onmouseover="this.style.boxShadow='0 0 60px rgba(139,92,246,0.8)'; this.style.transform='scale(1.05)';"
                                       onmouseout="this.style.boxShadow='0 0 30px rgba(139,92,246,0.4)'; this.style.transform='scale(1)';">
                                        <i class="fa-solid fa-atom"></i>
                                        <span>{{ $item->button_text }}</span>
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="absolute top-0 left-0 right-0 h-0.5 bg-white/5 z-20">
                <div class="rj-progress h-full bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 transition-all duration-150" style="width: 0%;"></div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex items-center gap-3 z-20">
                @foreach($items as $index => $item)
                    <button class="rj-dot h-1.5 rounded-full transition-all duration-500"
                            data-index="{{ $index }}"
                            style="width: {{ $index === 0 ? '40px' : '8px' }}; background: {{ $index === 0 ? 'linear-gradient(90deg, #818cf8, #ec4899)' : 'rgba(255,255,255,0.2)' }};"
                            aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>

            <div class="absolute bottom-10 right-8 md:right-16 hidden md:flex flex-col items-center gap-2 z-20 pointer-events-none">
                <span class="rj-counter font-mono text-2xl font-black text-white">01</span>
                <span class="font-mono text-[10px] text-gray-500">/ {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="absolute bottom-10 right-8 md:right-16 hidden md:flex flex-col items-center gap-2 z-20 pointer-events-none opacity-0">
            </div>

            <div class="absolute bottom-24 right-1/2 translate-x-1/2 flex flex-col items-center gap-3 z-20 pointer-events-none">
                <span class="font-mono text-[9px] text-gray-500 uppercase tracking-[0.3em]">Scroll</span>
                <div class="w-px h-12 bg-gradient-to-b from-indigo-500/60 to-transparent"></div>
            </div>

            <div class="absolute top-8 left-8 font-mono text-[10px] text-gray-600 uppercase tracking-[0.3em] z-20 hidden md:flex items-center gap-2">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full" style="box-shadow: 0 0 10px 3px rgba(52,211,153,0.9); animation: rjPulse 2s ease-in-out infinite;"></span>
                <span>Live</span>
            </div>
        </div>
    </section>

    <style>
        @keyframes rjSpin { from { transform: rotate(0); } to { transform: rotate(360deg); } }
        @keyframes rjPulse { 0%,100% { opacity: 0.5; transform: scale(1); } 50% { opacity: 1; transform: scale(1.05); } }
        @keyframes rjShine { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
        @keyframes rjTitleIn {
            0% { filter: blur(20px); opacity: 0; transform: translateY(40px) scale(0.95); }
            100% { filter: blur(0); opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes rjFadeUp {
            0% { opacity: 0; transform: translateY(40px); filter: blur(10px); }
            100% { opacity: 1; transform: translateY(0); filter: blur(0); }
        }
        .rj-slide.active { opacity: 1 !important; }
        .rj-slide.active .rj-slide-inner { animation: rjTitleIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .rj-slide.active .rj-slide-inner > * { animation: rjFadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) both; }
        .rj-slide.active .rj-slide-inner > *:nth-child(1) { animation-delay: 0.1s; }
        .rj-slide.active .rj-slide-inner > *:nth-child(2) { animation-delay: 0.25s; }
        .rj-slide.active .rj-slide-inner > *:nth-child(3) { animation-delay: 0.4s; }
        .rj-slide.active .rj-slide-inner > *:nth-child(4) { animation-delay: 0.55s; }
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
        var total = textSlides.length;
        var current = 0;

        function updateSlide(idx) {
            if (idx === current) return;

            bgSlides.forEach(function(el, i) {
                el.style.opacity = i === idx ? '1' : '0';
            });
            textSlides.forEach(function(el, i) {
                if (i === idx) el.classList.add('active');
                else el.classList.remove('active');
            });
            dots.forEach(function(d, i) {
                if (i === idx) {
                    d.style.width = '40px';
                    d.style.background = 'linear-gradient(90deg, #818cf8, #ec4899)';
                } else {
                    d.style.width = '8px';
                    d.style.background = 'rgba(255,255,255,0.2)';
                }
            });
            if (counter) counter.textContent = String(idx + 1).padStart(2, '0');
            current = idx;
        }

        textSlides[0].classList.add('active');

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    var rect = hero.getBoundingClientRect();
                    var heroTop = rect.top;
                    var heroHeight = hero.offsetHeight;
                    var windowH = window.innerHeight;
                    var scrollable = heroHeight - windowH;
                    var scrolled = Math.max(0, -heroTop);
                    var progress = Math.max(0, Math.min(1, scrolled / scrollable));

                    if (progressBar) progressBar.style.width = (progress * 100) + '%';

                    var idx = Math.min(total - 1, Math.floor(progress * total));
                    updateSlide(idx);

                    bgImages.forEach(function(img, i) {
                        var offset = (progress * 100) - (i * (100 / total));
                        var translate = offset * -0.5;
                        img.style.transform = 'scale(1.1) translateY(' + translate + 'px)';
                    });

                    ticking = false;
                });
                ticking = true;
            }
        });

        dots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var idx = parseInt(this.dataset.index);
                var heroTopAbs = hero.offsetTop;
                var heroHeight = hero.offsetHeight;
                var windowH = window.innerHeight;
                var scrollable = heroHeight - windowH;
                var targetScroll = heroTopAbs + (idx / total) * scrollable + 10;
                window.scrollTo({ top: targetScroll, behavior: 'smooth' });
            });
        });

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
                var count = Math.min(140, Math.floor(w * h / 12000));
                for (var i = 0; i < count; i++) {
                    particles.push({
                        x: Math.random() * w,
                        y: Math.random() * h,
                        vx: (Math.random() - 0.5) * 0.4,
                        vy: (Math.random() - 0.5) * 0.4,
                        r: Math.random() * 2 + 0.5,
                        baseR: Math.random() * 2 + 0.5,
                        hue: Math.random() * 80 + 220,
                        pulse: Math.random() * Math.PI * 2,
                        pulseSpeed: Math.random() * 0.03 + 0.01
                    });
                }
            }

            function draw() {
                ctx.fillStyle = 'rgba(5,3,15,0.15)';
                ctx.fillRect(0, 0, w, h);
                time += 0.005;

                for (var i = 0; i < particles.length; i++) {
                    var p = particles[i];
                    var dxc = centerX - p.x;
                    var dyc = centerY - p.y;
                    var distC = Math.sqrt(dxc * dxc + dyc * dyc);
                    if (distC > 0) {
                        p.vx += (dxc / distC) * 0.002;
                        p.vy += (dyc / distC) * 0.002;
                    }
                    p.vx *= 0.99;
                    p.vy *= 0.99;
                    p.x += p.vx;
                    p.y += p.vy;

                    if (p.x < 0) { p.x = 0; p.vx *= -1; }
                    if (p.x > w) { p.x = w; p.vx *= -1; }
                    if (p.y < 0) { p.y = 0; p.vy *= -1; }
                    if (p.y > h) { p.y = h; p.vy *= -1; }

                    p.pulse += p.pulseSpeed;
                    p.r = p.baseR * (1 + Math.sin(p.pulse) * 0.4);

                    var distNorm = Math.min(1, distC / (Math.min(w, h) / 2));

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = 'hsla(' + p.hue + ', 85%, ' + (60 + distNorm * 20) + '%, ' + (0.7 - distNorm * 0.3) + ')';
                    ctx.fill();
                }

                for (var i = 0; i < particles.length; i++) {
                    for (var j = i + 1; j < particles.length; j++) {
                        var dx = particles[i].x - particles[j].x;
                        var dy = particles[i].y - particles[j].y;
                        var dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 130) {
                            var alpha = (1 - dist / 130) * 0.25;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            var grad = ctx.createLinearGradient(particles[i].x, particles[i].y, particles[j].x, particles[j].y);
                            grad.addColorStop(0, 'hsla(' + particles[i].hue + ', 85%, 70%, ' + alpha + ')');
                            grad.addColorStop(1, 'hsla(' + particles[j].hue + ', 85%, 70%, ' + alpha + ')');
                            ctx.strokeStyle = grad;
                            ctx.lineWidth = 0.6;
                            ctx.stroke();
                        }
                    }
                }

                var waveRadius = (time * 200) % (Math.max(w, h) * 0.8);
                var waveAlpha = Math.max(0, 0.15 - waveRadius / (Math.max(w, h) * 0.8) * 0.15);
                if (waveAlpha > 0.01) {
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, waveRadius, 0, Math.PI * 2);
                    ctx.strokeStyle = 'hsla(250, 85%, 70%, ' + waveAlpha + ')';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                }

                var waveRadius2 = ((time * 200) + 300) % (Math.max(w, h) * 0.8);
                var waveAlpha2 = Math.max(0, 0.15 - waveRadius2 / (Math.max(w, h) * 0.8) * 0.15);
                if (waveAlpha2 > 0.01) {
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, waveRadius2, 0, Math.PI * 2);
                    ctx.strokeStyle = 'hsla(320, 85%, 70%, ' + waveAlpha2 + ')';
                    ctx.lineWidth = 2;
                    ctx.stroke();
                }

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

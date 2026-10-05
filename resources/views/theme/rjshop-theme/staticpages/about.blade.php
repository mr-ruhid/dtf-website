@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<section class="relative overflow-hidden bg-[#05030f] text-white min-h-[90vh] flex items-center">
    <canvas id="quantumField" class="absolute inset-0 w-full h-full"></canvas>

    <div class="absolute inset-0 bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.15),transparent_60%)]"></div>

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none">
        <div class="relative w-[500px] h-[500px] md:w-[700px] md:h-[700px]">
            <div class="absolute inset-0 rounded-full border border-indigo-500/20 animate-orbit-slow"></div>
            <div class="absolute inset-8 rounded-full border border-purple-500/20 animate-orbit-reverse"></div>
            <div class="absolute inset-16 rounded-full border border-pink-500/20 animate-orbit-slow"></div>
            <div class="absolute inset-0 animate-spin-slow">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3 h-3 bg-indigo-400 rounded-full shadow-[0_0_20px_4px_rgba(99,102,241,0.8)]"></div>
            </div>
            <div class="absolute inset-8 animate-spin-reverse">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2 h-2 bg-purple-400 rounded-full shadow-[0_0_15px_3px_rgba(168,85,247,0.8)]"></div>
            </div>
            <div class="absolute inset-16 animate-spin-slow">
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2 h-2 bg-pink-400 rounded-full shadow-[0_0_15px_3px_rgba(236,72,153,0.8)]"></div>
            </div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 md:w-32 md:h-32 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-full blur-2xl opacity-60 animate-pulse-slow"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 md:w-20 md:h-20 bg-white rounded-full opacity-10"></div>
        </div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 w-full">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-6 font-mono text-xs text-indigo-300">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                <span>QUANTUM STATE: ACTIVE</span>
            </div>

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black mb-6 leading-[0.95] tracking-tight">
                <span class="block text-white">{{ $page->title }}</span>
                <span class="block bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">in superposition</span>
            </h1>

            @if($page->excerpt)
                <p class="text-lg md:text-xl text-gray-400 leading-relaxed mb-8 max-w-2xl font-light">
                    {{ $page->excerpt }}
                </p>
            @endif

            <div class="flex flex-wrap gap-4">
                <a href="{{ url('contact-us') }}" class="group inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold px-6 py-3 rounded-full hover:shadow-[0_0_40px_rgba(99,102,241,0.6)] hover:scale-105 transition-all duration-300">
                    <span>Collapse the wave</span>
                    <i class="fa-solid fa-arrow-right group-hover:translate-x-1 transition"></i>
                </a>
                <a href="#entangled" class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 text-white font-semibold px-6 py-3 rounded-full hover:bg-white/10 transition">
                    <i class="fa-solid fa-atom text-indigo-400"></i>
                    <span>Observe</span>
                </a>
            </div>

            <div class="mt-12 flex items-center gap-6 font-mono text-xs text-gray-500">
                <div class="flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full"></span>
                    <span>ψ = α|0⟩ + β|1⟩</span>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="w-1.5 h-1.5 bg-purple-400 rounded-full"></span>
                    <span>Δx·Δp ≥ ℏ/2</span>
                </div>
            </div>
        </div>
    </div>

    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 text-gray-500 animate-bounce">
        <i class="fa-solid fa-chevron-down"></i>
    </div>
</section>

<section class="py-20 md:py-28 bg-gradient-to-b from-[#05030f] via-[#0a0720] to-[#05030f] text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="font-mono text-xs text-indigo-400 uppercase tracking-[0.3em] mb-4">// MEASUREMENTS</div>
            <h2 class="text-3xl md:text-5xl font-black mb-4">Observable quantities</h2>
            <p class="text-gray-400 text-lg">Every interaction leaves a signature in the quantum field.</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @php
                $stats = [
                    ['symbol' => 'N', 'value' => '50K+', 'label' => 'Orders Delivered', 'unit' => 'units'],
                    ['symbol' => 'Ψ', 'value' => '10K+', 'label' => 'Happy Customers', 'unit' => 'particles'],
                    ['symbol' => 'λ', 'value' => '4.9', 'label' => 'Average Rating', 'unit' => 'ratio'],
                    ['symbol' => 't', 'value' => '24h', 'label' => 'Fast Turnaround', 'unit' => 'time'],
                ];
            @endphp
            @foreach($stats as $i => $stat)
                <div class="group relative bg-white/[0.03] backdrop-blur-sm border border-white/10 rounded-2xl p-6 md:p-8 hover:bg-white/[0.06] hover:border-indigo-500/50 transition-all duration-500 hover:-translate-y-1">
                    <div class="absolute top-4 right-4 font-mono text-3xl md:text-4xl text-indigo-500/20 group-hover:text-indigo-500/40 transition">{{ $stat['symbol'] }}</div>
                    <div class="font-mono text-[10px] text-indigo-400 uppercase tracking-widest mb-3">[{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}]</div>
                    <div class="text-3xl md:text-5xl font-black mb-2 bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">{{ $stat['value'] }}</div>
                    <div class="text-xs text-gray-500 mb-1">{{ $stat['label'] }}</div>
                    <div class="font-mono text-[10px] text-gray-600">[{{ $stat['unit'] }}]</div>
                    <div class="absolute bottom-0 left-0 h-px w-0 bg-gradient-to-r from-indigo-500 to-pink-500 group-hover:w-full transition-all duration-700"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="entangled" class="py-20 md:py-28 bg-[#05030f] text-white relative overflow-hidden">
    <div class="absolute top-1/4 -left-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-1/4 -right-40 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px]"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="relative">
                <div class="font-mono text-xs text-indigo-400 uppercase tracking-[0.3em] mb-4">// ENTANGLEMENT</div>
                <h2 class="text-3xl md:text-5xl font-black mb-6 leading-tight">
                    Two states, <br>
                    <span class="bg-gradient-to-r from-indigo-400 to-pink-400 bg-clip-text text-transparent">one reality</span>
                </h2>
                <div class="prose prose-invert prose-lg max-w-none prose-p:text-gray-400 prose-headings:text-white prose-a:text-indigo-400">
                    {!! $page->content !!}
                </div>

                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="bg-white/[0.03] border border-white/10 rounded-xl p-4">
                        <div class="font-mono text-[10px] text-indigo-400 mb-1">COHERENCE</div>
                        <div class="text-2xl font-bold">99.9%</div>
                    </div>
                    <div class="bg-white/[0.03] border border-white/10 rounded-xl p-4">
                        <div class="font-mono text-[10px] text-purple-400 mb-1">FIDELITY</div>
                        <div class="text-2xl font-bold">∞</div>
                    </div>
                </div>
            </div>

            <div class="relative flex items-center justify-center">
                <div class="relative w-full aspect-square max-w-md">
                    <div class="absolute inset-0 rounded-full border border-indigo-500/20 animate-orbit-slow"></div>
                    <div class="absolute inset-12 rounded-full border border-purple-500/20 animate-orbit-reverse"></div>
                    <div class="absolute inset-24 rounded-full border border-pink-500/20 animate-orbit-slow"></div>

                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-40 h-40 bg-gradient-to-br from-indigo-500/30 to-pink-500/30 rounded-full blur-2xl"></div>

                    @if($page->image_url)
                        <div class="absolute inset-24 rounded-full overflow-hidden border-2 border-white/10 shadow-[0_0_60px_rgba(99,102,241,0.4)]">
                            <img src="{{ $page->image_url }}" alt="{{ $page->title }}" class="w-full h-full object-cover">
                        </div>
                    @else
                        <div class="absolute inset-24 rounded-full bg-gradient-to-br from-indigo-600/40 to-pink-600/40 backdrop-blur-sm border border-white/10 flex items-center justify-center">
                            <i class="fa-solid fa-atom text-white/40 text-6xl"></i>
                        </div>
                    @endif

                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3 h-3 bg-indigo-400 rounded-full shadow-[0_0_20px_6px_rgba(99,102,241,0.8)] animate-spin-slow origin-[50%_calc(50%_+_50%)]"></div>
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2 h-2 bg-pink-400 rounded-full shadow-[0_0_15px_4px_rgba(236,72,153,0.8)] animate-spin-reverse origin-[50%_calc(50%_+_50%)]"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-20 md:py-28 bg-gradient-to-b from-[#05030f] to-[#0a0720] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="font-mono text-xs text-indigo-400 uppercase tracking-[0.3em] mb-4">// FUNDAMENTAL FORCES</div>
            <h2 class="text-3xl md:text-5xl font-black mb-4">Our core constants</h2>
            <p class="text-gray-400 text-lg">The rules that govern everything we do.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $values = [
                    ['symbol' => 'Q', 'title' => 'Premium Quality', 'text' => 'We use only the best materials and cutting-edge technology to deliver products that exceed expectations.', 'color' => 'from-indigo-500 to-blue-500'],
                    ['symbol' => 'V', 'title' => 'Fast Turnaround', 'text' => 'Speed matters. Our efficient workflow ensures your orders are produced and shipped quickly.', 'color' => 'from-purple-500 to-fuchsia-500'],
                    ['symbol' => 'C', 'title' => 'Customer First', 'text' => 'Your satisfaction is our priority. We go the extra mile to make sure you are happy with every order.', 'color' => 'from-pink-500 to-rose-500'],
                ];
            @endphp
            @foreach($values as $i => $v)
                <div class="group relative bg-white/[0.02] backdrop-blur-sm border border-white/10 rounded-2xl p-8 hover:bg-white/[0.05] hover:border-white/20 transition-all duration-500 hover:-translate-y-2">
                    <div class="absolute top-0 left-8 right-8 h-px bg-gradient-to-r {{ $v['color'] }} opacity-0 group-hover:opacity-100 transition"></div>

                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $v['color'] }} flex items-center justify-center shadow-lg group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
                            <span class="text-white font-black text-2xl font-mono">{{ $v['symbol'] }}</span>
                        </div>
                        <div class="font-mono text-[10px] text-gray-600">0{{ $i + 1 }}</div>
                    </div>

                    <h3 class="text-xl font-bold text-white mb-3">{{ $v['title'] }}</h3>
                    <p class="text-gray-400 leading-relaxed text-sm">{{ $v['text'] }}</p>

                    <div class="mt-6 pt-6 border-t border-white/5 flex items-center gap-2 font-mono text-[10px] text-gray-600">
                        <span class="w-1 h-1 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span>STATE: STABLE</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="relative py-24 md:py-32 bg-[#05030f] text-white overflow-hidden">
    <div class="absolute inset-0">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-gradient-to-r from-indigo-500/20 via-purple-500/20 to-pink-500/20 rounded-full blur-[150px] animate-pulse-slow"></div>
    </div>

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="font-mono text-xs text-indigo-400 uppercase tracking-[0.3em] mb-6">// SUPERPOSITION</div>
        <h2 class="text-4xl md:text-6xl font-black mb-6 leading-tight">
            Every possibility <br>
            <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">exists at once</span>
        </h2>
        <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto font-light">
            Have a project in mind? Let's collapse the wave function and make it real.
        </p>

        <div class="flex flex-wrap gap-4 justify-center">
            <a href="{{ url('contact-us') }}" class="group inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold px-8 py-4 rounded-full hover:shadow-[0_0_50px_rgba(168,85,247,0.6)] hover:scale-105 transition-all duration-300">
                <i class="fa-solid fa-atom group-hover:rotate-180 transition-transform duration-700"></i>
                <span>Start Your Project</span>
            </a>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-white/5 backdrop-blur-md border border-white/10 text-white font-semibold px-8 py-4 rounded-full hover:bg-white/10 transition">
                <span>Browse Products</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

@push('styles')
<style>
@keyframes orbit-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes orbit-reverse { from { transform: rotate(360deg); } to { transform: rotate(0deg); } }
.animate-orbit-slow { animation: orbit-slow 30s linear infinite; }
.animate-orbit-reverse { animation: orbit-reverse 20s linear infinite; }
.animate-spin-slow { animation: orbit-slow 15s linear infinite; }
.animate-spin-reverse { animation: orbit-reverse 25s linear infinite; }

@keyframes pulse-slow {
    0%, 100% { opacity: 0.4; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.05); }
}
.animate-pulse-slow { animation: pulse-slow 6s ease-in-out infinite; }

.prose h2{font-size:1.75rem;font-weight:800;margin-top:1.5rem;margin-bottom:.75rem;color:#fff;}
.prose h3{font-size:1.25rem;font-weight:700;margin-top:1.25rem;margin-bottom:.5rem;color:#fff;}
.prose p{margin-bottom:1rem;line-height:1.8;}
.prose ul{list-style:disc;padding-left:1.5rem;margin-bottom:1rem;}
.prose ol{list-style:decimal;padding-left:1.5rem;margin-bottom:1rem;}
.prose li{margin-bottom:.35rem;}
.prose a{color:#818cf8;text-decoration:underline;}
</style>
@endpush

@push('scripts')
<script>
(function() {
    const canvas = document.getElementById('quantumField');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [];
    let w, h;

    function resize() {
        w = canvas.width = canvas.offsetWidth;
        h = canvas.height = canvas.offsetHeight;
    }

    function init() {
        particles = [];
        const count = Math.min(80, Math.floor(w * h / 15000));
        for (let i = 0; i < count; i++) {
            particles.push({
                x: Math.random() * w,
                y: Math.random() * h,
                vx: (Math.random() - 0.5) * 0.3,
                vy: (Math.random() - 0.5) * 0.3,
                r: Math.random() * 1.5 + 0.5,
                hue: Math.random() * 60 + 230
            });
        }
    }

    function draw() {
        ctx.fillStyle = 'rgba(5, 3, 15, 0.15)';
        ctx.fillRect(0, 0, w, h);

        particles.forEach((p, i) => {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0 || p.x > w) p.vx *= -1;
            if (p.y < 0 || p.y > h) p.vy *= -1;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `hsla(${p.hue}, 80%, 70%, 0.6)`;
            ctx.fill();

            for (let j = i + 1; j < particles.length; j++) {
                const q = particles[j];
                const dx = p.x - q.x;
                const dy = p.y - q.y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 120) {
                    ctx.beginPath();
                    ctx.moveTo(p.x, p.y);
                    ctx.lineTo(q.x, q.y);
                    ctx.strokeStyle = `hsla(240, 80%, 70%, ${0.15 * (1 - dist / 120)})`;
                    ctx.lineWidth = 0.5;
                    ctx.stroke();
                }
            }
        });

        requestAnimationFrame(draw);
    }

    window.addEventListener('resize', () => { resize(); init(); });
    resize();
    init();
    draw();
})();
</script>
@endpush

@endsection

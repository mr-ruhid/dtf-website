@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $model->meta_title ?: $model->name)
@section('meta_description', $model->meta_description ?: $model->description)
@section('meta_keywords', $model->meta_keywords)

@section('content')

{{-- ================= HERO ================= --}}
<section class="rj-sg-hero">
    <div class="rj-sg-grid-bg"></div>
    <div class="rj-sg-orb rj-sg-orb-a"></div>
    <div class="rj-sg-orb rj-sg-orb-b"></div>

    <div class="rj-sg-inner">
        <nav class="rj-sg-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span class="current">{{ $model->name }}</span>
        </nav>

        <div class="rj-sg-hero-grid">
            <div class="rj-sg-hero-text">
                <div class="rj-sg-eyebrow">
                    <span class="rj-sg-eyebrow-line"></span>
                    <span class="rj-sg-eyebrow-text">Custom Signage Studio</span>
                </div>

                <h1 class="rj-sg-hero-title">
                    Signs that make<br>
                    your brand <span class="rj-sg-grad">unmissable</span>
                </h1>

                <p class="rj-sg-hero-desc">
                    Premium vinyl, banners and fully custom signs — designed, printed and installed by professionals. From storefronts to stadiums, we bring your vision to life.
                </p>

                <div class="rj-sg-hero-actions">
                    <a href="#quote" class="rj-sg-btn rj-sg-btn-primary">
                        <i class="fa-solid fa-wand-magic-sparkles"></i>
                        <span>Get a Free Quote</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="#portfolio" class="rj-sg-btn rj-sg-btn-outline">
                        <i class="fa-solid fa-images"></i>
                        <span>View Portfolio</span>
                    </a>
                </div>

                <div class="rj-sg-hero-stats">
                    <div class="rj-sg-stat">
                        <span class="rj-sg-stat-value">15<span class="rj-sg-stat-plus">+</span></span>
                        <span class="rj-sg-stat-label">Years Experience</span>
                    </div>
                    <div class="rj-sg-stat-div"></div>
                    <div class="rj-sg-stat">
                        <span class="rj-sg-stat-value">8K<span class="rj-sg-stat-plus">+</span></span>
                        <span class="rj-sg-stat-label">Signs Produced</span>
                    </div>
                    <div class="rj-sg-stat-div"></div>
                    <div class="rj-sg-stat">
                        <span class="rj-sg-stat-value">24<span class="rj-sg-stat-plus">h</span></span>
                        <span class="rj-sg-stat-label">Rush Turnaround</span>
                    </div>
                </div>
            </div>

            <div class="rj-sg-hero-visual">
                <div class="rj-sg-hero-card rj-sg-hero-card-1">
                    <div class="rj-sg-hero-card-icon"><i class="fa-solid fa-sign-hanging"></i></div>
                    <p class="rj-sg-hero-card-title">Storefront Signs</p>
                    <p class="rj-sg-hero-card-meta">From $149</p>
                </div>

                <div class="rj-sg-hero-card rj-sg-hero-card-2">
                    <div class="rj-sg-hero-card-icon"><i class="fa-solid fa-scroll"></i></div>
                    <p class="rj-sg-hero-card-title">Vinyl Banners</p>
                    <p class="rj-sg-hero-card-meta">From $39</p>
                </div>

                <div class="rj-sg-hero-card rj-sg-hero-card-3">
                    <div class="rj-sg-hero-card-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <p class="rj-sg-hero-card-title">Install Service</p>
                    <p class="rj-sg-hero-card-meta">Same Day</p>
                </div>

                <div class="rj-sg-hero-mockup">
                    <div class="rj-sg-hero-mockup-inner">
                        <div class="rj-sg-hero-mockup-sign">
                            <span>YOUR BRAND</span>
                        </div>
                        <div class="rj-sg-hero-mockup-sub">Premium Vinyl Signage</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= TRUST BAR ================= --}}
<section class="rj-sg-trust">
    <div class="rj-sg-inner">
        <div class="rj-sg-trust-grid">
            <div class="rj-sg-trust-item">
                <i class="fa-solid fa-award"></i>
                <div>
                    <p class="rj-sg-trust-title">5-Year Warranty</p>
                    <p class="rj-sg-trust-desc">On all outdoor signage</p>
                </div>
            </div>
            <div class="rj-sg-trust-item">
                <i class="fa-solid fa-shield-halved"></i>
                <div>
                    <p class="rj-sg-trust-title">UV-Resistant</p>
                    <p class="rj-sg-trust-desc">Colors that last for years</p>
                </div>
            </div>
            <div class="rj-sg-trust-item">
                <i class="fa-solid fa-bolt"></i>
                <div>
                    <p class="rj-sg-trust-title">Fast Turnaround</p>
                    <p class="rj-sg-trust-desc">Ready in 24-72 hours</p>
                </div>
            </div>
            <div class="rj-sg-trust-item">
                <i class="fa-solid fa-truck"></i>
                <div>
                    <p class="rj-sg-trust-title">Free Delivery</p>
                    <p class="rj-sg-trust-desc">On orders over $99</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= SERVICES ================= --}}
<section class="rj-sg-section">
    <div class="rj-sg-grid-bg"></div>
    <div class="rj-sg-inner">
        <div class="rj-sg-head">
            <div class="rj-sg-eyebrow">
                <span class="rj-sg-eyebrow-line"></span>
                <span class="rj-sg-eyebrow-text">What we make</span>
            </div>
            <h2 class="rj-sg-h2">Our signage services</h2>
            <p class="rj-sg-sub">From single vinyl cuts to full building wraps — we handle it all.</p>
        </div>

        <div class="rj-sg-services">

            <a href="{{ url('design') }}" class="rj-sg-service">
                <div class="rj-sg-service-head">
                    <div class="rj-sg-service-icon">
                        <i class="fa-solid fa-scroll"></i>
                    </div>
                    <span class="rj-sg-service-tag">Most Popular</span>
                </div>
                <h3 class="rj-sg-service-title">Vinyl Signs</h3>
                <p class="rj-sg-service-desc">High-quality adhesive vinyl for windows, walls, vehicles and more. Available in cut, printed and laminated finishes.</p>
                <ul class="rj-sg-service-list">
                    <li><i class="fa-solid fa-check"></i> Indoor & outdoor</li>
                    <li><i class="fa-solid fa-check"></i> Full color print</li>
                    <li><i class="fa-solid fa-check"></i> UV laminate</li>
                </ul>
                <span class="rj-sg-service-cta">
                    <span>Configure</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="{{ url('design') }}" class="rj-sg-service">
                <div class="rj-sg-service-head">
                    <div class="rj-sg-service-icon">
                        <i class="fa-solid fa-rectangle-ad"></i>
                    </div>
                </div>
                <h3 class="rj-sg-service-title">Banners</h3>
                <p class="rj-sg-service-desc">Durable 13oz vinyl banners with reinforced hems and grommets. Perfect for events, stores and construction sites.</p>
                <ul class="rj-sg-service-list">
                    <li><i class="fa-solid fa-check"></i> 13oz heavy vinyl</li>
                    <li><i class="fa-solid fa-check"></i> Wind-tested</li>
                    <li><i class="fa-solid fa-check"></i> Any size</li>
                </ul>
                <span class="rj-sg-service-cta">
                    <span>Configure</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="#quote" class="rj-sg-service">
                <div class="rj-sg-service-head">
                    <div class="rj-sg-service-icon">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    <span class="rj-sg-service-tag rj-sg-service-tag-alt">Custom</span>
                </div>
                <h3 class="rj-sg-service-title">Custom Signs</h3>
                <p class="rj-sg-service-desc">3D signs, lightboxes, monument signs and complete storefront buildouts. Tell us your vision — we'll make it real.</p>
                <ul class="rj-sg-service-list">
                    <li><i class="fa-solid fa-check"></i> 3D channel letters</li>
                    <li><i class="fa-solid fa-check"></i> LED lightboxes</li>
                    <li><i class="fa-solid fa-check"></i> Design + install</li>
                </ul>
                <span class="rj-sg-service-cta">
                    <span>Request Quote</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

        </div>
    </div>
</section>

{{-- ================= MATERIALS ================= --}}
<section class="rj-sg-section rj-sg-section-alt">
    <div class="rj-sg-grid-bg"></div>
    <div class="rj-sg-inner">
        <div class="rj-sg-head">
            <div class="rj-sg-eyebrow">
                <span class="rj-sg-eyebrow-line"></span>
                <span class="rj-sg-eyebrow-text">Materials</span>
            </div>
            <h2 class="rj-sg-h2">Built to last</h2>
            <p class="rj-sg-sub">Every material is hand-picked for durability, color vibrancy and finish quality.</p>
        </div>

        <div class="rj-sg-materials">
            <div class="rj-sg-material">
                <div class="rj-sg-material-dot" style="background: linear-gradient(135deg, #6366f1, #a855f7);"></div>
                <h4>Oracal 651 Vinyl</h4>
                <p>4-6 year outdoor durability. 60+ colors.</p>
            </div>
            <div class="rj-sg-material">
                <div class="rj-sg-material-dot" style="background: linear-gradient(135deg, #ec4899, #f43f5e);"></div>
                <h4>Premium Print Vinyl</h4>
                <p>High-res solvent print with UV laminate.</p>
            </div>
            <div class="rj-sg-material">
                <div class="rj-sg-material-dot" style="background: linear-gradient(135deg, #10b981, #34d399);"></div>
                <h4>13oz Banner Vinyl</h4>
                <p>Wind-resistant, reinforced hems.</p>
            </div>
            <div class="rj-sg-material">
                <div class="rj-sg-material-dot" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);"></div>
                <h4>Acrylic & Aluminum</h4>
                <p>Rigid substrates for premium signage.</p>
            </div>
            <div class="rj-sg-material">
                <div class="rj-sg-material-dot" style="background: linear-gradient(135deg, #06b6d4, #0ea5e9);"></div>
                <h4>Reflective Vinyl</h4>
                <p>DOT-compliant for vehicles and roads.</p>
            </div>
            <div class="rj-sg-material">
                <div class="rj-sg-material-dot" style="background: linear-gradient(135deg, #8b5cf6, #6366f1);"></div>
                <h4>Perforated Window Film</h4>
                <p>See-through graphics for storefronts.</p>
            </div>
        </div>
    </div>
</section>

{{-- ================= PROCESS ================= --}}
<section class="rj-sg-section">
    <div class="rj-sg-grid-bg"></div>
    <div class="rj-sg-inner">
        <div class="rj-sg-head">
            <div class="rj-sg-eyebrow">
                <span class="rj-sg-eyebrow-line"></span>
                <span class="rj-sg-eyebrow-text">How it works</span>
            </div>
            <h2 class="rj-sg-h2">From idea to installation</h2>
            <p class="rj-sg-sub">A simple four-step process — from first sketch to final install.</p>
        </div>

        <div class="rj-sg-process">
            <div class="rj-sg-step">
                <div class="rj-sg-step-num">01</div>
                <div class="rj-sg-step-line"></div>
                <h3 class="rj-sg-step-title">Brief</h3>
                <p class="rj-sg-step-text">Tell us what you need — size, location, message, style. We'll ask the right questions.</p>
            </div>
            <div class="rj-sg-step">
                <div class="rj-sg-step-num">02</div>
                <div class="rj-sg-step-line"></div>
                <h3 class="rj-sg-step-title">Design</h3>
                <p class="rj-sg-step-text">Our designers create a mockup. You approve — or we revise until it's perfect.</p>
            </div>
            <div class="rj-sg-step">
                <div class="rj-sg-step-num">03</div>
                <div class="rj-sg-step-line"></div>
                <h3 class="rj-sg-step-title">Production</h3>
                <p class="rj-sg-step-text">High-res printing, precision cutting, quality checks. Ready in 24-72 hours.</p>
            </div>
            <div class="rj-sg-step">
                <div class="rj-sg-step-num">04</div>
                <div class="rj-sg-step-line"></div>
                <h3 class="rj-sg-step-title">Install</h3>
                <p class="rj-sg-step-text">Pick up, or let our team install it. Every sign is installed to professional standards.</p>
            </div>
        </div>
    </div>
</section>

{{-- ================= PORTFOLIO ================= --}}
<section class="rj-sg-section rj-sg-section-alt" id="portfolio">
    <div class="rj-sg-grid-bg"></div>
    <div class="rj-sg-inner">
        <div class="rj-sg-head">
            <div class="rj-sg-eyebrow">
                <span class="rj-sg-eyebrow-line"></span>
                <span class="rj-sg-eyebrow-text">Recent work</span>
            </div>
            <h2 class="rj-sg-h2">Portfolio</h2>
            <p class="rj-sg-sub">A glimpse of the signs we've shipped for clients across the country.</p>
        </div>

        <div class="rj-sg-portfolio">
            @for ($i = 1; $i <= 6; $i++)
                <div class="rj-sg-portfolio-item rj-sg-portfolio-item-{{ $i }}">
                    <div class="rj-sg-portfolio-inner">
                        <div class="rj-sg-portfolio-mock">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        <div class="rj-sg-portfolio-overlay">
                            <p class="rj-sg-portfolio-label">Project {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</p>
                            <p class="rj-sg-portfolio-cat">Signage</p>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <div class="rj-sg-portfolio-cta">
            <a href="{{ url('contact-us') }}" class="rj-sg-btn rj-sg-btn-outline">
                <i class="fa-solid fa-plus"></i>
                <span>See more projects</span>
            </a>
        </div>
    </div>
</section>

{{-- ================= CTA / QUOTE ================= --}}
<section class="rj-sg-cta" id="quote">
    <div class="rj-sg-cta-grid-bg"></div>
    <div class="rj-sg-cta-orb"></div>
    <div class="rj-sg-inner">
        <div class="rj-sg-cta-inner">
            <div class="rj-sg-cta-left">
                <div class="rj-sg-eyebrow">
                    <span class="rj-sg-eyebrow-line"></span>
                    <span class="rj-sg-eyebrow-text">Free Quote</span>
                </div>
                <h2 class="rj-sg-cta-title">Ready to make your mark?</h2>
                <p class="rj-sg-cta-desc">
                    Send us your idea — photo, sketch, or just a sentence. We'll come back within 24 hours with a detailed quote.
                </p>
                <div class="rj-sg-cta-actions">
                    <a href="{{ url('contact-us') }}" class="rj-sg-btn rj-sg-btn-primary">
                        <i class="fa-solid fa-comments"></i>
                        <span>Request Quote</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="tel:+15550000000" class="rj-sg-btn rj-sg-btn-outline">
                        <i class="fa-solid fa-phone"></i>
                        <span>Call Us</span>
                    </a>
                </div>
            </div>

            <div class="rj-sg-cta-right">
                <div class="rj-sg-cta-card">
                    <div class="rj-sg-cta-card-icon">
                        <i class="fa-solid fa-clock"></i>
                    </div>
                    <p class="rj-sg-cta-card-value">24h</p>
                    <p class="rj-sg-cta-card-label">Quote response</p>
                </div>
                <div class="rj-sg-cta-card">
                    <div class="rj-sg-cta-card-icon">
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <p class="rj-sg-cta-card-value">Free</p>
                    <p class="rj-sg-cta-card-label">Design mockup</p>
                </div>
                <div class="rj-sg-cta-card">
                    <div class="rj-sg-cta-card-icon">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <p class="rj-sg-cta-card-value">100%</p>
                    <p class="rj-sg-cta-card-label">Satisfaction</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================= FAQ ================= --}}
<section class="rj-sg-section">
    <div class="rj-sg-grid-bg"></div>
    <div class="rj-sg-inner">
        <div class="rj-sg-head">
            <div class="rj-sg-eyebrow">
                <span class="rj-sg-eyebrow-line"></span>
                <span class="rj-sg-eyebrow-text">Help Center</span>
            </div>
            <h2 class="rj-sg-h2">Frequently asked questions</h2>
        </div>

        @php
            $signFaqs = [
                ['q' => 'How long does production take?', 'a' => 'Standard turnaround is 2-3 business days. Rush orders can be ready within 24 hours for an additional fee.'],
                ['q' => 'Do you install signs?', 'a' => 'Yes — we offer professional installation for all types of signage, including storefronts, vehicles and building facades.'],
                ['q' => 'What file formats do you accept?', 'a' => 'We accept AI, PDF, EPS, SVG, PSD, and high-resolution PNG or JPG. Vector files are preferred for the best results.'],
                ['q' => 'Can I see a mockup before ordering?', 'a' => 'Absolutely. We provide a digital proof for every project. Production only starts after your approval.'],
                ['q' => 'Do you ship nationwide?', 'a' => 'Yes, we ship across the entire country. Free shipping on orders over $99.'],
            ];
        @endphp

        <div class="rj-sg-faq-list" x-data="{ open: null }">
            @foreach($signFaqs as $index => $faq)
                <div class="rj-sg-faq" :class="open === {{ $index }} ? 'rj-sg-faq-open' : ''">
                    <button type="button"
                            @click="open = open === {{ $index }} ? null : {{ $index }}"
                            class="rj-sg-faq-q">
                        <span class="rj-sg-faq-num">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="rj-sg-faq-qtext">{{ $faq['q'] }}</span>
                        <span class="rj-sg-faq-icon">
                            <i class="fa-solid fa-plus" :class="open === {{ $index }} ? 'rj-sg-faq-icon-rot' : ''"></i>
                        </span>
                    </button>
                    <div x-show="open === {{ $index }}" x-collapse x-cloak>
                        <div class="rj-sg-faq-a">{{ $faq['a'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="rj-sg-faq-more">
            <a href="{{ url('faq') }}" class="rj-sg-btn rj-sg-btn-outline">
                <span>View all FAQs</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<style>
    /* ==================== HERO ==================== */
    .rj-sg-hero {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 2rem 0 5rem;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sg-hero { padding: 3rem 0 7rem; } }

    .rj-sg-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-sg-orb {
        position: absolute; width: 500px; height: 500px; border-radius: 50%;
        filter: blur(140px); pointer-events: none;
    }
    .rj-sg-orb-a { top: 0; left: 15%; background: rgba(99, 102, 241, 0.09); }
    .rj-sg-orb-b { bottom: 0; right: 15%; background: rgba(236, 72, 153, 0.08); }

    .rj-sg-inner { position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; }
    @media (min-width: 1024px) { .rj-sg-inner { padding: 0 3rem; } }

    .rj-sg-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 2rem;
    }
    .rj-sg-breadcrumb a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .rj-sg-breadcrumb a:hover { color: #a5b4fc; }
    .rj-sg-breadcrumb .current { color: #9ca3af; }

    .rj-sg-hero-grid {
        display: grid; grid-template-columns: 1fr; gap: 3rem;
        align-items: center;
    }
    @media (min-width: 900px) {
        .rj-sg-hero-grid { grid-template-columns: 1.15fr 1fr; gap: 4rem; }
    }

    .rj-sg-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .rj-sg-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-sg-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }

    .rj-sg-hero-title {
        font-size: clamp(2rem, 5vw, 3.5rem);
        font-weight: 900; line-height: 1.05; letter-spacing: -0.03em;
        color: #fff; margin: 0 0 1.25rem;
    }
    .rj-sg-grad {
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .rj-sg-hero-desc {
        font-size: 1.0625rem; color: #9ca3af; line-height: 1.75;
        margin: 0 0 2rem; max-width: 32rem;
    }

    .rj-sg-hero-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; margin-bottom: 2.5rem; }

    .rj-sg-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem; font-weight: 600; font-size: 0.9375rem;
        padding: 1rem 1.75rem; border-radius: 9999px;
        text-decoration: none; transition: all 0.3s ease;
        cursor: pointer; border: none; font-family: inherit;
    }
    .rj-sg-btn i { font-size: 12px; }
    .rj-sg-btn-primary {
        background: #fff; color: #05030f;
    }
    .rj-sg-btn-primary:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.4);
        transform: translateY(-1px);
    }
    .rj-sg-btn-primary i:last-child { transition: transform 0.3s; }
    .rj-sg-btn-primary:hover i:last-child { transform: translateX(4px); }
    .rj-sg-btn-outline {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .rj-sg-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }

    .rj-sg-hero-stats {
        display: flex; align-items: center; gap: 1.5rem;
        padding-top: 1.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
    }
    .rj-sg-stat { display: flex; flex-direction: column; gap: 2px; }
    .rj-sg-stat-value {
        font-family: ui-monospace, monospace;
        font-size: 1.5rem; font-weight: 900;
        color: #fff; letter-spacing: -0.02em;
    }
    .rj-sg-stat-plus { color: #818cf8; font-size: 1.125rem; }
    .rj-sg-stat-label {
        font-family: ui-monospace, monospace;
        font-size: 9px; text-transform: uppercase;
        letter-spacing: 0.15em; color: #6b7280;
    }
    .rj-sg-stat-div { width: 1px; height: 32px; background: rgba(255, 255, 255, 0.08); }

    /* Hero Visual */
    .rj-sg-hero-visual {
        position: relative;
        aspect-ratio: 1;
        min-height: 320px;
    }

    .rj-sg-hero-mockup {
        position: absolute;
        inset: 8% 12%;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(236, 72, 153, 0.06));
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
    }
    .rj-sg-hero-mockup::before {
        content: '';
        position: absolute;
        inset: -20px;
        border-radius: 28px;
        border: 1px dashed rgba(99, 102, 241, 0.15);
    }
    .rj-sg-hero-mockup-inner {
        text-align: center;
        padding: 2rem;
    }
    .rj-sg-hero-mockup-sign {
        display: inline-block;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        border-radius: 12px;
        font-weight: 900;
        font-size: 1.25rem;
        letter-spacing: 0.15em;
        color: #fff;
        box-shadow: 0 20px 50px -10px rgba(168, 85, 247, 0.5);
        margin-bottom: 1rem;
    }
    .rj-sg-hero-mockup-sub {
        font-family: ui-monospace, monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3em;
        color: #818cf8;
    }

    .rj-sg-hero-card {
        position: absolute;
        background: rgba(10, 7, 21, 0.9);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        flex-direction: column;
        gap: 4px;
        min-width: 160px;
        z-index: 2;
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.6);
        animation: rj-sg-float 6s ease-in-out infinite;
    }
    .rj-sg-hero-card-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(236, 72, 153, 0.2));
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 13px;
        margin-bottom: 4px;
    }
    .rj-sg-hero-card-title {
        font-size: 13px; font-weight: 700; color: #fff;
        margin: 0;
    }
    .rj-sg-hero-card-meta {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #818cf8;
        margin: 0; letter-spacing: 0.05em;
    }
    .rj-sg-hero-card-1 { top: 5%; left: -5%; animation-delay: 0s; }
    .rj-sg-hero-card-2 { top: 42%; right: -8%; animation-delay: 2s; }
    .rj-sg-hero-card-3 { bottom: 5%; left: 8%; animation-delay: 4s; }

    @keyframes rj-sg-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }

    /* ==================== TRUST BAR ==================== */
    .rj-sg-trust {
        position: relative;
        background: #0a0715;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        padding: 1.75rem 0;
    }
    .rj-sg-trust-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;
    }
    @media (min-width: 768px) { .rj-sg-trust-grid { grid-template-columns: repeat(4, 1fr); } }

    .rj-sg-trust-item {
        display: flex; align-items: center; gap: 0.875rem;
    }
    .rj-sg-trust-item i {
        font-size: 18px;
        color: #818cf8;
        flex-shrink: 0;
    }
    .rj-sg-trust-title {
        font-size: 13px; font-weight: 700; color: #fff;
        margin: 0 0 2px;
    }
    .rj-sg-trust-desc {
        font-size: 11px; color: #6b7280; margin: 0;
    }

    /* ==================== SECTIONS ==================== */
    .rj-sg-section {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 4rem 0;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sg-section { padding: 6rem 0; } }
    .rj-sg-section-alt { background: #0a0715; }

    .rj-sg-head { margin-bottom: 3rem; max-width: 42rem; }
    .rj-sg-h2 {
        font-size: clamp(1.5rem, 3.5vw, 2.5rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 0.75rem;
    }
    .rj-sg-sub { font-size: 1rem; color: #9ca3af; line-height: 1.7; margin: 0; }

    /* ==================== SERVICES ==================== */
    .rj-sg-services {
        display: grid; grid-template-columns: 1fr; gap: 1.25rem;
    }
    @media (min-width: 640px) { .rj-sg-services { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .rj-sg-services { grid-template-columns: repeat(3, 1fr); } }

    .rj-sg-service {
        display: flex; flex-direction: column; gap: 1rem;
        padding: 1.75rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 20px;
        text-decoration: none; color: inherit;
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
        overflow: hidden;
    }
    .rj-sg-service::before {
        content: ''; position: absolute; inset: 0;
        background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.08), transparent 60%);
        opacity: 0; transition: opacity 0.4s;
        pointer-events: none;
    }
    .rj-sg-service:hover {
        transform: translateY(-4px);
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 24px 48px -16px rgba(99, 102, 241, 0.35);
    }
    .rj-sg-service:hover::before { opacity: 1; }

    .rj-sg-service-head {
        display: flex; align-items: center; justify-content: space-between; gap: 1rem;
        position: relative;
    }
    .rj-sg-service-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(236, 72, 153, 0.12));
        border: 1px solid rgba(99, 102, 241, 0.25);
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 20px;
    }
    .rj-sg-service-tag {
        font-family: ui-monospace, monospace;
        font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.15em;
        padding: 4px 10px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        border-radius: 9999px;
    }
    .rj-sg-service-tag-alt {
        background: rgba(16, 185, 129, 0.15);
        color: #6ee7b7;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .rj-sg-service-title {
        font-size: 1.375rem; font-weight: 800; color: #fff;
        margin: 0; letter-spacing: -0.01em;
    }
    .rj-sg-service-desc {
        font-size: 13.5px; color: #9ca3af;
        line-height: 1.65; margin: 0;
    }
    .rj-sg-service-list {
        list-style: none; padding: 0; margin: 0;
        display: flex; flex-direction: column; gap: 0.5rem;
    }
    .rj-sg-service-list li {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 12.5px; color: #d1d5db;
    }
    .rj-sg-service-list i {
        color: #34d399; font-size: 9px;
        background: rgba(52, 211, 153, 0.15);
        border-radius: 50%;
        width: 16px; height: 16px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .rj-sg-service-cta {
        display: inline-flex; align-items: center; gap: 0.5rem;
        font-family: ui-monospace, monospace;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #a5b4fc;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        transition: color 0.2s;
    }
    .rj-sg-service-cta i { transition: transform 0.3s; font-size: 10px; }
    .rj-sg-service:hover .rj-sg-service-cta { color: #fff; }
    .rj-sg-service:hover .rj-sg-service-cta i { transform: translateX(4px); }

    /* ==================== MATERIALS ==================== */
    .rj-sg-materials {
        display: grid; grid-template-columns: 1fr; gap: 1rem;
    }
    @media (min-width: 640px) { .rj-sg-materials { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .rj-sg-materials { grid-template-columns: repeat(3, 1fr); } }

    .rj-sg-material {
        display: flex; flex-direction: column; gap: 0.5rem;
        padding: 1.25rem 1.5rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        transition: all 0.3s;
    }
    .rj-sg-material:hover {
        border-color: rgba(99, 102, 241, 0.3);
        background: rgba(255, 255, 255, 0.025);
        transform: translateY(-2px);
    }
    .rj-sg-material-dot {
        width: 32px; height: 32px;
        border-radius: 8px;
        box-shadow: 0 0 20px -4px currentColor;
        margin-bottom: 0.25rem;
    }
    .rj-sg-material h4 {
        font-size: 14px; font-weight: 700; color: #fff;
        margin: 0;
    }
    .rj-sg-material p {
        font-size: 12.5px; color: #6b7280;
        margin: 0; line-height: 1.5;
    }

    /* ==================== PROCESS ==================== */
    .rj-sg-process {
        display: grid; grid-template-columns: 1fr; gap: 1.25rem;
    }
    @media (min-width: 640px) { .rj-sg-process { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 1024px) { .rj-sg-process { grid-template-columns: repeat(4, 1fr); } }

    .rj-sg-step {
        padding: 1.5rem 1.25rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        transition: all 0.4s ease;
    }
    .rj-sg-step:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.4);
        transform: translateY(-3px);
    }
    .rj-sg-step-num {
        font-family: ui-monospace, monospace;
        font-size: 11px; color: #818cf8;
        letter-spacing: 0.15em; margin-bottom: 0.75rem;
    }
    .rj-sg-step-line {
        width: 32px; height: 2px;
        background: linear-gradient(90deg, #6366f1, #ec4899);
        border-radius: 2px; margin-bottom: 1rem;
    }
    .rj-sg-step-title {
        font-size: 1.25rem; font-weight: 800; color: #fff;
        letter-spacing: -0.01em; margin: 0 0 0.5rem;
    }
    .rj-sg-step-text {
        font-size: 13px; color: #9ca3af;
        line-height: 1.6; margin: 0;
    }

    /* ==================== PORTFOLIO ==================== */
    .rj-sg-portfolio {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 1fr;
        gap: 0.75rem;
    }
    @media (min-width: 768px) {
        .rj-sg-portfolio {
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    .rj-sg-portfolio-item {
        position: relative;
        overflow: hidden;
        border-radius: 14px;
        aspect-ratio: 1;
        border: 1px solid rgba(255, 255, 255, 0.06);
        background: #0a0715;
        transition: all 0.4s;
        cursor: pointer;
    }
    @media (min-width: 768px) {
        .rj-sg-portfolio-item-1 { grid-column: span 2; grid-row: span 2; aspect-ratio: auto; }
        .rj-sg-portfolio-item-6 { grid-column: span 2; aspect-ratio: auto; }
    }
    .rj-sg-portfolio-item:hover {
        border-color: rgba(99, 102, 241, 0.4);
        transform: scale(1.02);
    }
    .rj-sg-portfolio-inner {
        position: relative;
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
    }
    .rj-sg-portfolio-mock {
        font-size: 3rem; color: rgba(99, 102, 241, 0.15);
    }
    .rj-sg-portfolio-overlay {
        position: absolute; inset: auto 0 0 0;
        padding: 1rem;
        background: linear-gradient(to top, rgba(5, 3, 15, 0.95), transparent);
    }
    .rj-sg-portfolio-label {
        font-family: ui-monospace, monospace;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #fff; margin: 0 0 2px;
    }
    .rj-sg-portfolio-cat {
        font-size: 11px; color: #a5b4fc; margin: 0;
    }

    .rj-sg-portfolio-cta {
        display: flex; justify-content: center; margin-top: 2.5rem;
    }

    /* ==================== CTA ==================== */
    .rj-sg-cta {
        position: relative;
        padding: 4rem 0;
        background: #05030f;
        overflow: hidden;
    }
    @media (min-width: 768px) { .rj-sg-cta { padding: 6rem 0; } }

    .rj-sg-cta-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-sg-cta-orb {
        position: absolute;
        width: 600px; height: 600px;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        border-radius: 50%;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.15), transparent 70%);
        pointer-events: none;
    }

    .rj-sg-cta-inner {
        position: relative;
        padding: 3rem 2rem;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(236, 72, 153, 0.06));
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 24px;
        display: grid; grid-template-columns: 1fr; gap: 2rem;
        align-items: center;
    }
    @media (min-width: 900px) {
        .rj-sg-cta-inner { grid-template-columns: 1.3fr 1fr; gap: 4rem; padding: 3.5rem; }
    }

    .rj-sg-cta-title {
        font-size: clamp(1.75rem, 3.5vw, 2.5rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 1rem;
    }
    .rj-sg-cta-desc {
        font-size: 1rem; color: #9ca3af; line-height: 1.7;
        margin: 0 0 1.75rem; max-width: 32rem;
    }
    .rj-sg-cta-actions { display: flex; flex-wrap: wrap; gap: 0.75rem; }

    .rj-sg-cta-right {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
    }
    .rj-sg-cta-card {
        display: flex; flex-direction: column; gap: 0.5rem;
        padding: 1.25rem 1rem;
        background: rgba(5, 3, 15, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 14px;
        text-align: center;
        transition: all 0.3s;
    }
    .rj-sg-cta-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
        background: rgba(5, 3, 15, 0.85);
    }
    .rj-sg-cta-card-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(236, 72, 153, 0.15));
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 14px;
        margin: 0 auto;
    }
    .rj-sg-cta-card-value {
        font-family: ui-monospace, monospace;
        font-size: 1.25rem; font-weight: 900;
        color: #fff; margin: 0.25rem 0 0;
        letter-spacing: -0.02em;
    }
    .rj-sg-cta-card-label {
        font-family: ui-monospace, monospace;
        font-size: 9px; text-transform: uppercase;
        letter-spacing: 0.15em; color: #6b7280;
        margin: 0;
    }

    /* ==================== FAQ ==================== */
    .rj-sg-faq-list {
        display: flex; flex-direction: column; gap: 0.75rem;
        max-width: 48rem; margin: 0 auto;
    }
    .rj-sg-faq {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px; overflow: hidden;
        transition: all 0.3s ease;
    }
    .rj-sg-faq:hover { border-color: rgba(255, 255, 255, 0.12); }
    .rj-sg-faq-open {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.3);
        box-shadow: 0 0 30px -8px rgba(99, 102, 241, 0.4);
    }
    .rj-sg-faq-q {
        width: 100%; text-align: left; padding: 1.25rem 1.5rem;
        display: flex; align-items: center; gap: 1rem;
        cursor: pointer; background: transparent; border: none;
        color: inherit; font-family: inherit;
    }
    .rj-sg-faq-num {
        font-family: ui-monospace, monospace; font-size: 11px;
        font-weight: 700; color: rgba(129, 140, 248, 0.8);
        letter-spacing: 0.1em; width: 2rem; flex-shrink: 0;
    }
    .rj-sg-faq-qtext {
        flex: 1; font-size: 15px; font-weight: 600; color: #fff;
        line-height: 1.4; padding-right: 0.5rem;
    }
    .rj-sg-faq-icon {
        width: 2rem; height: 2rem; border-radius: 50%;
        background: rgba(255, 255, 255, 0.04);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; transition: background 0.3s ease;
    }
    .rj-sg-faq-q:hover .rj-sg-faq-icon { background: rgba(99, 102, 241, 0.15); }
    .rj-sg-faq-icon i {
        font-size: 11px; color: #9ca3af;
        transition: all 0.3s ease;
    }
    .rj-sg-faq-q:hover .rj-sg-faq-icon i { color: #a5b4fc; }
    .rj-sg-faq-icon-rot { transform: rotate(45deg); color: #a5b4fc !important; }
    .rj-sg-faq-a {
        padding: 0 1.5rem 1.5rem 3.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        padding-top: 1.25rem; font-size: 14px; line-height: 1.7;
        color: #9ca3af;
    }
    .rj-sg-faq-more {
        margin-top: 2.5rem; display: flex; justify-content: center;
    }
</style>

@endsection

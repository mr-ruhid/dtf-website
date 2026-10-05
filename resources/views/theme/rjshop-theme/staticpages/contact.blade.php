@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $page->seo_title)
@section('meta_description', $page->seo_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')

<style>
.rjc-wrap{position:relative;background:#05030f;color:#fff;font-family:system-ui,-apple-system,sans-serif;overflow:hidden;}
.rjc-grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(99,102,241,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(99,102,241,.04) 1px,transparent 1px);background-size:50px 50px;pointer-events:none;}
.rjc-glow-1{position:absolute;top:-10%;left:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(99,102,241,.15),transparent 70%);filter:blur(80px);pointer-events:none;}
.rjc-glow-2{position:absolute;bottom:-10%;right:-10%;width:600px;height:600px;background:radial-gradient(circle,rgba(236,72,153,.15),transparent 70%);filter:blur(80px);pointer-events:none;}

.rjc-hero{position:relative;padding:90px 24px 60px;text-align:center;z-index:2;}
.rjc-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 18px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:999px;font-family:ui-monospace,monospace;font-size:11px;color:#a5b4fc;letter-spacing:.15em;text-transform:uppercase;margin-bottom:26px;backdrop-filter:blur(10px);}
.rjc-badge-dot{width:6px;height:6px;background:#34d399;border-radius:50%;box-shadow:0 0 10px 2px rgba(52,211,153,.8);animation:rjc-pulse 2s ease-in-out infinite;}
.rjc-title{font-size:clamp(2.5rem,6vw,5rem);font-weight:900;line-height:1;letter-spacing:-.03em;margin:0 0 20px;}
.rjc-title-grad{display:block;background:linear-gradient(90deg,#818cf8,#a855f7,#ec4899);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;color:transparent;}
.rjc-sub{font-size:clamp(1rem,2vw,1.2rem);color:#9ca3af;max-width:600px;margin:0 auto;line-height:1.7;font-weight:300;}

.rjc-section{position:relative;padding:40px 24px 100px;z-index:2;}
.rjc-container{max-width:1200px;margin:0 auto;}

.rjc-grid{display:grid;grid-template-columns:1fr 1.2fr;gap:40px;align-items:start;}

.rjc-info{display:flex;flex-direction:column;gap:20px;}
.rjc-info-card{position:relative;padding:28px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.08);border-radius:20px;transition:all .4s;display:flex;gap:20px;align-items:flex-start;}
.rjc-info-card:hover{background:rgba(255,255,255,.05);border-color:rgba(129,140,248,.4);transform:translateX(4px);}
.rjc-info-icon{width:52px;height:52px;flex-shrink:0;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:20px;color:#fff;}
.rjc-icon-1{background:linear-gradient(135deg,#6366f1,#3b82f6);box-shadow:0 8px 24px rgba(99,102,241,.35);}
.rjc-icon-2{background:linear-gradient(135deg,#a855f7,#d946ef);box-shadow:0 8px 24px rgba(168,85,247,.35);}
.rjc-icon-3{background:linear-gradient(135deg,#ec4899,#f43f5e);box-shadow:0 8px 24px rgba(236,72,153,.35);}
.rjc-info-content{flex:1;min-width:0;}
.rjc-info-label{font-family:ui-monospace,monospace;font-size:10px;color:#818cf8;letter-spacing:.2em;text-transform:uppercase;margin-bottom:6px;display:block;}
.rjc-info-value{color:#fff;font-size:15px;font-weight:600;text-decoration:none;word-break:break-word;transition:color .2s;}
.rjc-info-value:hover{color:#a5b4fc;}
.rjc-info-desc{color:#6b7280;font-size:12px;margin-top:4px;}

.rjc-socials-title{font-family:ui-monospace,monospace;font-size:10px;color:#818cf8;letter-spacing:.2em;text-transform:uppercase;margin:12px 0 12px;}
.rjc-socials{display:flex;gap:10px;flex-wrap:wrap;}
.rjc-social{width:44px;height:44px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:12px;color:#9ca3af;font-size:16px;text-decoration:none;transition:all .3s;}
.rjc-social:hover{color:#fff;border-color:rgba(129,140,248,.5);background:rgba(99,102,241,.1);transform:translateY(-3px);box-shadow:0 8px 24px rgba(99,102,241,.25);}

.rjc-form-box{position:relative;padding:40px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.08);border-radius:24px;backdrop-filter:blur(10px);}
.rjc-form-head{margin-bottom:28px;}
.rjc-form-title{font-size:1.75rem;font-weight:900;margin:0 0 6px;letter-spacing:-.02em;}
.rjc-form-desc{color:#9ca3af;font-size:14px;margin:0;}
.rjc-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;}
.rjc-field{margin-bottom:16px;}
.rjc-label{display:block;font-family:ui-monospace,monospace;font-size:10px;color:#818cf8;letter-spacing:.15em;text-transform:uppercase;margin-bottom:8px;}
.rjc-input,.rjc-textarea{width:100%;padding:14px 16px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.1);border-radius:12px;color:#fff;font-size:14px;font-family:inherit;transition:all .3s;outline:none;box-sizing:border-box;}
.rjc-input::placeholder,.rjc-textarea::placeholder{color:#4b5563;}
.rjc-input:focus,.rjc-textarea:focus{border-color:rgba(129,140,248,.6);background:rgba(99,102,241,.05);box-shadow:0 0 24px rgba(99,102,241,.25);}
.rjc-textarea{resize:vertical;min-height:140px;}
.rjc-submit{width:100%;padding:16px 32px;background:linear-gradient(135deg,#6366f1,#a855f7,#ec4899);color:#fff;border:none;border-radius:12px;font-weight:700;font-size:15px;cursor:pointer;transition:all .3s;display:inline-flex;align-items:center;justify-content:center;gap:10px;font-family:inherit;}
.rjc-submit:hover{box-shadow:0 12px 40px rgba(168,85,247,.5);transform:translateY(-2px);}
.rjc-form-note{margin-top:16px;font-family:ui-monospace,monospace;font-size:10px;color:#4b5563;text-align:center;letter-spacing:.1em;}

@keyframes rjc-pulse{0%,100%{opacity:.5;transform:scale(1);}50%{opacity:.85;transform:scale(1.05);}}

@media(max-width:900px){
    .rjc-grid{grid-template-columns:1fr;gap:32px;}
    .rjc-row{grid-template-columns:1fr;}
    .rjc-hero{padding:70px 20px 40px;}
    .rjc-section{padding:30px 20px 70px;}
    .rjc-form-box{padding:28px 22px;}
}
</style>

<div class="rjc-wrap">
    <div class="rjc-grid-bg"></div>
    <div class="rjc-glow-1"></div>
    <div class="rjc-glow-2"></div>

    <section class="rjc-hero">
        <div class="rjc-badge">
            <span class="rjc-badge-dot"></span>
            <span>Signal Channel: Open</span>
        </div>
        <h1 class="rjc-title">
            <span>Get in touch</span>
            <span class="rjc-title-grad">transmit a signal</span>
        </h1>
        <p class="rjc-sub">
            {{ $page->excerpt ?: 'Have a question? We respond within 24 hours.' }}
        </p>
    </section>

    <section class="rjc-section">
        <div class="rjc-container">
            <div class="rjc-grid">

                <div class="rjc-info">

                    @if($settings['site_email'])
                        <div class="rjc-info-card">
                            <div class="rjc-info-icon rjc-icon-1">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="rjc-info-content">
                                <span class="rjc-info-label">// Email</span>
                                <a href="mailto:{{ $settings['site_email'] }}" class="rjc-info-value">{{ $settings['site_email'] }}</a>
                                <p class="rjc-info-desc">Drop us a message anytime</p>
                            </div>
                        </div>
                    @endif

                    @if($settings['site_phone'])
                        <div class="rjc-info-card">
                            <div class="rjc-info-icon rjc-icon-2">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="rjc-info-content">
                                <span class="rjc-info-label">// Phone</span>
                                <a href="tel:{{ $settings['site_phone'] }}" class="rjc-info-value">{{ $settings['site_phone'] }}</a>
                                <p class="rjc-info-desc">Mon–Fri, 9am–6pm</p>
                            </div>
                        </div>
                    @endif

                    @if($settings['site_address'])
                        <div class="rjc-info-card">
                            <div class="rjc-info-icon rjc-icon-3">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="rjc-info-content">
                                <span class="rjc-info-label">// Address</span>
                                <div class="rjc-info-value">{{ $settings['site_address'] }}</div>
                                <p class="rjc-info-desc">Visit our office</p>
                            </div>
                        </div>
                    @endif

                    @php
                        $socials = [
                            'facebook_url' => 'fa-facebook-f',
                            'instagram_url' => 'fa-instagram',
                            'twitter_url' => 'fa-x-twitter',
                            'youtube_url' => 'fa-youtube',
                            'linkedin_url' => 'fa-linkedin-in',
                            'tiktok_url' => 'fa-tiktok',
                        ];
                        $hasSocials = false;
                        foreach ($socials as $key => $icon) {
                            if (!empty($settings[$key])) { $hasSocials = true; break; }
                        }
                    @endphp

                    @if($hasSocials)
                        <div>
                            <div class="rjc-socials-title">// Follow us</div>
                            <div class="rjc-socials">
                                @foreach($socials as $key => $icon)
                                    @if(!empty($settings[$key]))
                                        <a href="{{ $settings[$key] }}" target="_blank" rel="noopener" class="rjc-social" aria-label="{{ $key }}">
                                            <i class="fa-brands {{ $icon }}"></i>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                <div class="rjc-form-box">
                    <div class="rjc-form-head">
                        <h2 class="rjc-form-title">Send a message</h2>
                        <p class="rjc-form-desc">Fill in the form below and we'll get back to you shortly.</p>
                    </div>

                    <form method="POST" action="#">
                        @csrf

                        <div class="rjc-row">
                            <div>
                                <label class="rjc-label">Name</label>
                                <input type="text" name="name" class="rjc-input" placeholder="Your name" required>
                            </div>
                            <div>
                                <label class="rjc-label">Email</label>
                                <input type="email" name="email" class="rjc-input" placeholder="your@email.com" required>
                            </div>
                        </div>

                        <div class="rjc-field">
                            <label class="rjc-label">Subject</label>
                            <input type="text" name="subject" class="rjc-input" placeholder="What's this about?">
                        </div>

                        <div class="rjc-field">
                            <label class="rjc-label">Message</label>
                            <textarea name="message" class="rjc-textarea" placeholder="Tell us more..." required></textarea>
                        </div>

                        <button type="submit" class="rjc-submit">
                            <span>Transmit Message</span>
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>

                        <div class="rjc-form-note">
                            <span>RESPONSE TIME: &lt; 24H</span>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>
</div>

@endsection

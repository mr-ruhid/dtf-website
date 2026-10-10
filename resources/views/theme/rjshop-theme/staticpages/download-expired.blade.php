@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Link Expired — ' . $order->order_number)
@section('meta_description', 'This download link has expired')
@section('meta_keywords', 'download, expired, order')

@section('content')

<section class="rj-de-hero">
    <div class="rj-de-bg"></div>
    <div class="rj-de-inner">

        <div class="rj-de-card">
            <div class="rj-de-icon">
                <i class="fa-solid fa-hourglass-end"></i>
            </div>

            <p class="rj-de-eyebrow">// Link expired</p>

            <h1 class="rj-de-title">This download link is no longer active</h1>

            <p class="rj-de-sub">
                Order <strong class="rj-de-mono">{{ $order->order_number }}</strong>
                @if($order->download_expires_at)
                    expired on <strong>{{ $order->download_expires_at->format('d M Y') }}</strong>
                @endif
                — after 5 days the files are automatically removed from this page.
            </p>

            <div class="rj-de-note">
                <i class="fa-solid fa-circle-info"></i>
                <span>Need the files again? Contact us and we'll send them to you directly.</span>
            </div>

            <div class="rj-de-actions">
                <a href="{{ url('contact-us') }}" class="rj-de-btn-primary">
                    <i class="fa-solid fa-comments"></i>
                    <span>Contact support</span>
                </a>

                @if($order->tracking_token)
                    <a href="{{ route('track.show', ['token' => $order->tracking_token]) }}" class="rj-de-btn-outline">
                        <i class="fa-solid fa-location-dot"></i>
                        <span>Track order</span>
                    </a>
                @endif

                <a href="{{ url('/') }}" class="rj-de-btn-outline">
                    <i class="fa-solid fa-house"></i>
                    <span>Home</span>
                </a>
            </div>
        </div>

    </div>
</section>

<style>
    .rj-de-hero {
        position: relative;
        background: #05030f;
        color: #fff;
        padding: 4rem 0 6rem;
        min-height: 70vh;
        display: flex;
        align-items: center;
        overflow: hidden;
    }

    .rj-de-bg {
        position: absolute; inset: 0; opacity: 0.03; pointer-events: none;
        background-image:
            linear-gradient(rgba(251, 191, 36, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(251, 191, 36, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }

    .rj-de-inner {
        position: relative;
        max-width: 40rem;
        margin: 0 auto;
        padding: 0 1.5rem;
        width: 100%;
    }

    .rj-de-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .rj-de-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(251, 191, 36, 0.15), rgba(249, 115, 22, 0.1));
        border: 1px solid rgba(251, 191, 36, 0.3);
        color: #fbbf24;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin-bottom: 1.5rem;
    }

    .rj-de-eyebrow {
        font-family: ui-monospace, monospace;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        color: #fbbf24;
        margin: 0 0 0.875rem;
        font-weight: 700;
    }

    .rj-de-title {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 900;
        line-height: 1.15;
        letter-spacing: -0.025em;
        color: #fff;
        margin: 0 0 1rem;
    }

    .rj-de-sub {
        font-size: 0.9375rem;
        color: #9ca3af;
        line-height: 1.65;
        margin: 0 0 1.75rem;
    }

    .rj-de-mono {
        font-family: ui-monospace, monospace;
        color: #c7d2fe;
    }

    .rj-de-sub strong { color: #e5e7eb; }

    .rj-de-note {
        display: flex;
        gap: 0.625rem;
        align-items: flex-start;
        text-align: left;
        padding: 0.875rem 1.125rem;
        background: rgba(99, 102, 241, 0.06);
        border: 1px solid rgba(99, 102, 241, 0.2);
        border-radius: 10px;
        font-size: 13px;
        color: #c7d2fe;
        margin-bottom: 2rem;
    }
    .rj-de-note i { color: #818cf8; margin-top: 2px; flex-shrink: 0; }

    .rj-de-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.75rem;
    }

    .rj-de-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        color: #fff;
        text-decoration: none;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.3s;
        box-shadow: 0 8px 24px -8px rgba(99, 102, 241, 0.6);
    }
    .rj-de-btn-primary:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
        box-shadow: 0 12px 32px -8px rgba(99, 102, 241, 0.8);
    }

    .rj-de-btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.875rem 1.5rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
        text-decoration: none;
        border-radius: 9999px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.25s;
    }
    .rj-de-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }

    @media (max-width: 640px) {
        .rj-de-card { padding: 2rem 1.25rem; }
        .rj-de-actions { flex-direction: column; }
        .rj-de-actions a { width: 100%; justify-content: center; }
    }
</style>

@endsection

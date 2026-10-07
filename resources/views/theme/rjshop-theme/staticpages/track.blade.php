@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Track Your Order')
@section('meta_description', 'Check the status of your order')

@section('content')

<section class="rj-tk">
    <div class="rj-tk-grid-bg"></div>
    <div class="rj-tk-orb rj-tk-orb-a"></div>
    <div class="rj-tk-orb rj-tk-orb-b"></div>

    <div class="rj-tk-inner">
        <div class="rj-tk-head">
            <div class="rj-tk-eyebrow">
                <span class="rj-tk-eyebrow-line"></span>
                <span class="rj-tk-eyebrow-text">Order Tracking</span>
            </div>
            <h1 class="rj-tk-title">Track your order</h1>
            <p class="rj-tk-sub">Enter your order number and email to see the latest status.</p>
        </div>

        <div class="rj-tk-card">
            <form method="POST" action="{{ route('track.lookup') }}">
                @csrf

                @if($errors->any())
                    <div class="rj-tk-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <div class="rj-tk-field">
                    <label>Order Number</label>
                    <input type="text"
                           name="order_number"
                           value="{{ old('order_number') }}"
                           required
                           placeholder="RJ-2026-0001">
                </div>

                <div class="rj-tk-field">
                    <label>Email</label>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           placeholder="you@example.com">
                </div>

                <button type="submit" class="rj-tk-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>Find My Order</span>
                </button>
            </form>
        </div>

        <div class="rj-tk-help">
            <i class="fa-solid fa-circle-info"></i>
            <span>Lost your tracking link? Enter your order details above or check your confirmation email.</span>
        </div>
    </div>
</section>

<style>
    .rj-tk {
        position: relative;
        background: #05030f;
        color: #fff;
        min-height: calc(100vh - 80px);
        padding: 4rem 0 6rem;
        overflow: hidden;
    }
    .rj-tk-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-tk-orb {
        position: absolute; width: 500px; height: 500px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-tk-orb-a { top: 0; left: 20%; background: rgba(99, 102, 241, 0.07); }
    .rj-tk-orb-b { bottom: 0; right: 20%; background: rgba(236, 72, 153, 0.07); }
    .rj-tk-inner {
        position: relative; max-width: 32rem; margin: 0 auto; padding: 0 1.5rem;
    }
    .rj-tk-head { text-align: center; margin-bottom: 2.5rem; }
    .rj-tk-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .rj-tk-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-tk-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }
    .rj-tk-title {
        font-size: clamp(1.75rem, 4vw, 2.5rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 0.75rem;
    }
    .rj-tk-sub { font-size: 0.9375rem; color: #6b7280; line-height: 1.7; margin: 0; }
    .rj-tk-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        padding: 2rem;
    }
    .rj-tk-error {
        display: flex; gap: 0.5rem; align-items: flex-start;
        padding: 0.75rem 1rem;
        background: rgba(244, 63, 94, 0.08);
        border: 1px solid rgba(244, 63, 94, 0.25);
        border-radius: 10px;
        font-size: 12.5px; color: #fda4af;
        margin-bottom: 1.5rem;
    }
    .rj-tk-error i { margin-top: 2px; color: #f43f5e; }
    .rj-tk-field { margin-bottom: 1.25rem; }
    .rj-tk-field label {
        display: block;
        font-family: ui-monospace, monospace;
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.18em;
        color: #9ca3af;
        margin-bottom: 0.5rem;
    }
    .rj-tk-field input {
        width: 100%;
        padding: 0.875rem 1rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        color: #fff;
        font-size: 14px;
        font-family: inherit;
        outline: none;
        transition: all 0.2s;
    }
    .rj-tk-field input:focus {
        border-color: rgba(99, 102, 241, 0.6);
        background: rgba(99, 102, 241, 0.05);
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
    }
    .rj-tk-btn {
        width: 100%;
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border: none; border-radius: 9999px;
        color: #fff; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit;
        transition: all 0.3s;
        box-shadow: 0 12px 32px -12px rgba(99, 102, 241, 0.6);
        margin-top: 0.5rem;
    }
    .rj-tk-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -12px rgba(168, 85, 247, 0.7);
    }
    .rj-tk-btn i { font-size: 12px; }
    .rj-tk-help {
        display: flex; align-items: center; gap: 0.5rem;
        justify-content: center;
        margin-top: 1.5rem;
        font-size: 12px; color: #6b7280;
        text-align: center;
        font-family: ui-monospace, monospace;
        letter-spacing: 0.05em;
    }
    .rj-tk-help i { color: #818cf8; font-size: 11px; }
</style>

@endsection

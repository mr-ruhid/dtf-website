@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Order ' . $order->order_number . ' — Tracking')
@section('meta_description', 'Track your order status')

@section('content')

@php
    $statusFlow = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    $currentIndex = array_search($order->status, $statusFlow);
    if ($currentIndex === false) $currentIndex = 0;
@endphp

<section class="rj-ts">
    <div class="rj-ts-grid-bg"></div>
    <div class="rj-ts-orb rj-ts-orb-a"></div>
    <div class="rj-ts-orb rj-ts-orb-b"></div>

    <div class="rj-ts-inner">

        <nav class="rj-ts-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <a href="{{ route('track.form') }}">Track</a>
            <span>/</span>
            <span class="current">{{ $order->order_number }}</span>
        </nav>

        <div class="rj-ts-head">
            <div class="rj-ts-head-left">
                <div class="rj-ts-eyebrow">
                    <span class="rj-ts-eyebrow-line"></span>
                    <span class="rj-ts-eyebrow-text">Order Tracking</span>
                </div>
                <h1 class="rj-ts-title">{{ $order->order_number }}</h1>
                <p class="rj-ts-date">Placed on {{ $order->created_at->format('F d, Y · H:i') }}</p>
            </div>
            <div class="rj-ts-badge rj-ts-badge-{{ $order->status_color }}">
                {{ $order->status_label }}
            </div>
        </div>

        <div class="rj-ts-timeline">
            @foreach($statusFlow as $index => $status)
                @php
                    $labels = [
                        'pending' => 'Order Placed',
                        'confirmed' => 'Confirmed',
                        'processing' => 'In Production',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                    ];
                    $isDone = $index <= $currentIndex;
                    $isCurrent = $index === $currentIndex;
                @endphp
                <div class="rj-ts-step {{ $isDone ? 'rj-ts-step-done' : '' }} {{ $isCurrent ? 'rj-ts-step-current' : '' }}">
                    <div class="rj-ts-step-dot">
                        @if($isDone && !$isCurrent)
                            <i class="fa-solid fa-check"></i>
                        @elseif($isCurrent)
                            <i class="fa-solid fa-circle"></i>
                        @else
                            <i class="fa-regular fa-circle"></i>
                        @endif
                    </div>
                    <div class="rj-ts-step-label">{{ $labels[$status] }}</div>
                </div>
            @endforeach
        </div>

        @if($order->status === 'cancelled')
            <div class="rj-ts-cancel">
                <i class="fa-solid fa-circle-xmark"></i>
                <div>
                    <strong>This order has been cancelled.</strong>
                    <p>If you have questions, please contact our support.</p>
                </div>
            </div>
        @endif

        <div class="rj-ts-grid">

            <div class="rj-ts-card">
                <div class="rj-ts-card-label">// Order Details</div>
                <div class="rj-ts-rows">
                    <div class="rj-ts-row">
                        <span>Order Number</span>
                        <span class="rj-ts-mono">{{ $order->order_number }}</span>
                    </div>
                    <div class="rj-ts-row">
                        <span>Payment Status</span>
                        <span class="rj-ts-status rj-ts-status-{{ $order->payment_status }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    <div class="rj-ts-row">
                        <span>Total</span>
                        <span class="rj-ts-mono">${{ number_format($order->total, 2) }}</span>
                    </div>
                    @if($order->tracking_number)
                        <div class="rj-ts-row">
                            <span>Tracking #</span>
                            <span class="rj-ts-mono">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rj-ts-card">
                <div class="rj-ts-card-label">// Shipping To</div>
                <div class="rj-ts-address">
                    <p class="rj-ts-address-name">{{ $order->customer_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    @if($order->shipping_address2)
                        <p>{{ $order->shipping_address2 }}</p>
                    @endif
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p>{{ $order->shipping_country }}</p>
                </div>
            </div>

        </div>

        <div class="rj-ts-card">
            <div class="rj-ts-card-label">// Items</div>
            <div class="rj-ts-items">
                @foreach($order->items as $item)
                    <div class="rj-ts-item">
                        <div class="rj-ts-item-thumb">
                            @if($item->product_image)
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}">
                            @else
                                <i class="fa-regular fa-image"></i>
                            @endif
                            <span class="rj-ts-item-qty">{{ $item->quantity }}</span>
                        </div>
                        <div class="rj-ts-item-body">
                            <p class="rj-ts-item-name">{{ $item->product_name }}</p>
                            @if($item->attributes && is_array($item->attributes))
                                <p class="rj-ts-item-attrs">
                                    {{ collect($item->attributes)->map(fn($v, $k) => $k . ': ' . $v)->join(' · ') }}
                                </p>
                            @endif
                        </div>
                        <div class="rj-ts-item-price">${{ number_format($item->total_price, 2) }}</div>
                    </div>
                @endforeach
            </div>

            <div class="rj-ts-totals">
                <div class="rj-ts-total-row">
                    <span>Subtotal</span>
                    <span class="rj-ts-mono">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="rj-ts-total-row">
                    <span>Shipping</span>
                    <span class="rj-ts-mono">
                        @if($order->delivery_cost > 0)
                            ${{ number_format($order->delivery_cost, 2) }}
                        @else
                            Free
                        @endif
                    </span>
                </div>
                <div class="rj-ts-total-row rj-ts-total-grand">
                    <span>Total</span>
                    <span class="rj-ts-mono">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if($order->statusLogs->count())
            <div class="rj-ts-card">
                <div class="rj-ts-card-label">// Activity History</div>
                <div class="rj-ts-logs">
                    @foreach($order->statusLogs as $log)
                        <div class="rj-ts-log">
                            <div class="rj-ts-log-dot"></div>
                            <div class="rj-ts-log-body">
                                <p class="rj-ts-log-title">
                                    @php
                                        $toLabels = [
                                            'pending' => 'Order placed',
                                            'confirmed' => 'Order confirmed',
                                            'processing' => 'Processing started',
                                            'shipped' => 'Order shipped',
                                            'delivered' => 'Order delivered',
                                            'cancelled' => 'Order cancelled',
                                            'refunded' => 'Order refunded',
                                        ];
                                    @endphp
                                    {{ $toLabels[$log->to_status] ?? ucfirst($log->to_status) }}
                                </p>
                                @if($log->note)
                                    <p class="rj-ts-log-note">{{ $log->note }}</p>
                                @endif
                                <p class="rj-ts-log-time">{{ $log->created_at->format('M d, Y · H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="rj-ts-actions">
            <a href="{{ route('track.form') }}" class="rj-ts-btn rj-ts-btn-outline">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Track Another</span>
            </a>
            <a href="{{ url('contact-us') }}" class="rj-ts-btn rj-ts-btn-primary">
                <i class="fa-solid fa-headset"></i>
                <span>Contact Support</span>
            </a>
        </div>

    </div>
</section>

<style>
    .rj-ts {
        position: relative;
        background: #05030f;
        color: #fff;
        min-height: calc(100vh - 80px);
        padding: 3rem 0 6rem;
        overflow: hidden;
    }
    .rj-ts-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-ts-orb {
        position: absolute; width: 500px; height: 500px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-ts-orb-a { top: 0; left: 20%; background: rgba(99, 102, 241, 0.07); }
    .rj-ts-orb-b { bottom: 0; right: 20%; background: rgba(236, 72, 153, 0.07); }
    .rj-ts-inner { position: relative; max-width: 56rem; margin: 0 auto; padding: 0 1.5rem; }
    .rj-ts-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 2rem;
    }
    .rj-ts-breadcrumb a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .rj-ts-breadcrumb a:hover { color: #a5b4fc; }
    .rj-ts-breadcrumb .current { color: #9ca3af; }
    .rj-ts-head {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 1rem; flex-wrap: wrap;
        margin-bottom: 2.5rem;
    }
    .rj-ts-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 0.75rem; }
    .rj-ts-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-ts-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }
    .rj-ts-title {
        font-size: clamp(1.5rem, 3.5vw, 2.25rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 0.25rem;
        font-family: ui-monospace, monospace;
    }
    .rj-ts-date { font-size: 12.5px; color: #6b7280; margin: 0; }
    .rj-ts-badge {
        padding: 8px 16px;
        border-radius: 9999px;
        font-family: ui-monospace, monospace;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
    }
    .rj-ts-badge-amber { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .rj-ts-badge-blue { background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); }
    .rj-ts-badge-indigo { background: rgba(99, 102, 241, 0.15); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.3); }
    .rj-ts-badge-purple { background: rgba(168, 85, 247, 0.15); color: #c4b5fd; border: 1px solid rgba(168, 85, 247, 0.3); }
    .rj-ts-badge-emerald { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3); }
    .rj-ts-badge-rose { background: rgba(244, 63, 94, 0.15); color: #fda4af; border: 1px solid rgba(244, 63, 94, 0.3); }
    .rj-ts-badge-gray { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; border: 1px solid rgba(148, 163, 184, 0.3); }

    .rj-ts-timeline {
        display: flex; align-items: flex-start; justify-content: space-between;
        margin-bottom: 2.5rem;
        padding: 1.5rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        overflow-x: auto;
    }
    .rj-ts-step {
        display: flex; flex-direction: column; align-items: center;
        gap: 0.75rem;
        flex: 1; min-width: 80px;
    }
    .rj-ts-step-dot {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        background: rgba(255, 255, 255, 0.03);
        border: 2px solid rgba(255, 255, 255, 0.1);
        color: #4b5563;
        font-size: 12px;
        z-index: 2;
        transition: all 0.3s;
    }
    .rj-ts-step-done .rj-ts-step-dot {
        background: rgba(99, 102, 241, 0.15);
        border-color: #6366f1;
        color: #a5b4fc;
    }
    .rj-ts-step-current .rj-ts-step-dot {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.6);
    }
    .rj-ts-step-label {
        font-family: ui-monospace, monospace;
        font-size: 9px; font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #6b7280;
        text-align: center;
    }
    .rj-ts-step-done .rj-ts-step-label { color: #a5b4fc; }
    .rj-ts-step-current .rj-ts-step-label { color: #fff; font-weight: 700; }

    .rj-ts-cancel {
        display: flex; gap: 0.75rem;
        padding: 1.25rem 1.5rem;
        background: rgba(244, 63, 94, 0.08);
        border: 1px solid rgba(244, 63, 94, 0.25);
        border-radius: 12px;
        margin-bottom: 2rem;
        color: #fda4af;
    }
    .rj-ts-cancel i { font-size: 20px; margin-top: 2px; color: #f43f5e; }
    .rj-ts-cancel strong { color: #fff; display: block; margin-bottom: 0.25rem; font-size: 14px; }
    .rj-ts-cancel p { font-size: 12.5px; margin: 0; color: #fda4af; opacity: 0.8; }

    .rj-ts-grid {
        display: grid; grid-template-columns: 1fr; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 768px) { .rj-ts-grid { grid-template-columns: 1fr 1fr; } }

    .rj-ts-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 14px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .rj-ts-card-label {
        font-family: ui-monospace, monospace;
        font-size: 10px; text-transform: uppercase;
        letter-spacing: 0.22em; color: #818cf8;
        margin-bottom: 1rem;
    }
    .rj-ts-rows { display: flex; flex-direction: column; gap: 0.75rem; }
    .rj-ts-row {
        display: flex; justify-content: space-between;
        align-items: center; gap: 1rem;
        font-size: 13px; color: #9ca3af;
    }
    .rj-ts-mono { font-family: ui-monospace, monospace; color: #fff; font-weight: 600; }
    .rj-ts-status {
        font-family: ui-monospace, monospace;
        font-size: 11px; font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 3px 10px;
        border-radius: 9999px;
    }
    .rj-ts-status-paid { background: rgba(16, 185, 129, 0.15); color: #6ee7b7; }
    .rj-ts-status-unpaid { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .rj-ts-status-refunded { background: rgba(148, 163, 184, 0.15); color: #cbd5e1; }

    .rj-ts-address { font-size: 13px; color: #d1d5db; line-height: 1.7; }
    .rj-ts-address p { margin: 0; }
    .rj-ts-address-name { font-weight: 700; color: #fff; margin-bottom: 0.25rem !important; }

    .rj-ts-items { display: flex; flex-direction: column; gap: 1rem; }
    .rj-ts-item { display: flex; gap: 0.75rem; align-items: flex-start; }
    .rj-ts-item-thumb {
        width: 52px; height: 52px;
        border-radius: 10px;
        background: #0a0715;
        border: 1px solid rgba(255, 255, 255, 0.06);
        overflow: hidden; flex-shrink: 0; position: relative;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255, 255, 255, 0.1);
    }
    .rj-ts-item-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rj-ts-item-qty {
        position: absolute; top: -6px; right: -6px;
        min-width: 18px; height: 18px; padding: 0 5px;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-radius: 9999px;
        font-family: ui-monospace, monospace;
        font-size: 9px; font-weight: 800;
        color: #fff;
        display: flex; align-items: center; justify-content: center;
        border: 2px solid #05030f;
    }
    .rj-ts-item-body { flex: 1; min-width: 0; }
    .rj-ts-item-name {
        font-size: 13px; font-weight: 600; color: #fff;
        margin: 0 0 4px; line-height: 1.3;
    }
    .rj-ts-item-attrs {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #6b7280;
        margin: 0; line-height: 1.4;
    }
    .rj-ts-item-price {
        font-family: ui-monospace, monospace;
        font-size: 13px; font-weight: 700;
        color: #fff; white-space: nowrap;
    }

    .rj-ts-totals {
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    .rj-ts-total-row {
        display: flex; justify-content: space-between;
        padding: 6px 0;
        font-size: 12.5px; color: #9ca3af;
    }
    .rj-ts-total-grand {
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 18px; font-weight: 800;
        color: #fff;
    }

    .rj-ts-logs { display: flex; flex-direction: column; gap: 1rem; }
    .rj-ts-log { display: flex; gap: 0.75rem; }
    .rj-ts-log-dot {
        width: 10px; height: 10px; border-radius: 50%;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        flex-shrink: 0; margin-top: 6px;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }
    .rj-ts-log-body { flex: 1; }
    .rj-ts-log-title { font-size: 13px; font-weight: 600; color: #fff; margin: 0 0 2px; }
    .rj-ts-log-note { font-size: 12px; color: #9ca3af; margin: 0 0 4px; line-height: 1.5; }
    .rj-ts-log-time {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #4b5563;
        margin: 0; letter-spacing: 0.05em;
    }

    .rj-ts-actions {
        display: flex; flex-direction: column; gap: 0.75rem;
        margin-top: 2rem;
    }
    @media (min-width: 640px) {
        .rj-ts-actions { flex-direction: row; justify-content: center; }
    }
    .rj-ts-btn {
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem;
        padding: 0.875rem 1.5rem;
        border-radius: 9999px;
        font-size: 14px; font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
        font-family: inherit;
        flex: 1;
    }
    @media (min-width: 640px) { .rj-ts-btn { flex: 0 1 auto; } }
    .rj-ts-btn i { font-size: 12px; }
    .rj-ts-btn-primary {
        background: #fff; color: #05030f;
    }
    .rj-ts-btn-primary:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.4);
        transform: translateY(-1px);
    }
    .rj-ts-btn-outline {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .rj-ts-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }
</style>

@endsection

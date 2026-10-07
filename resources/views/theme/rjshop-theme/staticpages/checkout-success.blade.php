@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Order Confirmed — ' . $order->order_number)
@section('meta_description', 'Thank you for your order')

@section('content')

<section class="rj-cs">
    <div class="rj-cs-grid-bg"></div>
    <div class="rj-cs-orb rj-cs-orb-a"></div>
    <div class="rj-cs-orb rj-cs-orb-b"></div>

    <div class="rj-cs-inner"
         x-data="receiptUpload({
            orderNumber: '{{ $order->order_number }}',
            maxSize: {{ $receiptMaxSize }},
            requiresReceipt: {{ $requiresReceipt ? 'true' : 'false' }},
            uploadUrl: '{{ route('checkout.receipt', ['orderNumber' => $order->order_number]) }}'
         })">

        <div class="rj-cs-hero">
            <div class="rj-cs-check">
                <i class="fa-solid fa-check"></i>
            </div>
            <div class="rj-cs-eyebrow">
                <span class="rj-cs-eyebrow-line"></span>
                <span class="rj-cs-eyebrow-text">Order Placed</span>
            </div>
            <h1 class="rj-cs-title">Thank you, {{ $order->customer_name }}!</h1>
            <p class="rj-cs-sub">Your order has been received. We'll contact you once payment is verified.</p>
        </div>

        <div class="rj-cs-cards">

            <div class="rj-cs-card rj-cs-card-order">
                <div class="rj-cs-card-label">// Order Number</div>
                <div class="rj-cs-order-num">
                    <span>{{ $order->order_number }}</span>
                    <button type="button"
                            @click="copyText('{{ $order->order_number }}', $event)"
                            class="rj-cs-copy"
                            title="Copy order number">
                        <i class="fa-solid fa-copy"></i>
                    </button>
                </div>
            </div>

            <div class="rj-cs-card">
                <div class="rj-cs-card-label">// Tracking Link</div>
                <div class="rj-cs-track-row">
                    <input type="text"
                           readonly
                           value="{{ route('track.show', ['token' => $order->tracking_token]) }}"
                           x-ref="trackUrl">
                    <button type="button"
                            @click="copyText($refs.trackUrl.value, $event)"
                            class="rj-cs-copy rj-cs-copy-strong"
                            title="Copy tracking link">
                        <i class="fa-solid fa-link"></i>
                    </button>
                </div>
                <p class="rj-cs-card-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Save this link — check status anytime, anywhere.</span>
                </p>
            </div>

        </div>

        @if($requiresReceipt)
        <div class="rj-cs-receipt"
             :class="uploaded ? 'rj-cs-receipt-done' : ''"
             x-show="!uploaded" x-cloak>
            <div class="rj-cs-receipt-head">
                <div class="rj-cs-receipt-icon">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                </div>
                <div>
                    <h2 class="rj-cs-receipt-title">Upload Your Payment Receipt</h2>
                    <p class="rj-cs-receipt-desc">Complete the payment using one of the methods below, then upload your receipt to confirm your order.</p>
                </div>
            </div>

            @if(!empty($methods))
                <div class="rj-cs-methods">
                    @foreach($methods as $method)
                        <div class="rj-cs-method">
                            <div class="rj-cs-method-head">
                                <i class="{{ $method['icon'] }}"></i>
                                <span>{{ $method['label'] }}</span>
                            </div>
                            <div class="rj-cs-method-fields">
                                @foreach($method['fields'] as $label => $value)
                                    <div class="rj-cs-method-row">
                                        <span class="rj-cs-method-label">{{ $label }}</span>
                                        <span class="rj-cs-method-value">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($instructions)
                <div class="rj-cs-instructions">
                    {!! nl2br(e($instructions)) !!}
                </div>
            @endif

            <div class="rj-cs-upload-zone"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="onDrop($event)"
                 :class="dragging ? 'rj-cs-upload-drag' : ''">

                <input type="file"
                       x-ref="fileInput"
                       accept="image/*,.pdf,.jpg,.jpeg,.png,.webp"
                       @change="onSelect($event)"
                       class="hidden">

                <template x-if="!file">
                    <div class="rj-cs-upload-empty">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        <p class="rj-cs-upload-title">Drop your receipt here</p>
                        <p class="rj-cs-upload-sub">or</p>
                        <button type="button"
                                @click="$refs.fileInput.click()"
                                class="rj-cs-upload-btn">
                            <i class="fa-solid fa-folder-open"></i>
                            <span>Choose File</span>
                        </button>
                        <p class="rj-cs-upload-hint">
                            Accepted: JPG, PNG, WEBP, PDF · Max {{ $receiptMaxSize }}MB
                        </p>
                    </div>
                </template>

                <template x-if="file">
                    <div class="rj-cs-upload-selected">
                        <div class="rj-cs-upload-preview">
                            <template x-if="filePreview">
                                <img :src="filePreview" alt="Preview">
                            </template>
                            <template x-if="!filePreview">
                                <i class="fa-solid fa-file-pdf"></i>
                            </template>
                        </div>
                        <div class="rj-cs-upload-meta">
                            <p class="rj-cs-upload-name" x-text="file.name"></p>
                            <p class="rj-cs-upload-size" x-text="fileSizeLabel"></p>
                        </div>
                        <button type="button"
                                @click="clearFile()"
                                class="rj-cs-upload-remove"
                                title="Remove">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </template>

            </div>

            <template x-if="error">
                <div class="rj-cs-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span x-text="error"></span>
                </div>
            </template>

            <button type="button"
                    @click="submit()"
                    :disabled="!file || submitting"
                    class="rj-cs-submit">
                <i class="fa-solid" :class="submitting ? 'fa-spinner fa-spin' : 'fa-upload'"></i>
                <span x-text="submitting ? 'Uploading...' : 'Upload Receipt'"></span>
            </button>

            <p class="rj-cs-window">
                <i class="fa-regular fa-clock"></i>
                <span>Please complete payment within <strong>{{ $paymentWindowHours }} hours</strong>.</span>
            </p>
        </div>

        <div class="rj-cs-receipt rj-cs-receipt-done" x-show="uploaded" x-cloak>
            <div class="rj-cs-success-icon">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <h2 class="rj-cs-success-title">Receipt Received!</h2>
            <p class="rj-cs-success-text">
                Your payment receipt has been uploaded successfully. Our team will verify it and confirm your order shortly.
            </p>
        </div>
        @else
        <div class="rj-cs-receipt">
            @if(!empty($methods))
                <div class="rj-cs-receipt-head">
                    <div class="rj-cs-receipt-icon">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div>
                        <h2 class="rj-cs-receipt-title">Payment Details</h2>
                        <p class="rj-cs-receipt-desc">Please complete the payment using one of the methods below.</p>
                    </div>
                </div>

                <div class="rj-cs-methods">
                    @foreach($methods as $method)
                        <div class="rj-cs-method">
                            <div class="rj-cs-method-head">
                                <i class="{{ $method['icon'] }}"></i>
                                <span>{{ $method['label'] }}</span>
                            </div>
                            <div class="rj-cs-method-fields">
                                @foreach($method['fields'] as $label => $value)
                                    <div class="rj-cs-method-row">
                                        <span class="rj-cs-method-label">{{ $label }}</span>
                                        <span class="rj-cs-method-value">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if($instructions)
                <div class="rj-cs-instructions">
                    {!! nl2br(e($instructions)) !!}
                </div>
            @endif
        </div>
        @endif

        <div class="rj-cs-summary">
            <div class="rj-cs-summary-head">
                <i class="fa-solid fa-receipt"></i>
                <span>Order Summary</span>
            </div>

            <div class="rj-cs-summary-items">
                @foreach($order->items as $item)
                    <div class="rj-cs-summary-item">
                        <div class="rj-cs-summary-thumb">
                            @if($item->product_image)
                                <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}">
                            @else
                                <i class="fa-regular fa-image"></i>
                            @endif
                            <span class="rj-cs-summary-qty">{{ $item->quantity }}</span>
                        </div>
                        <div class="rj-cs-summary-body">
                            <p class="rj-cs-summary-name">{{ $item->product_name }}</p>
                            @if($item->attributes && is_array($item->attributes))
                                <p class="rj-cs-summary-attrs">
                                    {{ collect($item->attributes)->map(fn($v, $k) => $k . ': ' . $v)->join(' · ') }}
                                </p>
                            @endif
                        </div>
                        <div class="rj-cs-summary-price">
                            ${{ number_format($item->total_price, 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="rj-cs-summary-totals">
                <div class="rj-cs-summary-row">
                    <span>Subtotal</span>
                    <span class="rj-cs-mono">${{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="rj-cs-summary-row">
                    <span>Shipping</span>
                    <span class="rj-cs-mono">
                        @if($order->delivery_cost > 0)
                            ${{ number_format($order->delivery_cost, 2) }}
                        @else
                            Free
                        @endif
                    </span>
                </div>
                <div class="rj-cs-summary-row rj-cs-summary-row-grand">
                    <span>Total</span>
                    <span class="rj-cs-mono">${{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="rj-cs-actions">
            <a href="{{ url('/') }}" class="rj-cs-btn rj-cs-btn-outline">
                <i class="fa-solid fa-house"></i>
                <span>Back to Home</span>
            </a>
            <a href="{{ route('track.show', ['token' => $order->tracking_token]) }}"
               class="rj-cs-btn rj-cs-btn-primary">
                <i class="fa-solid fa-location-dot"></i>
                <span>Track Order</span>
            </a>
        </div>

    </div>
</section>

<style>
    .rj-cs {
        position: relative;
        background: #05030f;
        color: #fff;
        min-height: calc(100vh - 80px);
        padding: 3rem 0 6rem;
        overflow: hidden;
    }
    .rj-cs-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-cs-orb {
        position: absolute; width: 500px; height: 500px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-cs-orb-a { top: 0; left: 20%; background: rgba(52, 211, 153, 0.08); }
    .rj-cs-orb-b { bottom: 0; right: 20%; background: rgba(99, 102, 241, 0.07); }

    .rj-cs-inner {
        position: relative; max-width: 56rem; margin: 0 auto; padding: 0 1.5rem;
    }

    .rj-cs-hero { text-align: center; margin-bottom: 3rem; }

    .rj-cs-check {
        width: 72px; height: 72px; margin: 0 auto 1.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; color: #fff;
        box-shadow: 0 12px 40px -8px rgba(16, 185, 129, 0.6);
        position: relative;
    }
    .rj-cs-check::after {
        content: ''; position: absolute; inset: -8px;
        border-radius: 50%;
        border: 2px solid rgba(16, 185, 129, 0.2);
        animation: rj-cs-pulse 2s ease-in-out infinite;
    }
    @keyframes rj-cs-pulse {
        0%, 100% { opacity: 0.4; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.08); }
    }

    .rj-cs-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .rj-cs-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(52, 211, 153, 0.6); }
    .rj-cs-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(52, 211, 153, 0.9);
    }
    .rj-cs-title {
        font-size: clamp(1.5rem, 3.5vw, 2.25rem);
        font-weight: 900; line-height: 1.15; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 0.75rem;
    }
    .rj-cs-sub { font-size: 0.9375rem; color: #6b7280; line-height: 1.7; margin: 0; }

    .rj-cs-cards {
        display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 2rem;
    }
    @media (min-width: 768px) { .rj-cs-cards { grid-template-columns: 1fr 1fr; } }

    .rj-cs-card {
        padding: 1.25rem 1.5rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 14px;
    }
    .rj-cs-card-order {
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.08), rgba(168, 85, 247, 0.06));
        border-color: rgba(99, 102, 241, 0.25);
    }
    .rj-cs-card-label {
        font-family: ui-monospace, monospace;
        font-size: 10px; text-transform: uppercase;
        letter-spacing: 0.22em; color: #818cf8;
        margin-bottom: 0.75rem;
    }
    .rj-cs-card-hint {
        display: flex; align-items: center; gap: 0.5rem;
        margin: 0.75rem 0 0;
        font-size: 11px; color: #6b7280;
    }
    .rj-cs-card-hint i { color: #818cf8; font-size: 10px; }

    .rj-cs-order-num {
        display: flex; align-items: center; justify-content: space-between;
        gap: 0.75rem;
    }
    .rj-cs-order-num > span {
        font-family: ui-monospace, monospace;
        font-size: 1.25rem; font-weight: 800;
        color: #fff; letter-spacing: 0.05em;
        word-break: break-all;
    }

    .rj-cs-track-row {
        display: flex; gap: 0.5rem;
    }
    .rj-cs-track-row input {
        flex: 1; min-width: 0;
        padding: 0.625rem 0.875rem;
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        color: #9ca3af;
        font-family: ui-monospace, monospace;
        font-size: 11px;
        outline: none;
    }
    .rj-cs-copy {
        width: 38px; height: 38px; flex-shrink: 0;
        background: rgba(99, 102, 241, 0.12);
        border: 1px solid rgba(99, 102, 241, 0.3);
        border-radius: 8px;
        color: #a5b4fc; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        font-size: 13px;
    }
    .rj-cs-copy:hover {
        background: rgba(99, 102, 241, 0.2);
        color: #fff;
        transform: scale(1.05);
    }
    .rj-cs-copy-strong {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-color: transparent;
        color: #fff;
    }
    .rj-cs-copy-strong:hover {
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.6);
        background: linear-gradient(135deg, #7c7ff5, #b966f9);
    }

    .rj-cs-receipt {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .rj-cs-receipt-done {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.06), rgba(52, 211, 153, 0.03));
        border-color: rgba(16, 185, 129, 0.25);
        text-align: center;
    }
    .rj-cs-receipt-head {
        display: flex; gap: 1rem; margin-bottom: 1.5rem;
    }
    .rj-cs-receipt-icon {
        width: 44px; height: 44px; flex-shrink: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(236, 72, 153, 0.15));
        border: 1px solid rgba(99, 102, 241, 0.3);
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 16px;
    }
    .rj-cs-receipt-title {
        font-size: 1.125rem; font-weight: 800; color: #fff;
        margin: 0 0 0.25rem; letter-spacing: -0.01em;
    }
    .rj-cs-receipt-desc {
        font-size: 12.5px; color: #6b7280;
        margin: 0; line-height: 1.6;
    }

    .rj-cs-methods {
        display: grid; grid-template-columns: 1fr; gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) { .rj-cs-methods { grid-template-columns: repeat(2, 1fr); } }

    .rj-cs-method {
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 12px;
    }
    .rj-cs-method-head {
        display: flex; align-items: center; gap: 0.5rem;
        margin-bottom: 0.75rem;
        font-family: ui-monospace, monospace;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.2em;
        color: #a5b4fc;
    }
    .rj-cs-method-head i { font-size: 12px; color: #818cf8; }
    .rj-cs-method-fields {
        display: flex; flex-direction: column; gap: 0.5rem;
    }
    .rj-cs-method-row {
        display: flex; flex-direction: column; gap: 2px;
    }
    .rj-cs-method-label {
        font-family: ui-monospace, monospace;
        font-size: 9px; text-transform: uppercase;
        letter-spacing: 0.15em; color: #6b7280;
    }
    .rj-cs-method-value {
        font-family: ui-monospace, monospace;
        font-size: 13px; color: #fff;
        word-break: break-all;
        font-weight: 600;
    }

    .rj-cs-instructions {
        padding: 1rem 1.25rem;
        background: rgba(99, 102, 241, 0.05);
        border-left: 3px solid #6366f1;
        border-radius: 8px;
        font-size: 13px; line-height: 1.7;
        color: #d1d5db;
        margin-bottom: 1.5rem;
    }

    .rj-cs-upload-zone {
        position: relative;
        border: 2px dashed rgba(99, 102, 241, 0.3);
        border-radius: 14px;
        padding: 2rem 1.5rem;
        background: rgba(99, 102, 241, 0.03);
        transition: all 0.25s;
        margin-bottom: 1.5rem;
    }
    .rj-cs-upload-zone:hover {
        border-color: rgba(99, 102, 241, 0.5);
        background: rgba(99, 102, 241, 0.05);
    }
    .rj-cs-upload-drag {
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, 0.12) !important;
        box-shadow: 0 0 40px -8px rgba(99, 102, 241, 0.5);
    }
    .rj-cs-upload-empty {
        display: flex; flex-direction: column;
        align-items: center; text-align: center;
        gap: 0.5rem;
    }
    .rj-cs-upload-empty > i {
        font-size: 32px; color: #818cf8;
        margin-bottom: 0.5rem;
        opacity: 0.7;
    }
    .rj-cs-upload-title {
        font-size: 15px; font-weight: 700;
        color: #fff; margin: 0;
    }
    .rj-cs-upload-sub {
        font-size: 11px; color: #6b7280;
        margin: 0.25rem 0;
        font-family: ui-monospace, monospace;
        letter-spacing: 0.1em;
    }
    .rj-cs-upload-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border: none; border-radius: 8px;
        color: #fff; font-size: 13px; font-weight: 600;
        cursor: pointer; font-family: inherit;
        transition: all 0.2s;
        margin: 0.5rem 0;
    }
    .rj-cs-upload-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 24px -6px rgba(99, 102, 241, 0.5);
    }
    .rj-cs-upload-btn i { font-size: 11px; }
    .rj-cs-upload-hint {
        font-size: 10px; color: #4b5563;
        margin: 0.5rem 0 0;
        font-family: ui-monospace, monospace;
        letter-spacing: 0.08em;
    }

    .rj-cs-upload-selected {
        display: flex; align-items: center; gap: 1rem;
    }
    .rj-cs-upload-preview {
        width: 60px; height: 60px; flex-shrink: 0;
        border-radius: 10px;
        background: #0a0715;
        border: 1px solid rgba(255, 255, 255, 0.08);
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        color: #ef4444; font-size: 22px;
    }
    .rj-cs-upload-preview img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .rj-cs-upload-meta { flex: 1; min-width: 0; }
    .rj-cs-upload-name {
        font-size: 13px; font-weight: 600;
        color: #fff; margin: 0 0 2px;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }
    .rj-cs-upload-size {
        font-family: ui-monospace, monospace;
        font-size: 11px; color: #6b7280;
        margin: 0;
    }
    .rj-cs-upload-remove {
        width: 32px; height: 32px;
        background: rgba(244, 63, 94, 0.1);
        border: 1px solid rgba(244, 63, 94, 0.25);
        border-radius: 8px;
        color: #fda4af; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 12px;
        transition: all 0.2s;
        flex-shrink: 0;
    }
    .rj-cs-upload-remove:hover {
        background: rgba(244, 63, 94, 0.2);
        color: #fff;
    }

    .rj-cs-error {
        display: flex; gap: 0.5rem; align-items: flex-start;
        padding: 0.75rem 1rem;
        background: rgba(244, 63, 94, 0.08);
        border: 1px solid rgba(244, 63, 94, 0.25);
        border-radius: 10px;
        font-size: 12px; color: #fda4af;
        margin-bottom: 1rem;
    }
    .rj-cs-error i { margin-top: 2px; color: #f43f5e; }

    .rj-cs-submit {
        width: 100%;
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        border: none; border-radius: 9999px;
        color: #fff; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit;
        transition: all 0.3s;
        box-shadow: 0 12px 32px -12px rgba(99, 102, 241, 0.6);
    }
    .rj-cs-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -12px rgba(168, 85, 247, 0.7);
    }
    .rj-cs-submit:disabled {
        opacity: 0.5; cursor: not-allowed; transform: none;
    }
    .rj-cs-submit i { font-size: 12px; }

    .rj-cs-window {
        display: flex; align-items: center; justify-content: center;
        gap: 0.5rem;
        margin: 1rem 0 0;
        font-size: 11.5px; color: #9ca3af;
        font-family: ui-monospace, monospace;
        letter-spacing: 0.05em;
    }
    .rj-cs-window i { color: #f59e0b; font-size: 11px; }
    .rj-cs-window strong { color: #fbbf24; font-weight: 700; }

    .rj-cs-success-icon {
        width: 64px; height: 64px; margin: 0 auto 1.25rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981, #059669);
        display: flex; align-items: center; justify-content: center;
        font-size: 26px; color: #fff;
        box-shadow: 0 12px 32px -8px rgba(16, 185, 129, 0.6);
    }
    .rj-cs-success-title {
        font-size: 1.375rem; font-weight: 800; color: #fff;
        margin: 0 0 0.5rem;
    }
    .rj-cs-success-text {
        font-size: 13.5px; color: #9ca3af;
        line-height: 1.7; margin: 0;
        max-width: 32rem; margin-left: auto; margin-right: auto;
    }

    .rj-cs-summary {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 2rem;
    }
    .rj-cs-summary-head {
        display: flex; align-items: center; gap: 0.625rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 1.25rem;
        font-family: ui-monospace, monospace;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.25em;
        color: #818cf8;
    }
    .rj-cs-summary-head i { font-size: 13px; }
    .rj-cs-summary-items {
        display: flex; flex-direction: column; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .rj-cs-summary-item {
        display: flex; gap: 0.75rem; align-items: flex-start;
    }
    .rj-cs-summary-thumb {
        width: 52px; height: 52px; border-radius: 10px;
        background: #0a0715; border: 1px solid rgba(255, 255, 255, 0.06);
        overflow: hidden; flex-shrink: 0; position: relative;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255, 255, 255, 0.1);
    }
    .rj-cs-summary-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rj-cs-summary-qty {
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
    .rj-cs-summary-body { flex: 1; min-width: 0; }
    .rj-cs-summary-name {
        font-size: 13px; font-weight: 600; color: #fff;
        margin: 0 0 4px; line-height: 1.3;
    }
    .rj-cs-summary-attrs {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #6b7280;
        margin: 0; line-height: 1.4;
    }
    .rj-cs-summary-price {
        font-family: ui-monospace, monospace;
        font-size: 13px; font-weight: 700;
        color: #fff; white-space: nowrap;
    }
    .rj-cs-summary-totals {
        padding-top: 1.25rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }
    .rj-cs-summary-row {
        display: flex; justify-content: space-between;
        padding: 6px 0;
        font-size: 12.5px; color: #9ca3af;
    }
    .rj-cs-summary-row-grand {
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 18px; font-weight: 800;
        color: #fff;
    }
    .rj-cs-mono { font-family: ui-monospace, monospace; }

    .rj-cs-actions {
        display: flex; flex-direction: column; gap: 0.75rem;
    }
    @media (min-width: 640px) {
        .rj-cs-actions { flex-direction: row; justify-content: center; }
    }
    .rj-cs-btn {
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
    @media (min-width: 640px) { .rj-cs-btn { flex: 0 1 auto; } }
    .rj-cs-btn i { font-size: 12px; }
    .rj-cs-btn-primary {
        background: #fff; color: #05030f;
    }
    .rj-cs-btn-primary:hover {
        background: #eef2ff;
        box-shadow: 0 0 40px rgba(192, 132, 252, 0.4);
        transform: translateY(-1px);
    }
    .rj-cs-btn-outline {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #fff;
    }
    .rj-cs-btn-outline:hover {
        background: rgba(99, 102, 241, 0.1);
        border-color: rgba(99, 102, 241, 0.4);
    }

    [x-cloak] { display: none !important; }
</style>

<script>
function receiptUpload(config) {
    return {
        file: null,
        filePreview: null,
        dragging: false,
        submitting: false,
        uploaded: false,
        error: '',
        maxSize: config.maxSize || 5,
        uploadUrl: config.uploadUrl,

        init() {},

        get fileSizeLabel() {
            if (!this.file) return '';
            const bytes = this.file.size;
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
        },

        onSelect(e) {
            const f = e.target.files && e.target.files[0];
            if (!f) return;
            this.setFile(f);
        },

        onDrop(e) {
            this.dragging = false;
            const f = e.dataTransfer.files && e.dataTransfer.files[0];
            if (!f) return;
            this.setFile(f);
        },

        setFile(f) {
            this.error = '';

            if (f.size > this.maxSize * 1024 * 1024) {
                this.error = 'File is larger than ' + this.maxSize + 'MB.';
                return;
            }

            const allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'application/pdf'];
            if (!allowed.includes(f.type)) {
                this.error = 'Only images (JPG, PNG, WEBP) and PDF files are allowed.';
                return;
            }

            this.file = f;

            if (f.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = (ev) => { this.filePreview = ev.target.result; };
                reader.readAsDataURL(f);
            } else {
                this.filePreview = null;
            }
        },

        clearFile() {
            this.file = null;
            this.filePreview = null;
            this.error = '';
            if (this.$refs.fileInput) this.$refs.fileInput.value = '';
        },

        async submit() {
            if (!this.file || this.submitting) return;

            this.submitting = true;
            this.error = '';

            try {
                const formData = new FormData();
                formData.append('receipt', this.file);

                const res = await fetch(this.uploadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    this.uploaded = true;
                    return;
                }

                this.error = data.message || 'Upload failed. Please try again.';
            } catch (e) {
                this.error = 'Network error. Please try again.';
            }

            this.submitting = false;
        },

        copyText(text, event) {
            if (!text) return;
            navigator.clipboard.writeText(text).then(() => {
                const btn = event && event.currentTarget ? event.currentTarget : null;
                if (!btn) return;
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                setTimeout(() => { btn.innerHTML = original; }, 1200);
            });
        }
    };
}
</script>

@endsection

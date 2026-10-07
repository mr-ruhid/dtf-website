@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Checkout')
@section('meta_description', 'Complete your order')

@section('content')

<section class="rj-ck">
    <div class="rj-ck-grid-bg"></div>
    <div class="rj-ck-orb rj-ck-orb-a"></div>
    <div class="rj-ck-orb rj-ck-orb-b"></div>

    <div class="rj-ck-inner"
         x-data="checkoutForm({
            zones: {{ \Illuminate\Support\Js::from($zones) }},
            subtotal: {{ (float) $subtotal }}
         })">

        <nav class="rj-ck-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <span class="current">Checkout</span>
        </nav>

        <div class="rj-ck-head">
            <div class="rj-ck-eyebrow">
                <span class="rj-ck-eyebrow-line"></span>
                <span class="rj-ck-eyebrow-text">Secure Checkout</span>
            </div>
            <h1 class="rj-ck-title">Complete your order</h1>
            <p class="rj-ck-sub">Fill in your details and choose how you want to pay.</p>
        </div>

        <form @submit.prevent="submit()" class="rj-ck-layout">

            <div class="rj-ck-main">

                <div class="rj-ck-card">
                    <div class="rj-ck-card-head">
                        <div class="rj-ck-card-num">01</div>
                        <div>
                            <h2 class="rj-ck-card-title">Contact Information</h2>
                            <p class="rj-ck-card-desc">We'll use this to reach you about your order.</p>
                        </div>
                    </div>

                    <div class="rj-ck-fields">
                        <div class="rj-ck-field">
                            <label>Full Name <span class="rj-ck-req">*</span></label>
                            <input type="text" x-model="form.customer.name" required placeholder="John Doe">
                        </div>

                        <div class="rj-ck-field rj-ck-field-half">
                            <label>Email <span class="rj-ck-req">*</span></label>
                            <input type="email" x-model="form.customer.email" required placeholder="you@example.com">
                        </div>

                        <div class="rj-ck-field rj-ck-field-half">
                            <label>Phone <span class="rj-ck-req">*</span></label>
                            <input type="tel" x-model="form.customer.phone" required placeholder="+1 555 123 4567">
                        </div>

                        <div class="rj-ck-field">
                            <label>Company <span class="rj-ck-opt">(optional)</span></label>
                            <input type="text" x-model="form.customer.company" placeholder="Company name">
                        </div>
                    </div>
                </div>

                <div class="rj-ck-card">
                    <div class="rj-ck-card-head">
                        <div class="rj-ck-card-num">02</div>
                        <div>
                            <h2 class="rj-ck-card-title">Shipping Address</h2>
                            <p class="rj-ck-card-desc">Where should we deliver your order?</p>
                        </div>
                    </div>

                    <div class="rj-ck-fields">
                        <div class="rj-ck-field">
                            <label>Address <span class="rj-ck-req">*</span></label>
                            <input type="text" x-model="form.shipping.address" required placeholder="Street address">
                        </div>

                        <div class="rj-ck-field">
                            <label>Address Line 2 <span class="rj-ck-opt">(optional)</span></label>
                            <input type="text" x-model="form.shipping.address2" placeholder="Apartment, suite, etc.">
                        </div>

                        <div class="rj-ck-field rj-ck-field-half">
                            <label>City <span class="rj-ck-req">*</span></label>
                            <input type="text" x-model="form.shipping.city" required placeholder="City">
                        </div>

                        <div class="rj-ck-field rj-ck-field-quarter">
                            <label>State <span class="rj-ck-req">*</span></label>
                            <input type="text" x-model="form.shipping.state" required placeholder="CA" maxlength="10">
                        </div>

                        <div class="rj-ck-field rj-ck-field-quarter">
                            <label>ZIP <span class="rj-ck-req">*</span></label>
                            <input type="text" x-model="form.shipping.zip" required placeholder="90001" maxlength="20">
                        </div>

                        <div class="rj-ck-field rj-ck-field-half">
                            <label>Country</label>
                            <input type="text" x-model="form.shipping.country" placeholder="US" maxlength="5">
                        </div>
                    </div>

                    <div class="rj-ck-ship-note">
                        <i class="fa-solid fa-truck-fast"></i>
                        <span>Shipping cost is calculated automatically based on your location.</span>
                    </div>
                </div>

                <div class="rj-ck-card">
                    <div class="rj-ck-card-head">
                        <div class="rj-ck-card-num">03</div>
                        <div>
                            <h2 class="rj-ck-card-title">Payment Method</h2>
                            <p class="rj-ck-card-desc">How would you like to pay?</p>
                        </div>
                    </div>

                    <div class="rj-ck-payments">
                        @foreach($gateways as $id => $gateway)
                            <label class="rj-ck-payment"
                                   :class="form.gateway_id === '{{ $id }}' ? 'rj-ck-payment-active' : ''">
                                <input type="radio"
                                       name="gateway_id"
                                       value="{{ $id }}"
                                       x-model="form.gateway_id"
                                       class="hidden">
                                <div class="rj-ck-payment-icon">
                                    <i class="{{ $gateway->getIcon() }}"></i>
                                </div>
                                <div class="rj-ck-payment-body">
                                    <div class="rj-ck-payment-name">{{ $gateway->getName() }}</div>
                                    <div class="rj-ck-payment-desc">{{ $gateway->getDescription() }}</div>
                                </div>
                                <div class="rj-ck-payment-check">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="rj-ck-card">
                    <div class="rj-ck-card-head">
                        <div class="rj-ck-card-num">04</div>
                        <div>
                            <h2 class="rj-ck-card-title">Order Note</h2>
                            <p class="rj-ck-card-desc">Any special instructions? (optional)</p>
                        </div>
                    </div>

                    <div class="rj-ck-field">
                        <textarea x-model="form.note" rows="3"
                                  placeholder="Delivery notes, special requests..."></textarea>
                    </div>
                </div>

            </div>

            <aside class="rj-ck-side">
                <div class="rj-ck-summary">

                    <div class="rj-ck-summary-head">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span>Order Summary</span>
                    </div>

                    <div class="rj-ck-summary-items">
                        @foreach($items as $item)
                            <div class="rj-ck-summary-item">
                                <div class="rj-ck-summary-thumb">
                                    @if(!empty($item['image']))
                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                    @else
                                        <i class="fa-regular fa-image"></i>
                                    @endif
                                    <span class="rj-ck-summary-qty">{{ $item['qty'] }}</span>
                                </div>
                                <div class="rj-ck-summary-body">
                                    <p class="rj-ck-summary-name">{{ $item['name'] }}</p>
                                    @if(!empty($item['attributes']))
                                        <p class="rj-ck-summary-attrs">
                                            {{ collect($item['attributes'])->map(fn($v, $k) => $k . ': ' . $v)->join(' · ') }}
                                        </p>
                                    @endif
                                </div>
                                <div class="rj-ck-summary-price">
                                    ${{ number_format($item['total'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rj-ck-summary-totals">
                        <div class="rj-ck-summary-row">
                            <span>Subtotal</span>
                            <span class="rj-ck-mono">${{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="rj-ck-summary-row">
                            <span>Shipping</span>
                            <span class="rj-ck-mono" x-text="shippingLabel"></span>
                        </div>
                        <div class="rj-ck-summary-row rj-ck-summary-row-grand">
                            <span>Total</span>
                            <span class="rj-ck-mono">$<span x-text="totalFormatted"></span></span>
                        </div>
                    </div>

                    <button type="submit"
                            class="rj-ck-submit"
                            :disabled="submitting">
                        <i class="fa-solid" :class="submitting ? 'fa-spinner fa-spin' : 'fa-lock'"></i>
                        <span x-text="submitting ? 'Placing order...' : 'Place Order'"></span>
                    </button>

                    <p class="rj-ck-secure">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span>Your information is secure and encrypted.</span>
                    </p>

                    <template x-if="error">
                        <div class="rj-ck-error">
                            <i class="fa-solid fa-circle-exclamation"></i>
                            <span x-text="error"></span>
                        </div>
                    </template>

                </div>
            </aside>

        </form>

    </div>
</section>

<style>
    .rj-ck {
        position: relative;
        background: #05030f;
        color: #fff;
        min-height: calc(100vh - 80px);
        padding: 3rem 0 6rem;
        overflow: hidden;
    }
    .rj-ck-grid-bg {
        position: absolute; inset: 0; opacity: 0.025; pointer-events: none;
        background-image:
            linear-gradient(rgba(99, 102, 241, 0.5) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99, 102, 241, 0.5) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .rj-ck-orb {
        position: absolute; width: 500px; height: 500px; border-radius: 50%;
        filter: blur(120px); pointer-events: none;
    }
    .rj-ck-orb-a { top: 0; left: 20%; background: rgba(99, 102, 241, 0.07); }
    .rj-ck-orb-b { bottom: 0; right: 20%; background: rgba(236, 72, 153, 0.07); }

    .rj-ck-inner {
        position: relative; max-width: 80rem; margin: 0 auto; padding: 0 1.5rem;
    }
    @media (min-width: 1024px) { .rj-ck-inner { padding: 0 3rem; } }

    .rj-ck-breadcrumb {
        display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;
        font-family: ui-monospace, monospace; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.15em;
        color: #6b7280; margin-bottom: 2rem;
    }
    .rj-ck-breadcrumb a { color: #6b7280; text-decoration: none; transition: color 0.2s; }
    .rj-ck-breadcrumb a:hover { color: #a5b4fc; }
    .rj-ck-breadcrumb .current { color: #9ca3af; }

    .rj-ck-head { margin-bottom: 3rem; }
    .rj-ck-eyebrow { display: inline-flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
    .rj-ck-eyebrow-line { width: 1.25rem; height: 1px; background: rgba(244, 114, 182, 0.6); }
    .rj-ck-eyebrow-text {
        font-family: ui-monospace, monospace; font-size: 9px;
        text-transform: uppercase; letter-spacing: 0.35em;
        color: rgba(244, 114, 182, 0.9);
    }
    .rj-ck-title {
        font-size: clamp(1.75rem, 4vw, 2.75rem);
        font-weight: 900; line-height: 1.1; letter-spacing: -0.02em;
        color: #fff; margin: 0 0 0.75rem;
    }
    .rj-ck-sub { font-size: 0.9375rem; color: #6b7280; line-height: 1.7; margin: 0; }

    .rj-ck-layout {
        display: grid; grid-template-columns: 1fr; gap: 2rem;
        align-items: start;
    }
    @media (min-width: 1024px) {
        .rj-ck-layout { grid-template-columns: 1fr 380px; gap: 3rem; }
    }

    .rj-ck-main { display: flex; flex-direction: column; gap: 1.25rem; }

    .rj-ck-card {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        padding: 1.75rem;
    }
    @media (min-width: 768px) { .rj-ck-card { padding: 2rem; } }

    .rj-ck-card-head {
        display: flex; gap: 1rem; margin-bottom: 1.75rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }
    .rj-ck-card-num {
        font-family: ui-monospace, monospace;
        font-size: 11px; font-weight: 700;
        color: #a5b4fc; letter-spacing: 0.15em;
        padding: 6px 12px;
        background: rgba(99, 102, 241, 0.1);
        border: 1px solid rgba(99, 102, 241, 0.25);
        border-radius: 8px;
        flex-shrink: 0;
        height: fit-content;
    }
    .rj-ck-card-title {
        font-size: 1.125rem; font-weight: 800; color: #fff;
        margin: 0 0 0.25rem; letter-spacing: -0.01em;
    }
    .rj-ck-card-desc {
        font-size: 12.5px; color: #6b7280; margin: 0;
    }

    .rj-ck-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    @media (max-width: 640px) { .rj-ck-fields { grid-template-columns: 1fr; } }

    .rj-ck-field { display: flex; flex-direction: column; gap: 0.5rem; grid-column: 1 / -1; }
    .rj-ck-field-half { grid-column: span 1; }
    .rj-ck-field-quarter { grid-column: span 1; }
    @media (min-width: 640px) {
        .rj-ck-field-quarter { grid-column: span 1; }
    }

    .rj-ck-field label {
        font-family: ui-monospace, monospace;
        font-size: 10px; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.18em;
        color: #9ca3af;
    }
    .rj-ck-req { color: #f472b6; margin-left: 0.15rem; }
    .rj-ck-opt { color: #4b5563; font-weight: 400; letter-spacing: 0.05em; }

    .rj-ck-field input,
    .rj-ck-field textarea {
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
        resize: vertical;
    }
    .rj-ck-field input::placeholder,
    .rj-ck-field textarea::placeholder { color: #4b5563; }
    .rj-ck-field input:focus,
    .rj-ck-field textarea:focus {
        border-color: rgba(99, 102, 241, 0.6);
        background: rgba(99, 102, 241, 0.05);
        box-shadow: 0 0 20px rgba(99, 102, 241, 0.15);
    }

    .rj-ck-ship-note {
        display: flex; align-items: center; gap: 0.5rem;
        margin-top: 1.25rem; padding: 0.75rem 1rem;
        background: rgba(99, 102, 241, 0.06);
        border: 1px dashed rgba(99, 102, 241, 0.25);
        border-radius: 10px;
        font-size: 12px; color: #a5b4fc;
    }
    .rj-ck-ship-note i { font-size: 11px; color: #818cf8; }

    .rj-ck-payments { display: flex; flex-direction: column; gap: 0.75rem; }

    .rj-ck-payment {
        display: flex; align-items: center; gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .rj-ck-payment:hover {
        background: rgba(255, 255, 255, 0.03);
        border-color: rgba(99, 102, 241, 0.3);
    }
    .rj-ck-payment-active {
        background: rgba(99, 102, 241, 0.08) !important;
        border-color: rgba(99, 102, 241, 0.6) !important;
        box-shadow: 0 0 24px -8px rgba(99, 102, 241, 0.4);
    }
    .rj-ck-payment-icon {
        width: 42px; height: 42px; border-radius: 10px;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(236, 72, 153, 0.15));
        border: 1px solid rgba(99, 102, 241, 0.25);
        display: flex; align-items: center; justify-content: center;
        color: #a5b4fc; font-size: 16px; flex-shrink: 0;
    }
    .rj-ck-payment-body { flex: 1; min-width: 0; }
    .rj-ck-payment-name {
        font-size: 14px; font-weight: 700; color: #fff;
        margin-bottom: 2px;
    }
    .rj-ck-payment-desc {
        font-size: 11.5px; color: #6b7280;
        line-height: 1.4;
    }
    .rj-ck-payment-check {
        color: #6366f1; font-size: 18px;
        opacity: 0; transition: opacity 0.2s;
        flex-shrink: 0;
    }
    .rj-ck-payment-active .rj-ck-payment-check { opacity: 1; }

    .rj-ck-side { position: relative; }
    @media (min-width: 1024px) {
        .rj-ck-side { position: sticky; top: 100px; }
    }

    .rj-ck-summary {
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 16px;
        padding: 1.75rem;
    }

    .rj-ck-summary-head {
        display: flex; align-items: center; gap: 0.625rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 1.25rem;
        font-family: ui-monospace, monospace;
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.25em;
        color: #818cf8;
    }
    .rj-ck-summary-head i { font-size: 13px; }

    .rj-ck-summary-items {
        display: flex; flex-direction: column; gap: 1rem;
        max-height: 340px; overflow-y: auto;
        margin-bottom: 1.5rem; padding-right: 0.25rem;
    }
    .rj-ck-summary-items::-webkit-scrollbar { width: 4px; }
    .rj-ck-summary-items::-webkit-scrollbar-thumb { background: rgba(99, 102, 241, 0.3); border-radius: 2px; }

    .rj-ck-summary-item {
        display: flex; gap: 0.75rem; align-items: flex-start;
    }
    .rj-ck-summary-thumb {
        width: 52px; height: 52px; border-radius: 10px;
        background: #0a0715; border: 1px solid rgba(255, 255, 255, 0.06);
        overflow: hidden; flex-shrink: 0; position: relative;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255, 255, 255, 0.1);
    }
    .rj-ck-summary-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .rj-ck-summary-qty {
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
    .rj-ck-summary-body { flex: 1; min-width: 0; }
    .rj-ck-summary-name {
        font-size: 13px; font-weight: 600; color: #fff;
        margin: 0 0 4px; line-height: 1.3;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .rj-ck-summary-attrs {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #6b7280;
        margin: 0; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .rj-ck-summary-price {
        font-family: ui-monospace, monospace;
        font-size: 13px; font-weight: 700;
        color: #fff; white-space: nowrap;
    }

    .rj-ck-summary-totals {
        padding: 1.25rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 1.25rem;
    }
    .rj-ck-summary-row {
        display: flex; justify-content: space-between;
        padding: 6px 0;
        font-size: 12.5px; color: #9ca3af;
    }
    .rj-ck-summary-row-grand {
        margin-top: 0.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        font-size: 18px;
        font-weight: 800;
        color: #fff;
    }
    .rj-ck-mono { font-family: ui-monospace, monospace; }

    .rj-ck-submit {
        width: 100%;
        display: inline-flex; align-items: center; justify-content: center;
        gap: 0.625rem;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border: none; border-radius: 9999px;
        color: #fff; font-size: 14px; font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.3s;
        box-shadow: 0 12px 32px -12px rgba(99, 102, 241, 0.6);
    }
    .rj-ck-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 18px 40px -12px rgba(168, 85, 247, 0.7);
    }
    .rj-ck-submit:disabled {
        opacity: 0.6; cursor: not-allowed; transform: none;
    }
    .rj-ck-submit i { font-size: 12px; }

    .rj-ck-secure {
        display: flex; align-items: center; justify-content: center;
        gap: 0.5rem;
        margin: 1rem 0 0;
        font-size: 11px; color: #4b5563;
        font-family: ui-monospace, monospace;
        letter-spacing: 0.05em;
    }
    .rj-ck-secure i { color: #10b981; font-size: 10px; }

    .rj-ck-error {
        display: flex; gap: 0.5rem; align-items: flex-start;
        margin-top: 1rem;
        padding: 0.75rem 1rem;
        background: rgba(244, 63, 94, 0.08);
        border: 1px solid rgba(244, 63, 94, 0.25);
        border-radius: 10px;
        font-size: 12px; color: #fda4af; line-height: 1.5;
    }
    .rj-ck-error i { margin-top: 2px; color: #f43f5e; }
</style>

<script>
function checkoutForm(config) {
    return {
        zones: config.zones || [],
        subtotal: parseFloat(config.subtotal) || 0,
        submitting: false,
        error: '',

        form: {
            customer: { name: '', email: '', phone: '', company: '' },
            shipping: { address: '', address2: '', city: '', state: '', zip: '', country: 'US' },
            gateway_id: '',
            note: ''
        },

        init() {
            var first = document.querySelector('input[name="gateway_id"]');
            if (first) this.form.gateway_id = first.value;
        },

        get matchedZone() {
            const { zip, city, state } = this.form.shipping;
            if (!zip && !city && !state) return null;

            for (const zone of this.zones) {
                if (!zone.regions || !zone.regions.length) continue;
                for (const r of zone.regions) {
                    const v = (r.value || '').toString().trim().toLowerCase();
                    if (r.type === 'zip' && zip && zip.toString().toLowerCase() === v) return zone;
                    if (r.type === 'city' && city && city.toString().toLowerCase() === v) return zone;
                    if (r.type === 'state' && state && state.toString().toLowerCase() === v) return zone;
                }
            }
            return null;
        },

        get shippingLabel() {
            const zone = this.matchedZone;
            if (!zone) return 'Calculated at checkout';
            return 'Based on ' + zone.name;
        },

        get totalFormatted() {
            return this.subtotal.toFixed(2);
        },

        async submit() {
            if (this.submitting) return;
            this.error = '';
            this.submitting = true;

            try {
                const res = await fetch('{{ route('checkout.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await res.json();

                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                    return;
                }

                this.error = data.message || 'Could not place order. Please try again.';
                if (data.errors) {
                    const firstError = Object.values(data.errors)[0];
                    this.error = Array.isArray(firstError) ? firstError[0] : firstError;
                }
            } catch (e) {
                this.error = 'Network error. Please check your connection and try again.';
            }

            this.submitting = false;
        }
    };
}
</script>

@endsection

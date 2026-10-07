@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Design Studio')
@section('meta_description', 'Build your gang sheet')

@section('content')

@php
    $pricing = [
        'enabled' => \App\Models\Setting::get('design_custom_enabled', '1') == '1',
        'per_sq_inch' => (float) \App\Models\Setting::get('design_custom_price_per_sq_inch', '0.05'),
        'min_price' => (float) \App\Models\Setting::get('design_custom_min_price', '4.50'),
        'min_inch' => (float) \App\Models\Setting::get('design_custom_min_inch', '1'),
        'max_inch' => (float) \App\Models\Setting::get('design_custom_max_inch', '60'),
    ];
@endphp

<section id="rjHero"
         class="rj-dz"
         x-data="designStudio({
            zones: {{ \Illuminate\Support\Js::from($zones) }},
            product: {{ \Illuminate\Support\Js::from($product) }},
            allProducts: {{ \Illuminate\Support\Js::from($allProducts) }},
            pricing: {{ \Illuminate\Support\Js::from($pricing) }}
         })">

    <div class="rj-dz-toolbar">
        <div class="rj-dz-tb-group">
            <label class="rj-dz-tb-btn rj-dz-tb-primary">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Upload</span>
                <input type="file" accept="image/png,image/jpeg,image/webp" multiple class="hidden" @change="onFiles($event)">
            </label>

            <button type="button" class="rj-dz-tb-btn" @click="removeBg()" :disabled="!hasActiveImage || bgWorking">
                <i class="fa-solid" :class="bgWorking ? 'fa-spinner fa-spin' : 'fa-wand-magic-sparkles'"></i>
                <span x-text="bgWorking ? 'Working...' : 'Remove BG'"></span>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="flipH()" :disabled="!hasActiveImage" title="Flip">
                <i class="fa-solid fa-left-right"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="rotate(90)" :disabled="!hasActiveImage" title="Rotate">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="layerUp()" :disabled="!hasActiveImage" title="Bring forward">
                <i class="fa-solid fa-arrow-up"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="layerDown()" :disabled="!hasActiveImage" title="Send backward">
                <i class="fa-solid fa-arrow-down"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn rj-dz-tb-danger" @click="removeActive()" :disabled="!hasActiveImage" title="Delete">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="zoom(-1)" title="Zoom out">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
            </button>
            <span class="rj-dz-tb-zoom" x-text="Math.round(zoomLevel * 100) + '%'"></span>
            <button type="button" class="rj-dz-tb-btn" @click="zoom(1)" title="Zoom in">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="resetView()" title="Reset">
                <i class="fa-solid fa-arrows-rotate"></i>
            </button>
        </div>

        <div class="rj-dz-tb-spacer"></div>

        <div class="rj-dz-tb-info">
            <span class="rj-dz-dot"></span>
            <span x-text="items.length + ' ' + (items.length === 1 ? 'item' : 'items')"></span>
        </div>
    </div>

    <div class="rj-dz-body">

        <aside class="rj-dz-left">
            <div class="rj-dz-left-inner">
                <div class="rj-dz-left-label">// Product</div>

                <div class="rj-dz-select-wrap">
                    <select class="rj-dz-select" x-ref="productSelect" @change="changeProduct($event)"></select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>

                <template x-if="product">
                    <div class="rj-dz-left-mock-wrap">
                        <div class="rj-dz-left-mock">
                            <template x-if="product.image">
                                <img :src="product.image" :alt="product.name" class="rj-dz-left-img">
                            </template>
                            <template x-if="!product.image">
                                <div class="rj-dz-left-img-empty">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            </template>
                            <div class="rj-dz-left-overlay">
                                <span class="rj-dz-left-dot"></span>
                                <span x-text="currentZone ? currentZone.label : 'Custom'"></span>
                            </div>
                        </div>

                        <p class="rj-dz-left-name" x-text="product.name"></p>
                        <p class="rj-dz-left-meta">
                            <span x-text="'Base $' + Number(product.base_price).toFixed(2)"></span>
                            <span class="rj-dz-left-sep">·</span>
                            <span x-text="currentZone ? (currentZone.width_inch + '×' + currentZone.height_inch + ' in') : 'Custom'"></span>
                        </p>
                    </div>
                </template>

                <div class="rj-dz-left-tip">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Only DTF Transfers, UV Stickers and Special Films support custom design.</span>
                </div>
            </div>
        </aside>

        <div class="rj-dz-stage-wrap">
            <div class="rj-dz-stage" x-ref="stage">
                <canvas id="designCanvas"></canvas>
                <div class="rj-dz-empty" x-show="items.length === 0">
                    <i class="fa-regular fa-image"></i>
                    <p>Upload artwork to start</p>
                </div>
            </div>
        </div>

        <aside class="rj-dz-sidebar">
            <div class="rj-dz-side-block">
                <label class="rj-dz-label">Sheet Size</label>
                <div class="rj-dz-select-wrap">
                    <select class="rj-dz-select" x-ref="zoneSelect" @change="changeZone($event)"></select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <p class="rj-dz-hint" x-text="currentZone ? (currentZone.width_inch + ' × ' + currentZone.height_inch + ' in') : ''"></p>
            </div>

            <div class="rj-dz-side-block" x-show="pricing.enabled" x-cloak>
                <label class="rj-dz-label">
                    Custom Size
                    <span class="rj-dz-label-note">override</span>
                </label>
                <div class="rj-dz-custom">
                    <input type="number" :min="pricing.min_inch" :max="pricing.max_inch" step="0.1" x-model.number="customW" placeholder="W" @input="applyCustom()">
                    <span class="rj-dz-custom-sep">×</span>
                    <input type="number" :min="pricing.min_inch" :max="pricing.max_inch" step="0.1" x-model.number="customH" placeholder="H" @input="applyCustom()">
                    <span class="rj-dz-custom-unit">in</span>
                </div>
                <button type="button" class="rj-dz-custom-clear" x-show="isCustom" @click="clearCustom()">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Clear custom</span>
                </button>
            </div>

            <div class="rj-dz-side-block">
                <label class="rj-dz-label">Quantity</label>
                <div class="rj-dz-qty">
                    <button type="button" @click="decQty()">−</button>
                    <input type="number" x-model.number="qty" min="1" max="999">
                    <button type="button" @click="incQty()">+</button>
                </div>
            </div>

            <div class="rj-dz-side-spacer"></div>

            <div class="rj-dz-side-total">
                <div class="rj-dz-total-row">
                    <span>Sheet</span>
                    <span x-text="'$' + sheetPrice.toFixed(2)"></span>
                </div>
                <div class="rj-dz-total-row">
                    <span>Product</span>
                    <span x-text="'$' + productPrice.toFixed(2)"></span>
                </div>
                <div class="rj-dz-total-row">
                    <span>Items</span>
                    <span x-text="items.length"></span>
                </div>
                <div class="rj-dz-total-row rj-dz-total-grand">
                    <span>Total</span>
                    <span x-text="'$' + totalPrice.toFixed(2)"></span>
                </div>
            </div>

            <button type="button"
                    class="rj-dz-add"
                    :disabled="items.length === 0 || adding"
                    @click="addToCart()">
                <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-cart-plus'"></i>
                <span x-text="adding ? 'Adding...' : (items.length === 0 ? 'Upload to start' : 'Add to Cart')"></span>
            </button>
        </aside>

    </div>
</section>

<style>
    .rj-dz {
        position: relative;
        background: #05030f;
        color: #fff;
        display: flex;
        flex-direction: column;
        height: calc(100vh - 80px);
        min-height: 640px;
        overflow: hidden;
    }
    .rj-dz-toolbar {
        display: flex; align-items: center; gap: 8px;
        padding: 10px 16px;
        background: #0a0715;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        flex-shrink: 0;
        overflow-x: auto;
    }
    .rj-dz-tb-group { display: inline-flex; align-items: center; gap: 6px; flex-shrink: 0; }
    .rj-dz-tb-sep { width: 1px; height: 24px; background: rgba(255,255,255,0.08); margin: 0 6px; flex-shrink: 0; }
    .rj-dz-tb-spacer { flex: 1; }
    .rj-dz-tb-btn {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 8px 12px; border-radius: 8px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        color: #d1d5db; font-size: 12px; font-weight: 600;
        cursor: pointer; transition: all .2s;
        font-family: inherit; white-space: nowrap;
    }
    .rj-dz-tb-btn:hover:not(:disabled) {
        background: rgba(99,102,241,0.12);
        border-color: rgba(99,102,241,0.4);
        color: #fff;
    }
    .rj-dz-tb-btn:disabled { opacity: .35; cursor: not-allowed; }
    .rj-dz-tb-primary {
        background: linear-gradient(135deg, #6366f1, #a855f7);
        border-color: transparent; color: #fff;
    }
    .rj-dz-tb-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #7c7ff5, #b966f9);
        box-shadow: 0 0 24px rgba(99,102,241,0.5);
        border-color: transparent;
    }
    .rj-dz-tb-danger:hover:not(:disabled) {
        background: rgba(244,63,94,0.12);
        border-color: rgba(244,63,94,0.4);
        color: #fda4af;
    }
    .rj-dz-tb-zoom {
        font-family: ui-monospace, monospace; font-size: 11px;
        color: #9ca3af; min-width: 44px; text-align: center;
    }
    .rj-dz-tb-info {
        display: inline-flex; align-items: center; gap: 8px;
        font-family: ui-monospace, monospace; font-size: 11px;
        color: #6b7280; text-transform: uppercase; letter-spacing: .15em;
        flex-shrink: 0;
    }
    .rj-dz-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #34d399;
        box-shadow: 0 0 8px 2px rgba(52,211,153,0.7);
    }

    .rj-dz-body {
        flex: 1; display: flex; overflow: hidden; min-height: 0;
    }

    .rj-dz-left {
        width: 240px; flex-shrink: 0;
        background: #0a0715;
        border-right: 1px solid rgba(255,255,255,0.06);
        padding: 20px 16px;
        overflow-y: auto;
    }
    .rj-dz-left-inner {
        position: sticky; top: 0;
        display: flex; flex-direction: column; gap: 12px;
    }
    .rj-dz-left-label {
        font-family: ui-monospace, monospace;
        font-size: 9px; text-transform: uppercase;
        letter-spacing: .25em; color: #f472b6;
        padding-bottom: 8px;
        border-bottom: 1px dashed rgba(244,114,182,0.2);
    }
    .rj-dz-left-mock-wrap {
        display: flex; flex-direction: column; gap: 10px;
        margin-top: 4px;
    }
    .rj-dz-left-mock {
        position: relative;
        aspect-ratio: 1;
        border-radius: 12px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(236,72,153,0.06));
        border: 1px solid rgba(255,255,255,0.08);
    }
    .rj-dz-left-img { width: 100%; height: 100%; object-fit: cover; }
    .rj-dz-left-img-empty {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.08);
        font-size: 2rem;
    }
    .rj-dz-left-overlay {
        position: absolute; left: 8px; bottom: 8px;
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px;
        background: rgba(5,3,15,0.85);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 9999px;
        font-family: ui-monospace, monospace;
        font-size: 9px; text-transform: uppercase;
        letter-spacing: .12em; color: #fff;
    }
    .rj-dz-left-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #6366f1;
        box-shadow: 0 0 6px 1px rgba(99,102,241,0.8);
    }
    .rj-dz-left-name {
        font-size: 13px; font-weight: 700; color: #fff;
        margin: 0; line-height: 1.3;
    }
    .rj-dz-left-meta {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #9ca3af;
        margin: 0; display: flex; gap: 6px; align-items: center;
        letter-spacing: .05em;
    }
    .rj-dz-left-sep { color: #4b5563; }
    .rj-dz-left-tip {
        display: flex; gap: 8px;
        padding: 10px;
        background: rgba(99,102,241,0.06);
        border: 1px solid rgba(99,102,241,0.15);
        border-radius: 10px;
        font-size: 11px; color: #a5b4fc; line-height: 1.5;
    }
    .rj-dz-left-tip i { color: #818cf8; font-size: 11px; margin-top: 2px; }

    .rj-dz-stage-wrap {
        flex: 1; position: relative;
        display: flex; align-items: center; justify-content: center;
        padding: 24px; overflow: hidden;
        background:
            radial-gradient(circle at 30% 40%, rgba(99,102,241,0.06), transparent 60%),
            radial-gradient(circle at 70% 60%, rgba(236,72,153,0.05), transparent 60%),
            #05030f;
    }
    .rj-dz-stage-wrap::before {
        content: ""; position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(99,102,241,0.04) 1px, transparent 1px),
            linear-gradient(90deg, rgba(99,102,241,0.04) 1px, transparent 1px);
        background-size: 40px 40px;
        pointer-events: none;
    }
    .rj-dz-stage {
        position: relative;
        background: #fff;
        border-radius: 8px;
        box-shadow:
            0 0 0 1px rgba(99,102,241,0.2),
            0 30px 80px -20px rgba(0,0,0,0.8),
            0 0 60px rgba(99,102,241,0.15);
        transform-origin: center center;
    }
    .rj-dz-stage .canvas-container {
        border-radius: 8px;
        overflow: hidden;
        display: block !important;
    }
    .rj-dz-empty {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 12px;
        color: #9ca3af; pointer-events: none;
        text-align: center;
    }
    .rj-dz-empty i { font-size: 42px; opacity: .25; }
    .rj-dz-empty p { font-size: 13px; margin: 0; }

    .rj-dz-sidebar {
        width: 300px; flex-shrink: 0;
        background: #0a0715;
        border-left: 1px solid rgba(255,255,255,0.06);
        padding: 20px;
        display: flex; flex-direction: column;
        overflow-y: auto;
    }
    .rj-dz-side-block { margin-bottom: 20px; }
    .rj-dz-side-spacer { flex: 1; }
    .rj-dz-label {
        display: block;
        font-family: ui-monospace, monospace;
        font-size: 10px; text-transform: uppercase;
        letter-spacing: .22em; color: #818cf8;
        margin-bottom: 10px;
    }
    .rj-dz-label-note {
        color: #f472b6; font-size: 8px;
        margin-left: 4px; opacity: .7;
        letter-spacing: .1em;
    }
    .rj-dz-select-wrap { position: relative; }
    .rj-dz-select {
        width: 100%;
        padding: 12px 40px 12px 14px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        color: #fff; font-size: 13px;
        font-family: inherit;
        appearance: none; -webkit-appearance: none;
        outline: none; cursor: pointer;
        transition: all .2s;
    }
    .rj-dz-select:focus {
        border-color: rgba(99,102,241,0.6);
        background: rgba(99,102,241,0.06);
        box-shadow: 0 0 20px rgba(99,102,241,0.2);
    }
    .rj-dz-select option { background: #0a0715; color: #fff; }
    .rj-dz-select-wrap i {
        position: absolute; right: 14px; top: 50%;
        transform: translateY(-50%);
        color: #6b7280; font-size: 10px; pointer-events: none;
    }
    .rj-dz-hint {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #6b7280;
        margin: 8px 0 0; letter-spacing: .1em;
    }

    .rj-dz-custom {
        display: flex; align-items: center; gap: 6px;
    }
    .rj-dz-custom input {
        flex: 1; min-width: 0;
        padding: 10px 8px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #fff; font-size: 13px;
        font-family: ui-monospace, monospace;
        text-align: center;
        outline: none;
        -moz-appearance: textfield;
        transition: all .2s;
    }
    .rj-dz-custom input::-webkit-outer-spin-button,
    .rj-dz-custom input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .rj-dz-custom input:focus {
        border-color: rgba(244,114,182,0.6);
        background: rgba(244,114,182,0.06);
    }
    .rj-dz-custom-sep { color: #6b7280; font-family: ui-monospace, monospace; }
    .rj-dz-custom-unit {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #6b7280;
        letter-spacing: .1em;
    }
    .rj-dz-custom-clear {
        margin-top: 8px;
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 10px;
        background: rgba(244,114,182,0.08);
        border: 1px solid rgba(244,114,182,0.25);
        border-radius: 8px;
        color: #f9a8d4; font-size: 10px;
        font-family: ui-monospace, monospace;
        text-transform: uppercase;
        letter-spacing: .15em;
        cursor: pointer;
        transition: all .2s;
    }
    .rj-dz-custom-clear:hover {
        background: rgba(244,114,182,0.15);
        color: #fbcfe8;
    }

    .rj-dz-qty {
        display: inline-flex; align-items: center;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px; overflow: hidden;
    }
    .rj-dz-qty button {
        width: 42px; height: 42px;
        background: transparent; border: none;
        color: #d1d5db; font-size: 18px; font-weight: 600;
        cursor: pointer; transition: all .2s;
    }
    .rj-dz-qty button:hover { background: rgba(99,102,241,0.15); color: #fff; }
    .rj-dz-qty input {
        width: 64px; height: 42px;
        background: transparent;
        border: none;
        border-left: 1px solid rgba(255,255,255,0.08);
        border-right: 1px solid rgba(255,255,255,0.08);
        color: #fff; text-align: center;
        font-family: ui-monospace, monospace;
        font-size: 14px; font-weight: 600;
        outline: none; -moz-appearance: textfield;
    }
    .rj-dz-qty input::-webkit-outer-spin-button,
    .rj-dz-qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .rj-dz-side-total {
        padding: 16px 0;
        border-top: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 16px;
    }
    .rj-dz-total-row {
        display: flex; justify-content: space-between;
        font-size: 12px; color: #9ca3af;
        padding: 4px 0;
        font-family: ui-monospace, monospace;
    }
    .rj-dz-total-grand {
        font-size: 18px; font-weight: 800;
        color: #fff; padding-top: 12px;
        margin-top: 8px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .rj-dz-add {
        width: 100%;
        display: inline-flex; align-items: center; justify-content: center;
        gap: 10px;
        padding: 16px;
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        border: none; border-radius: 12px;
        color: #fff; font-size: 14px; font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: all .3s;
        box-shadow: 0 10px 30px -10px rgba(168,85,247,0.5);
    }
    .rj-dz-add:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 16px 40px -10px rgba(168,85,247,0.7);
    }
    .rj-dz-add:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

    @media (max-width: 1100px) {
        .rj-dz-left { display: none !important; }
    }
    @media (max-width: 900px) {
        .rj-dz { height: calc(100vh - 64px); }
        .rj-dz-sidebar { width: 240px; }
        .rj-dz-tb-btn span { display: none; }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script>
(function () {
    var DPI = 60;
    var CUSTOM_ID = '__custom__';

    var FALLBACK_ZONES = [
        { id: 1, name: 'A4', slug: 'a4', width_inch: 8.3, height_inch: 11.7, label: 'A4 (8.3 × 11.7 in)', price_addon: 4.50 },
        { id: 2, name: 'A3', slug: 'a3', width_inch: 11.7, height_inch: 16.5, label: 'A3 (11.7 × 16.5 in)', price_addon: 7.50 },
        { id: 3, name: '12 × 12', slug: '12x12', width_inch: 12, height_inch: 12, label: '12 × 12 in', price_addon: 6.00 },
        { id: 4, name: '12 × 24', slug: '12x24', width_inch: 12, height_inch: 24, label: '12 × 24 in', price_addon: 10.00 },
        { id: 5, name: '13 × 19', slug: '13x19', width_inch: 13, height_inch: 19, label: '13 × 19 in', price_addon: 9.00 },
        { id: 6, name: '22 × 24', slug: '22x24', width_inch: 22, height_inch: 24, label: '22 × 24 in', price_addon: 18.00 }
    ];

    var removeBgModulePromise = null;

    function loadRemoveBg() {
        if (!removeBgModulePromise) {
            removeBgModulePromise = import('https://esm.sh/@imgly/background-removal@1.4.5')
                .then(function (m) { return m.default; });
        }
        return removeBgModulePromise;
    }

    function getQuery(name) {
        var params = new URLSearchParams(window.location.search);
        return params.get(name);
    }

    window.designStudio = function (config) {
        var incoming = (config.zones && config.zones.length) ? config.zones : FALLBACK_ZONES;
        var zones = incoming.map(function (z) {
            return {
                id: z.id,
                name: z.name || '',
                slug: z.slug || '',
                width_inch: Number(z.width_inch) || 12,
                height_inch: Number(z.height_inch) || 12,
                label: z.label || ((Number(z.width_inch) || 12) + ' × ' + (Number(z.height_inch) || 12) + ' in'),
                price_addon: Number(z.price_addon) || 0
            };
        });

        var products = config.allProducts || [];
        var initialProduct = config.product || null;
        var pricing = config.pricing || {
            enabled: true,
            per_sq_inch: 0.05,
            min_price: 4.50,
            min_inch: 1,
            max_inch: 60
        };

        return {
            zones: zones,
            products: products,
            product: initialProduct,
            pricing: pricing,
            zoneId: zones[0] ? zones[0].id : null,
            customW: null,
            customH: null,
            isCustom: false,
            qty: 1,
            items: [],
            hasActiveImage: false,
            bgWorking: false,
            adding: false,
            zoomLevel: 1,
            fitScale: 1,
            canvas: null,
            ready: false,

            init() {
                var self = this;

                this.buildProductOptions();
                this.buildZoneOptions();

                this.$nextTick(function () {
                    self.setupCanvas();

                    self.$nextTick(function () {
                        self.readQueryParams();
                        self.ready = true;
                    });
                });
            },

            buildProductOptions() {
                var s = this.$refs.productSelect;
                if (!s) return;

                if (!this.products.length) {
                    s.innerHTML = '<option value="">— No designable products —</option>';
                    s.disabled = true;
                    return;
                }

                s.innerHTML = this.products.map(function (p) {
                    var safe = String(p.name).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    return '<option value="' + p.id + '">' + safe + '</option>';
                }).join('');

                if (this.product) s.value = this.product.id;
            },

            buildZoneOptions() {
                var s = this.$refs.zoneSelect;
                if (!s) return;

                var opts = this.zones.map(function (z) {
                    var safe = String(z.label).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    return '<option value="' + z.id + '">' + safe + '</option>';
                });

                if (this.pricing.enabled) {
                    opts.push('<option value="' + CUSTOM_ID + '">Custom size (see below)</option>');
                }

                s.innerHTML = opts.join('');

                if (this.zoneId) {
                    s.value = this.zoneId;
                } else if (this.zones[0]) {
                    this.zoneId = this.zones[0].id;
                    s.value = this.zoneId;
                }
            },

            readQueryParams() {
                var w = parseFloat(getQuery('w'));
                var h = parseFloat(getQuery('h'));

                if (w > 0 && h > 0 && this.pricing.enabled) {
                    this.customW = w;
                    this.customH = h;
                    this.isCustom = true;

                    if (this.$refs.zoneSelect) {
                        this.$refs.zoneSelect.value = CUSTOM_ID;
                    }

                    this.applyZoneSize();
                }
            },

            get currentZone() {
                if (this.isCustom && this.customW > 0 && this.customH > 0) {
                    return {
                        id: CUSTOM_ID,
                        name: 'Custom',
                        slug: 'custom',
                        width_inch: this.customW,
                        height_inch: this.customH,
                        label: 'Custom (' + this.customW + ' × ' + this.customH + ' in)',
                        price_addon: this.calculateCustomPrice()
                    };
                }

                if (!this.zones.length) return null;
                if (!this.zoneId) return this.zones[0];
                var found = this.zones.find(z => Number(z.id) === Number(this.zoneId));
                return found || this.zones[0];
            },

            calculateCustomPrice() {
                if (!this.customW || !this.customH) return 0;

                var perSqInch = Number(this.pricing.per_sq_inch) || 0;
                var minPrice = Number(this.pricing.min_price) || 0;
                var areaSqIn = this.customW * this.customH;
                var calculated = areaSqIn * perSqInch;

                return Math.round(Math.max(calculated, minPrice) * 100) / 100;
            },

            get sheetPrice() {
                if (!this.currentZone) return 0;
                return Number(this.currentZone.price_addon) || 0;
            },

            get productPrice() {
                return this.product ? Number(this.product.base_price) || 0 : 0;
            },

            get basePrice() {
                return this.sheetPrice + this.productPrice;
            },

            get totalPrice() {
                return this.basePrice * (this.qty || 1);
            },

            setupCanvas() {
                var el = document.getElementById('designCanvas');
                if (!el || typeof fabric === 'undefined') return;

                this.canvas = new fabric.Canvas('designCanvas', {
                    backgroundColor: '#ffffff',
                    preserveObjectStacking: true,
                    selection: true
                });

                var self = this;
                this.canvas.on('selection:created', function () { self.syncActive(); });
                this.canvas.on('selection:updated', function () { self.syncActive(); });
                this.canvas.on('selection:cleared', function () { self.syncActive(); });
                this.canvas.on('object:added', function () { self.syncCount(); });
                this.canvas.on('object:removed', function () { self.syncCount(); });

                this.applyZoneSize();
                window.addEventListener('resize', function () { self.fitStage(); });
            },

            syncActive() {
                var obj = this.canvas ? this.canvas.getActiveObject() : null;
                this.hasActiveImage = !!(obj && obj.type === 'image');
            },

            syncCount() {
                this.items = this.canvas ? this.canvas.getObjects().filter(function (o) { return o.type === 'image'; }) : [];
            },

            applyZoneSize() {
                var z = this.currentZone;
                if (!z || !this.canvas) return;

                var newW = Math.max(1, Math.round(Number(z.width_inch) * DPI));
                var newH = Math.max(1, Math.round(Number(z.height_inch) * DPI));
                var oldW = this.canvas.getWidth();
                var oldH = this.canvas.getHeight();

                if (newW === oldW && newH === oldH) {
                    this.fitStage();
                    return;
                }

                var objects = this.canvas.getObjects();
                var hasObjects = objects.length > 0;

                var snapshots = objects.map(function (o) {
                    return {
                        obj: o,
                        relX: oldW > 0 ? o.left / oldW : 0.5,
                        relY: oldH > 0 ? o.top / oldH : 0.5
                    };
                });

                this.canvas.setWidth(newW);
                this.canvas.setHeight(newH);

                if (hasObjects) {
                    snapshots.forEach(function (s) {
                        var newLeft = s.relX * newW;
                        var newTop = s.relY * newH;
                        s.obj.set({
                            left: newLeft,
                            top: newTop
                        });
                        s.obj.setCoords();
                    });
                }

                this.canvas.renderAll();
                this.fitStage();
            },

            fitStage() {
                var stage = this.$refs.stage;
                if (!stage || !this.canvas) return;

                var wrap = stage.parentElement;
                if (!wrap) return;

                var pad = 48;
                var maxW = wrap.clientWidth - pad;
                var maxH = wrap.clientHeight - pad;
                var cw = this.canvas.getWidth();
                var ch = this.canvas.getHeight();

                if (cw <= 0 || ch <= 0) return;

                var fit = Math.min(maxW / cw, maxH / ch, 1);
                this.fitScale = fit;
                stage.style.transform = 'scale(' + (fit * this.zoomLevel) + ')';
            },

            changeZone(e) {
                var val = e && e.target ? e.target.value : this.zoneId;

                if (val === CUSTOM_ID) {
                    this.isCustom = true;
                    this.zoneId = null;
                    if (this.customW > 0 && this.customH > 0) {
                        this.applyZoneSize();
                    }
                    return;
                }

                this.isCustom = false;
                this.customW = null;
                this.customH = null;
                this.zoneId = Number(val);
                this.applyZoneSize();
            },

            applyCustom() {
                if (!this.customW || !this.customH) return;

                var min = Number(this.pricing.min_inch) || 1;
                var max = Number(this.pricing.max_inch) || 60;

                if (this.customW < min) this.customW = min;
                if (this.customH < min) this.customH = min;
                if (this.customW > max) this.customW = max;
                if (this.customH > max) this.customH = max;

                this.isCustom = true;
                this.zoneId = null;

                if (this.$refs.zoneSelect) {
                    this.$refs.zoneSelect.value = CUSTOM_ID;
                }

                this.applyZoneSize();
            },

            clearCustom() {
                this.customW = null;
                this.customH = null;
                this.isCustom = false;
                if (this.zones[0]) {
                    this.zoneId = this.zones[0].id;
                    if (this.$refs.zoneSelect) this.$refs.zoneSelect.value = this.zoneId;
                }
                this.applyZoneSize();
            },

            changeProduct(e) {
                var id = Number(e.target.value);
                var p = this.products.find(x => Number(x.id) === id);
                if (!p) return;
                this.product = p;

                if (p.slug) {
                    var url = new URL(window.location.href);
                    url.pathname = '/design/' + p.slug;
                    window.history.replaceState({}, '', url.toString());
                }
            },

            onFiles(e) {
                var files = Array.from(e.target.files || []);
                files.forEach(f => this.addImageFromFile(f));
                e.target.value = '';
            },

            addImageFromFile(file) {
                var reader = new FileReader();
                var self = this;
                reader.onload = function (ev) { self.addImageFromSrc(ev.target.result); };
                reader.readAsDataURL(file);
            },

            addImageFromSrc(src, replaceObj) {
                if (!this.canvas) return;
                var self = this;

                fabric.Image.fromURL(src, function (img) {
                    var cw = self.canvas.getWidth();
                    var ch = self.canvas.getHeight();

                    var maxW = cw * 0.6;
                    var maxH = ch * 0.6;
                    var scale = Math.min(maxW / img.width, maxH / img.height, 1);

                    img.set({
                        left: cw / 2 - (img.width * scale) / 2,
                        top: ch / 2 - (img.height * scale) / 2,
                        scaleX: scale,
                        scaleY: scale,
                        cornerColor: '#6366f1',
                        cornerStrokeColor: '#fff',
                        borderColor: '#6366f1',
                        cornerSize: 12,
                        cornerStyle: 'circle',
                        transparentCorners: false
                    });

                    if (replaceObj) {
                        img.set({
                            left: replaceObj.left,
                            top: replaceObj.top,
                            scaleX: replaceObj.scaleX,
                            scaleY: replaceObj.scaleY,
                            angle: replaceObj.angle
                        });
                        self.canvas.remove(replaceObj);
                    }

                    self.canvas.add(img);
                    self.canvas.setActiveObject(img);
                    self.canvas.renderAll();
                    self.syncActive();
                    self.syncCount();
                }, { crossOrigin: 'anonymous' });
            },

            async removeBg() {
                var active = this.canvas ? this.canvas.getActiveObject() : null;
                if (!active || active.type !== 'image') return;

                this.bgWorking = true;

                try {
                    var removeFn = await loadRemoveBg();
                    var blob = await removeFn(active.getSrc());
                    var url = URL.createObjectURL(blob);
                    this.addImageFromSrc(url, active);
                } catch (e) {
                    this.toast('Background removal failed');
                }

                this.bgWorking = false;
            },

            flipH() {
                var a = this.canvas ? this.canvas.getActiveObject() : null;
                if (!a) return;
                a.set('flipX', !a.flipX);
                this.canvas.renderAll();
            },

            rotate(deg) {
                var a = this.canvas ? this.canvas.getActiveObject() : null;
                if (!a) return;
                a.rotate(((a.angle || 0) + deg) % 360);
                this.canvas.renderAll();
            },

            layerUp() {
                var a = this.canvas ? this.canvas.getActiveObject() : null;
                if (!a) return;
                this.canvas.bringForward(a);
                this.canvas.renderAll();
            },

            layerDown() {
                var a = this.canvas ? this.canvas.getActiveObject() : null;
                if (!a) return;
                this.canvas.sendBackwards(a);
                this.canvas.renderAll();
            },

            removeActive() {
                var a = this.canvas ? this.canvas.getActiveObject() : null;
                if (!a) return;
                this.canvas.remove(a);
                this.canvas.discardActiveObject();
                this.canvas.renderAll();
                this.syncActive();
                this.syncCount();
            },

            zoom(dir) {
                this.zoomLevel = Math.max(0.25, Math.min(3, this.zoomLevel + dir * 0.15));
                this.fitStage();
            },

            resetView() {
                this.zoomLevel = 1;
                this.fitStage();
            },

            incQty() { this.qty = Math.min(999, (this.qty || 1) + 1); },
            decQty() { this.qty = Math.max(1, (this.qty || 1) - 1); },

            async addToCart() {
                if (this.items.length === 0 || this.adding) return;

                this.adding = true;

                var z = this.currentZone;
                var snapshot = this.canvas.toDataURL({ format: 'jpeg', quality: 0.7 });

                var attributes = {
                    'Sheet Size': z ? (z.width_inch + ' × ' + z.height_inch + ' in') : 'Custom',
                    'Items': this.items.length
                };

                if (this.isCustom) {
                    attributes['Custom'] = 'yes';
                }

                var payload = {
                    product_id: this.product ? this.product.id : null,
                    name: this.product ? this.product.name : ('Custom Gang Sheet — ' + (z ? z.label : 'Custom')),
                    unit_price: this.basePrice,
                    qty: this.qty,
                    attributes: attributes,
                    print_type: 'custom_size',
                    note: this.items.length + ' design item(s) · ' + (z ? (z.width_inch + '×' + z.height_inch + ' in') : ''),
                    image: snapshot
                };

                try {
                    var store = window.Alpine && window.Alpine.store('cart');
                    if (!store) {
                        this.toast('Cart not ready');
                        this.adding = false;
                        return;
                    }

                    var res = await store.add(payload);

                    if (res && res.success) {
                        this.toast('Added to cart ✓');
                    } else {
                        this.toast('Could not add to cart');
                    }
                } catch (e) {
                    this.toast('Error: ' + (e.message || 'unknown'));
                }

                this.adding = false;
            },

            toast(msg) {
                var el = document.createElement('div');
                el.textContent = msg;
                el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#0a0715;color:#fff;padding:12px 24px;border-radius:9999px;border:1px solid rgba(99,102,241,0.4);font-size:13px;font-weight:600;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.6);';
                document.body.appendChild(el);
                setTimeout(function () { el.remove(); }, 2200);
            }
        };
    };
})();
</script>

@endsection

@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Design Studio')
@section('meta_description', 'Build your gang sheet')

@section('content')

@php
    $pricing = [
        'enabled' => (\App\Models\Setting::get('design_custom_enabled') ?? '1') == '1',
        'per_sq_inch' => (float) (\App\Models\Setting::get('design_custom_price_per_sq_inch') ?? '0.05'),
        'min_price' => (float) (\App\Models\Setting::get('design_custom_min_price') ?? '4.50'),
        'min_inch' => (float) (\App\Models\Setting::get('design_custom_min_inch') ?? '1'),
        'max_inch' => (float) (\App\Models\Setting::get('design_custom_max_inch') ?? '60'),
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

    {{-- ============ TOOLBAR ============ --}}
    <div class="rj-dz-toolbar">
        <div class="rj-dz-tb-group">
            <label class="rj-dz-tb-btn rj-dz-tb-primary" title="Upload images (or drag & drop / paste)">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Upload</span>
                <input type="file" accept="image/png,image/jpeg,image/webp" multiple class="hidden" @change="onFiles($event)">
            </label>

            <button type="button" class="rj-dz-tb-btn" @click="removeBg()" :disabled="!single || bgWorking">
                <i class="fa-solid" :class="bgWorking ? 'fa-spinner fa-spin' : 'fa-wand-magic-sparkles'"></i>
                <span x-text="bgWorking ? 'Working...' : 'Remove BG'"></span>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="undo()" :disabled="!canUndo" title="Undo (Ctrl+Z)">
                <i class="fa-solid fa-rotate-left"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="redo()" :disabled="!canRedo" title="Redo (Ctrl+Y)">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="flipH()" :disabled="!hasActive" title="Flip horizontally">
                <i class="fa-solid fa-left-right"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="rotate(90)" :disabled="!hasActive" title="Rotate 90°">
                <i class="fa-solid fa-arrows-spin"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="layerUp()" :disabled="!hasActive" title="Bring forward">
                <i class="fa-solid fa-arrow-up"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="layerDown()" :disabled="!hasActive" title="Send backward">
                <i class="fa-solid fa-arrow-down"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="duplicate()" :disabled="!hasActive" title="Duplicate (Ctrl+D)">
                <i class="fa-regular fa-clone"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn rj-dz-tb-danger" @click="removeActive()" :disabled="!hasActive" title="Delete (Del)">
                <i class="fa-solid fa-trash-can"></i>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="autoArrange()" :disabled="itemCount < 2" title="Auto arrange on sheet">
                <i class="fa-solid fa-table-cells-large"></i>
                <span>Arrange</span>
            </button>
            <button type="button" class="rj-dz-tb-btn rj-dz-tb-danger" @click="clearAll()" :disabled="itemCount === 0" title="Remove all items">
                <i class="fa-solid fa-broom"></i>
                <span>Clear</span>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="zoom(-1)" title="Zoom out">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
            </button>
            <span class="rj-dz-tb-zoom" x-text="Math.round(fitScale * zoomLevel * 100) + '%'"></span>
            <button type="button" class="rj-dz-tb-btn" @click="zoom(1)" title="Zoom in">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="resetView()" title="Fit to screen">
                <i class="fa-solid fa-expand"></i>
            </button>
        </div>

        <div class="rj-dz-tb-spacer"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="toggleBg()" title="Toggle preview background">
                <i class="fa-solid fa-chess-board"></i>
                <span x-text="bgMode === 'white' ? 'White' : 'Transparent'"></span>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="downloadPng()" :disabled="itemCount === 0" title="Download print-ready PNG (transparent)">
                <i class="fa-solid fa-download"></i>
                <span>PNG</span>
            </button>
        </div>

        <div class="rj-dz-tb-info">
            <span class="rj-dz-dot"></span>
            <span x-text="itemCount + ' ' + (itemCount === 1 ? 'item' : 'items')"></span>
        </div>
    </div>

    <div class="rj-dz-body">

        {{-- ============ LEFT: PRODUCT ============ --}}
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

        {{-- ============ STAGE ============ --}}
        <div class="rj-dz-stage-wrap"
             x-ref="wrap"
             @dragover.prevent="dragging = true"
             @dragleave.prevent="dragging = false"
             @drop.prevent="onDrop($event)"
             :class="{ 'rj-dz-dragging': dragging }">
            <div class="rj-dz-stage" x-ref="stage" :class="'rj-dz-bg-' + bgMode">
                <canvas id="designCanvas"></canvas>
                <div class="rj-dz-empty" x-show="itemCount === 0">
                    <i class="fa-regular fa-image"></i>
                    <p>Upload, drop or paste artwork to start</p>
                </div>
            </div>
        </div>

        {{-- ============ RIGHT SIDEBAR ============ --}}
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
                    <input type="number" :min="pricing.min_inch" :max="pricing.max_inch" step="0.1" x-model.number="customW" placeholder="W" @input.debounce.400ms="applyCustom()">
                    <span class="rj-dz-custom-sep">×</span>
                    <input type="number" :min="pricing.min_inch" :max="pricing.max_inch" step="0.1" x-model.number="customH" placeholder="H" @input.debounce.400ms="applyCustom()">
                    <span class="rj-dz-custom-unit">in</span>
                </div>
                <button type="button" class="rj-dz-custom-clear" x-show="isCustom" @click="clearCustom()">
                    <i class="fa-solid fa-xmark"></i>
                    <span>Clear custom</span>
                </button>
            </div>

            {{-- Selected image controls --}}
            <div class="rj-dz-side-block rj-dz-sel" x-show="single" x-cloak>
                <label class="rj-dz-label">
                    Selected Image
                    <span class="rj-dz-dpi" :class="'rj-dz-dpi-' + dpiClass" x-text="sel.dpi + ' DPI'"></span>
                </label>

                <div class="rj-dz-custom">
                    <input type="number" step="0.01" min="0.1" :value="sel.w" placeholder="W" @change="setSize('w', $event.target.value)">
                    <button type="button" class="rj-dz-lock" :class="{ 'is-on': lockRatio }" @click="toggleLock()" :title="lockRatio ? 'Aspect ratio locked' : 'Aspect ratio unlocked'">
                        <i class="fa-solid" :class="lockRatio ? 'fa-lock' : 'fa-lock-open'"></i>
                    </button>
                    <input type="number" step="0.01" min="0.1" :value="sel.h" placeholder="H" @change="setSize('h', $event.target.value)">
                    <span class="rj-dz-custom-unit">in</span>
                </div>

                <div class="rj-dz-row">
                    <div class="rj-dz-mini">
                        <span>Angle</span>
                        <input type="number" step="1" :value="sel.angle" @change="setAngle($event.target.value)">
                    </div>
                    <div class="rj-dz-mini rj-dz-mini-grow">
                        <span x-text="'Opacity ' + sel.opacity + '%'"></span>
                        <input type="range" min="10" max="100" step="1" :value="sel.opacity" @change="setOpacity($event.target.value)">
                    </div>
                </div>

                <div class="rj-dz-actions">
                    <button type="button" class="rj-dz-mini-btn" @click="fitToSheet()">
                        <i class="fa-solid fa-maximize"></i><span>Fit</span>
                    </button>
                    <button type="button" class="rj-dz-mini-btn" @click="center('h')">
                        <i class="fa-solid fa-arrows-left-right-to-line"></i><span>Center H</span>
                    </button>
                    <button type="button" class="rj-dz-mini-btn" @click="center('v')">
                        <i class="fa-solid fa-arrows-up-to-line"></i><span>Center V</span>
                    </button>
                </div>
            </div>

            <div class="rj-dz-side-block">
                <label class="rj-dz-label">Quantity</label>
                <div class="rj-dz-qty">
                    <button type="button" @click="decQty()">−</button>
                    <input type="number" x-model.number="qty" min="1" max="999" @blur="normalizeQty()">
                    <button type="button" @click="incQty()">+</button>
                </div>
            </div>

            <div class="rj-dz-side-spacer"></div>

            <div class="rj-dz-warn" x-show="outCount > 0" x-cloak>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span x-text="outCount + ' item(s) extend beyond the sheet and will be cut off.'"></span>
            </div>
            <div class="rj-dz-warn rj-dz-warn-soft" x-show="lowDpiCount > 0" x-cloak>
                <i class="fa-solid fa-circle-exclamation"></i>
                <span x-text="lowDpiCount + ' item(s) below 100 DPI may print blurry.'"></span>
            </div>

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
                    <span x-text="itemCount"></span>
                </div>
                <div class="rj-dz-total-row rj-dz-total-grand">
                    <span>Total</span>
                    <span x-text="'$' + totalPrice.toFixed(2)"></span>
                </div>
            </div>

            <button type="button"
                    class="rj-dz-add"
                    :disabled="itemCount === 0 || adding"
                    @click="addToCart()">
                <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-cart-plus'"></i>
                <span x-text="adding ? 'Adding...' : (itemCount === 0 ? 'Upload to start' : 'Add to Cart')"></span>
            </button>
        </aside>

    </div>
</section>

<style>
    [x-cloak] { display: none !important; }

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
    .rj-dz-tb-spacer { flex: 1; min-width: 8px; }
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
        flex-shrink: 0; margin-left: 12px;
    }
    .rj-dz-dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: #34d399;
        box-shadow: 0 0 8px 2px rgba(52,211,153,0.7);
    }

    .rj-dz-body {
        flex: 1; display: flex; overflow: hidden; min-height: 0;
    }

    /* ---- left ---- */
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
    .rj-dz-left-mock-wrap { display: flex; flex-direction: column; gap: 10px; margin-top: 4px; }
    .rj-dz-left-mock {
        position: relative; aspect-ratio: 1;
        border-radius: 12px; overflow: hidden;
        background: linear-gradient(135deg, rgba(99,102,241,0.06), rgba(236,72,153,0.06));
        border: 1px solid rgba(255,255,255,0.08);
    }
    .rj-dz-left-img { width: 100%; height: 100%; object-fit: cover; }
    .rj-dz-left-img-empty {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.08); font-size: 2rem;
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
        background: #6366f1; box-shadow: 0 0 6px 1px rgba(99,102,241,0.8);
    }
    .rj-dz-left-name { font-size: 13px; font-weight: 700; color: #fff; margin: 0; line-height: 1.3; }
    .rj-dz-left-meta {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #9ca3af;
        margin: 0; display: flex; gap: 6px; align-items: center; letter-spacing: .05em;
    }
    .rj-dz-left-sep { color: #4b5563; }
    .rj-dz-left-tip {
        display: flex; gap: 8px; padding: 10px;
        background: rgba(99,102,241,0.06);
        border: 1px solid rgba(99,102,241,0.15);
        border-radius: 10px;
        font-size: 11px; color: #a5b4fc; line-height: 1.5;
    }
    .rj-dz-left-tip i { color: #818cf8; font-size: 11px; margin-top: 2px; }

    /* ---- stage (scrollable, so zoom never clips) ---- */
    .rj-dz-stage-wrap {
        flex: 1; position: relative;
        display: flex;
        padding: 24px; overflow: auto;
        background:
            radial-gradient(circle at 30% 40%, rgba(99,102,241,0.06), transparent 60%),
            radial-gradient(circle at 70% 60%, rgba(236,72,153,0.05), transparent 60%),
            #05030f;
        transition: box-shadow .2s;
    }
    .rj-dz-stage-wrap.rj-dz-dragging { box-shadow: inset 0 0 0 2px rgba(99,102,241,0.7); }
    .rj-dz-stage {
        position: relative;
        margin: auto;           /* centers when small, scrolls when large */
        flex-shrink: 0;
        border-radius: 8px;
        box-shadow:
            0 0 0 1px rgba(99,102,241,0.2),
            0 30px 80px -20px rgba(0,0,0,0.8),
            0 0 60px rgba(99,102,241,0.15);
    }
    .rj-dz-bg-white { background: #fff; }
    .rj-dz-bg-checker {
        background-color: #fff;
        background-image:
            linear-gradient(45deg, #d1d5db 25%, transparent 25%, transparent 75%, #d1d5db 75%),
            linear-gradient(45deg, #d1d5db 25%, transparent 25%, transparent 75%, #d1d5db 75%);
        background-size: 20px 20px;
        background-position: 0 0, 10px 10px;
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
        gap: 12px; color: #6b7280; pointer-events: none; text-align: center;
    }
    .rj-dz-empty i { font-size: 42px; opacity: .35; }
    .rj-dz-empty p { font-size: 13px; margin: 0; }

    /* ---- right sidebar ---- */
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
        display: flex; align-items: center; justify-content: space-between;
        font-family: ui-monospace, monospace;
        font-size: 10px; text-transform: uppercase;
        letter-spacing: .22em; color: #818cf8;
        margin-bottom: 10px;
    }
    .rj-dz-label-note { color: #f472b6; font-size: 8px; margin-left: 4px; opacity: .7; letter-spacing: .1em; }
    .rj-dz-select-wrap { position: relative; }
    .rj-dz-select {
        width: 100%;
        padding: 12px 40px 12px 14px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        color: #fff; font-size: 13px; font-family: inherit;
        appearance: none; -webkit-appearance: none;
        outline: none; cursor: pointer; transition: all .2s;
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
        font-size: 10px; color: #6b7280; margin: 8px 0 0; letter-spacing: .1em;
    }

    .rj-dz-custom { display: flex; align-items: center; gap: 6px; }
    .rj-dz-custom input {
        flex: 1; min-width: 0;
        padding: 10px 8px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: #fff; font-size: 13px;
        font-family: ui-monospace, monospace;
        text-align: center; outline: none;
        -moz-appearance: textfield; transition: all .2s;
    }
    .rj-dz-custom input::-webkit-outer-spin-button,
    .rj-dz-custom input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .rj-dz-custom input:focus { border-color: rgba(244,114,182,0.6); background: rgba(244,114,182,0.06); }
    .rj-dz-custom-sep { color: #6b7280; font-family: ui-monospace, monospace; }
    .rj-dz-custom-unit { font-family: ui-monospace, monospace; font-size: 10px; color: #6b7280; letter-spacing: .1em; }
    .rj-dz-custom-clear {
        margin-top: 8px;
        display: inline-flex; align-items: center; gap: 6px;
        padding: 6px 10px;
        background: rgba(244,114,182,0.08);
        border: 1px solid rgba(244,114,182,0.25);
        border-radius: 8px;
        color: #f9a8d4; font-size: 10px;
        font-family: ui-monospace, monospace;
        text-transform: uppercase; letter-spacing: .15em;
        cursor: pointer; transition: all .2s;
    }
    .rj-dz-custom-clear:hover { background: rgba(244,114,182,0.15); color: #fbcfe8; }

    /* selected image panel */
    .rj-dz-sel {
        padding: 14px;
        background: rgba(99,102,241,0.05);
        border: 1px solid rgba(99,102,241,0.18);
        border-radius: 12px;
    }
    .rj-dz-sel .rj-dz-custom input:focus { border-color: rgba(99,102,241,0.7); background: rgba(99,102,241,0.08); }
    .rj-dz-lock {
        width: 32px; height: 36px; flex-shrink: 0;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; color: #6b7280; cursor: pointer; transition: all .2s;
    }
    .rj-dz-lock.is-on { color: #a5b4fc; border-color: rgba(99,102,241,0.5); background: rgba(99,102,241,0.12); }
    .rj-dz-dpi {
        font-size: 9px; letter-spacing: .1em;
        padding: 2px 8px; border-radius: 9999px;
        border: 1px solid transparent;
    }
    .rj-dz-dpi-ok   { color: #6ee7b7; background: rgba(16,185,129,.12); border-color: rgba(16,185,129,.35); }
    .rj-dz-dpi-warn { color: #fcd34d; background: rgba(245,158,11,.12); border-color: rgba(245,158,11,.35); }
    .rj-dz-dpi-bad  { color: #fda4af; background: rgba(244,63,94,.12);  border-color: rgba(244,63,94,.35); }
    .rj-dz-row { display: flex; gap: 10px; margin-top: 12px; align-items: flex-end; }
    .rj-dz-mini { display: flex; flex-direction: column; gap: 6px; width: 64px; }
    .rj-dz-mini-grow { flex: 1; width: auto; }
    .rj-dz-mini span { font-family: ui-monospace, monospace; font-size: 9px; color: #9ca3af; letter-spacing: .1em; text-transform: uppercase; }
    .rj-dz-mini input[type=number] {
        width: 100%; padding: 8px 6px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px; color: #fff; font-size: 12px;
        font-family: ui-monospace, monospace; text-align: center; outline: none;
    }
    .rj-dz-mini input[type=range] { width: 100%; accent-color: #6366f1; }
    .rj-dz-actions { display: flex; gap: 6px; margin-top: 12px; }
    .rj-dz-mini-btn {
        flex: 1; display: inline-flex; flex-direction: column; align-items: center; gap: 4px;
        padding: 8px 4px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 8px; color: #d1d5db; cursor: pointer;
        font-size: 9px; font-family: ui-monospace, monospace;
        text-transform: uppercase; letter-spacing: .08em; transition: all .2s;
    }
    .rj-dz-mini-btn i { font-size: 12px; }
    .rj-dz-mini-btn:hover { background: rgba(99,102,241,0.15); border-color: rgba(99,102,241,0.4); color: #fff; }

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
        background: transparent; border: none;
        border-left: 1px solid rgba(255,255,255,0.08);
        border-right: 1px solid rgba(255,255,255,0.08);
        color: #fff; text-align: center;
        font-family: ui-monospace, monospace;
        font-size: 14px; font-weight: 600;
        outline: none; -moz-appearance: textfield;
    }
    .rj-dz-qty input::-webkit-outer-spin-button,
    .rj-dz-qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .rj-dz-warn {
        display: flex; gap: 8px; align-items: flex-start;
        padding: 10px; margin-bottom: 8px;
        background: rgba(244,63,94,0.08);
        border: 1px solid rgba(244,63,94,0.3);
        border-radius: 10px;
        font-size: 11px; color: #fda4af; line-height: 1.5;
    }
    .rj-dz-warn i { margin-top: 2px; }
    .rj-dz-warn-soft { background: rgba(245,158,11,0.08); border-color: rgba(245,158,11,0.3); color: #fcd34d; }

    .rj-dz-side-total {
        padding: 16px 0;
        border-top: 1px solid rgba(255,255,255,0.06);
        margin-bottom: 16px;
    }
    .rj-dz-total-row {
        display: flex; justify-content: space-between;
        font-size: 12px; color: #9ca3af;
        padding: 4px 0; font-family: ui-monospace, monospace;
    }
    .rj-dz-total-grand {
        font-size: 18px; font-weight: 800;
        color: #fff; padding-top: 12px; margin-top: 8px;
        border-top: 1px solid rgba(255,255,255,0.06);
    }

    .rj-dz-add {
        width: 100%;
        display: inline-flex; align-items: center; justify-content: center;
        gap: 10px; padding: 16px;
        background: linear-gradient(135deg, #6366f1, #a855f7, #ec4899);
        border: none; border-radius: 12px;
        color: #fff; font-size: 14px; font-weight: 700;
        cursor: pointer; font-family: inherit; transition: all .3s;
        box-shadow: 0 10px 30px -10px rgba(168,85,247,0.5);
    }
    .rj-dz-add:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 16px 40px -10px rgba(168,85,247,0.7); }
    .rj-dz-add:disabled { opacity: .4; cursor: not-allowed; transform: none; box-shadow: none; }

    @media (max-width: 1100px) { .rj-dz-left { display: none !important; } }
    @media (max-width: 900px) {
        .rj-dz { height: calc(100vh - 64px); }
        .rj-dz-sidebar { width: 260px; }
        .rj-dz-tb-btn span { display: none; }
    }
    @media (max-width: 700px) {
        .rj-dz { height: auto; min-height: 100vh; overflow: visible; }
        .rj-dz-body { flex-direction: column; overflow: visible; }
        .rj-dz-stage-wrap { min-height: 55vh; flex: none; }
        .rj-dz-sidebar { width: 100%; border-left: none; border-top: 1px solid rgba(255,255,255,0.06); }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script>
(function () {
    var DPI = 60;                 // canvas pixels per inch (editing resolution)
    var TARGET_DPI = 300;         // print resolution used for default placement / export
    var LOW_DPI = 100;
    var GOOD_DPI = 150;
    var MARGIN_IN = 0.2;
    var GAP_IN = 0.2;
    var MAX_FILE_MB = 30;
    var HISTORY_LIMIT = 40;
    var MAX_EXPORT_PIXELS = 16000000;
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
        return new URLSearchParams(window.location.search).get(name);
    }

    function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

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
        var pricing = config.pricing || { enabled: true, per_sq_inch: 0.05, min_price: 4.50, min_inch: 1, max_inch: 60 };

        // Non-reactive internals (Fabric objects must NOT be wrapped by Alpine's proxy)
        var canvas = null;
        var hist = [];
        var hIndex = -1;
        var restoring = false;
        var cornerPx = 14;
        var nudgeTimer = null;
        var resizeObs = null;

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

            itemCount: 0,
            outCount: 0,
            lowDpiCount: 0,
            hasActive: false,
            single: false,
            lockRatio: true,
            sel: { w: '', h: '', angle: 0, opacity: 100, dpi: 0 },
            canUndo: false,
            canRedo: false,
            bgMode: 'white',
            dragging: false,

            bgWorking: false,
            adding: false,
            zoomLevel: 1,
            fitScale: 1,
            ready: false,

            /* ------------------------------------------------------------ init */
            init() {
                var self = this;
                this.buildProductOptions();
                this.buildZoneOptions();

                this.$nextTick(function () {
                    self.setupCanvas();
                    self.bindGlobalEvents();
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
                    this.customW = clamp(w, this.pricing.min_inch, this.pricing.max_inch);
                    this.customH = clamp(h, this.pricing.min_inch, this.pricing.max_inch);
                    this.isCustom = true;
                    if (this.$refs.zoneSelect) this.$refs.zoneSelect.value = CUSTOM_ID;
                    this.applyZoneSize();
                }
            },

            /* ------------------------------------------------------------ pricing */
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
                var calculated = this.customW * this.customH * perSqInch;
                return Math.round(Math.max(calculated, minPrice) * 100) / 100;
            },

            get sheetPrice() { return this.currentZone ? (Number(this.currentZone.price_addon) || 0) : 0; },
            get productPrice() { return this.product ? Number(this.product.base_price) || 0 : 0; },
            get basePrice() { return this.sheetPrice + this.productPrice; },
            get totalPrice() { return this.basePrice * (this.qty || 1); },

            get dpiClass() {
                var d = Number(this.sel.dpi) || 0;
                return d >= GOOD_DPI ? 'ok' : (d >= LOW_DPI ? 'warn' : 'bad');
            },

            /* ------------------------------------------------------------ canvas setup */
            setupCanvas() {
                var el = document.getElementById('designCanvas');
                if (!el || typeof fabric === 'undefined') return;

                var self = this;

                canvas = new fabric.Canvas('designCanvas', {
                    preserveObjectStacking: true,
                    selection: true,
                    enableRetinaScaling: false,
                    stopContextMenu: true,
                    fireRightClick: false
                });

                fabric.Object.prototype.set({
                    transparentCorners: false,
                    cornerColor: '#6366f1',
                    cornerStrokeColor: '#ffffff',
                    borderColor: '#6366f1',
                    cornerStyle: 'circle'
                });

                canvas.on('selection:created', function () { self.syncActive(); });
                canvas.on('selection:updated', function () { self.syncActive(); });
                canvas.on('selection:cleared', function () { self.syncActive(); });

                canvas.on('object:added', function () { if (!restoring) self.syncCount(); });
                canvas.on('object:removed', function () { if (!restoring) self.syncCount(); });

                canvas.on('object:scaling', function () { self.updateSel(); });
                canvas.on('object:rotating', function () { self.updateSel(); });
                canvas.on('object:moving', function () { self.refreshStats(); });
                canvas.on('object:modified', function () { self.commit(); });

                this.applyZoneSize();
                this.pushHistory();
            },

            bindGlobalEvents() {
                var self = this;
                var wrap = this.$refs.wrap;

                if (window.ResizeObserver && wrap) {
                    resizeObs = new ResizeObserver(function () { self.fitStage(); });
                    resizeObs.observe(wrap);
                } else {
                    window.addEventListener('resize', function () { self.fitStage(); });
                }

                // Ctrl/Cmd + wheel = zoom
                if (wrap) {
                    wrap.addEventListener('wheel', function (e) {
                        if (!(e.ctrlKey || e.metaKey)) return;
                        e.preventDefault();
                        self.zoom(e.deltaY < 0 ? 1 : -1);
                    }, { passive: false });
                }

                // Keyboard shortcuts
                document.addEventListener('keydown', function (e) {
                    var t = e.target;
                    if (t && (/^(INPUT|TEXTAREA|SELECT)$/.test(t.tagName) || t.isContentEditable)) return;
                    if (!canvas) return;

                    var mod = e.ctrlKey || e.metaKey;
                    var k = (e.key || '').toLowerCase();

                    if (mod && k === 'z') { e.preventDefault(); e.shiftKey ? self.redo() : self.undo(); return; }
                    if (mod && k === 'y') { e.preventDefault(); self.redo(); return; }
                    if (mod && k === 'd') { e.preventDefault(); self.duplicate(); return; }

                    if ((k === 'delete' || k === 'backspace') && self.hasActive) {
                        e.preventDefault(); self.removeActive(); return;
                    }

                    var step = e.shiftKey ? 30 : 3;
                    var dx = 0, dy = 0;
                    if (k === 'arrowleft') dx = -step;
                    else if (k === 'arrowright') dx = step;
                    else if (k === 'arrowup') dy = -step;
                    else if (k === 'arrowdown') dy = step;
                    else return;

                    if (!self.hasActive) return;
                    e.preventDefault();
                    self.moveBy(dx, dy);
                    clearTimeout(nudgeTimer);
                    nudgeTimer = setTimeout(function () { self.commit(); }, 400);
                });

                // Paste image from clipboard
                window.addEventListener('paste', function (e) {
                    var items = (e.clipboardData && e.clipboardData.items) || [];
                    var files = [];
                    for (var i = 0; i < items.length; i++) {
                        if (items[i].kind === 'file' && items[i].type.indexOf('image/') === 0) {
                            var f = items[i].getAsFile();
                            if (f) files.push(f);
                        }
                    }
                    if (files.length) { e.preventDefault(); self.addFiles(files); }
                });
            },

            /* ------------------------------------------------------------ sync / stats */
            syncActive() {
                var o = canvas ? canvas.getActiveObject() : null;
                this.hasActive = !!o;
                this.single = !!(o && o.type === 'image');
                if (this.single) {
                    this.applyLockToObject(o);
                    this.updateSel();
                }
            },

            syncCount() {
                if (!canvas) return;
                this.itemCount = canvas.getObjects().filter(function (o) { return o.type === 'image'; }).length;
                this.refreshStats();
            },

            updateSel() {
                var o = canvas ? canvas.getActiveObject() : null;
                if (!o || o.type !== 'image') return;
                var wIn = o.getScaledWidth() / DPI;
                var hIn = o.getScaledHeight() / DPI;
                this.sel = {
                    w: wIn.toFixed(2),
                    h: hIn.toFixed(2),
                    angle: Math.round(((o.angle % 360) + 360) % 360),
                    opacity: Math.round((o.opacity == null ? 1 : o.opacity) * 100),
                    dpi: wIn > 0 ? Math.round(o.width / wIn) : 0
                };
                this.refreshStats();
            },

            refreshStats() {
                if (!canvas) return;
                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var out = 0, low = 0;

                canvas.getObjects().forEach(function (o) {
                    if (o.type !== 'image') return;
                    var r = o.getBoundingRect(true, true);
                    if (r.left < -1 || r.top < -1 || r.left + r.width > cw + 1 || r.top + r.height > ch + 1) out++;
                    var inch = o.getScaledWidth() / DPI;
                    if (inch > 0 && (o.width / inch) < LOW_DPI) low++;
                });

                this.outCount = out;
                this.lowDpiCount = low;
            },

            commit() {
                this.updateSel();
                this.refreshStats();
                this.pushHistory();
            },

            /* ------------------------------------------------------------ history (undo / redo) */
            snapshot() {
                return canvas.getObjects().map(function (o) {
                    return {
                        o: o,
                        p: {
                            left: o.left, top: o.top, scaleX: o.scaleX, scaleY: o.scaleY,
                            angle: o.angle, flipX: o.flipX, flipY: o.flipY, opacity: o.opacity
                        }
                    };
                });
            },

            pushHistory() {
                if (!canvas || restoring) return;
                hist = hist.slice(0, hIndex + 1);
                hist.push(this.snapshot());
                if (hist.length > HISTORY_LIMIT) hist.shift();
                hIndex = hist.length - 1;
                this.canUndo = hIndex > 0;
                this.canRedo = false;
            },

            restore(state) {
                restoring = true;
                canvas.discardActiveObject();
                canvas.getObjects().slice().forEach(function (o) { canvas.remove(o); });
                state.forEach(function (s) {
                    s.o.set(s.p);
                    s.o.setCoords();
                    canvas.add(s.o);
                });
                restoring = false;
                canvas.renderAll();
                this.syncActive();
                this.syncCount();
            },

            undo() {
                if (hIndex <= 0) return;
                hIndex--;
                this.restore(hist[hIndex]);
                this.canUndo = hIndex > 0;
                this.canRedo = true;
            },

            redo() {
                if (hIndex >= hist.length - 1) return;
                hIndex++;
                this.restore(hist[hIndex]);
                this.canUndo = true;
                this.canRedo = hIndex < hist.length - 1;
            },

            /* ------------------------------------------------------------ sheet size */
            applyZoneSize() {
                var z = this.currentZone;
                if (!z || !canvas) return;

                var newW = Math.max(1, Math.round(Number(z.width_inch) * DPI));
                var newH = Math.max(1, Math.round(Number(z.height_inch) * DPI));

                if (newW !== canvas.getWidth() || newH !== canvas.getHeight()) {
                    // Items keep their physical size and absolute position (no more drifting);
                    // anything that no longer fits is flagged by refreshStats().
                    canvas.setDimensions({ width: newW, height: newH });
                    canvas.renderAll();
                }

                this.fitStage();
                this.refreshStats();
            },

            fitStage() {
                var stage = this.$refs.stage;
                if (!stage || !canvas) return;
                var wrap = stage.parentElement;
                if (!wrap) return;

                var pad = 48;
                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                if (cw <= 0 || ch <= 0) return;

                var fit = Math.min((wrap.clientWidth - pad) / cw, (wrap.clientHeight - pad) / ch, 2);
                fit = Math.max(fit, 0.05);
                this.fitScale = fit;

                var scale = fit * this.zoomLevel;
                stage.style.width = (cw * scale) + 'px';
                stage.style.height = (ch * scale) + 'px';

                var container = canvas.wrapperEl;
                if (container) {
                    container.style.transformOrigin = '0 0';
                    container.style.transform = 'scale(' + scale + ')';
                }

                // keep handles a comfortable on-screen size at any zoom
                cornerPx = clamp(14 / scale, 10, 70);
                var self = this;
                canvas.getObjects().forEach(function (o) { self.applyHandleSize(o); });
                canvas.requestRenderAll();
            },

            applyHandleSize(o) {
                var scale = this.fitScale * this.zoomLevel || 1;
                o.set({
                    cornerSize: cornerPx,
                    touchCornerSize: cornerPx * 1.8,
                    borderScaleFactor: Math.max(1, 1.5 / scale)
                });
            },

            changeZone(e) {
                var val = e && e.target ? e.target.value : this.zoneId;

                if (val === CUSTOM_ID) {
                    this.isCustom = true;
                    this.zoneId = null;
                    if (this.customW > 0 && this.customH > 0) this.applyZoneSize();
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

                this.customW = clamp(this.customW, min, max);
                this.customH = clamp(this.customH, min, max);

                this.isCustom = true;
                this.zoneId = null;
                if (this.$refs.zoneSelect) this.$refs.zoneSelect.value = CUSTOM_ID;

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

            /* ------------------------------------------------------------ uploading */
            onFiles(e) {
                this.addFiles(Array.from(e.target.files || []));
                e.target.value = '';
            },

            onDrop(e) {
                this.dragging = false;
                var files = Array.from((e.dataTransfer && e.dataTransfer.files) || []);
                this.addFiles(files);
            },

            addFiles(files) {
                var self = this;
                files.forEach(function (f) {
                    if (!/^image\/(png|jpe?g|webp)$/i.test(f.type)) {
                        self.toast('Only PNG, JPG or WEBP images are supported');
                        return;
                    }
                    if (f.size > MAX_FILE_MB * 1024 * 1024) {
                        self.toast(f.name + ' is larger than ' + MAX_FILE_MB + 'MB');
                        return;
                    }
                    var reader = new FileReader();
                    reader.onload = function (ev) { self.addImageFromSrc(ev.target.result); };
                    reader.readAsDataURL(f);
                });
            },

            addImageFromSrc(src, replaceObj) {
                if (!canvas) return;
                var self = this;

                fabric.Image.fromURL(src, function (img) {
                    if (!img || !img.width) { self.toast('Could not load image'); return; }

                    var cw = canvas.getWidth();
                    var ch = canvas.getHeight();

                    // default: place at TARGET_DPI, but never larger than 60% of the sheet
                    var scale = Math.min(DPI / TARGET_DPI, (cw * 0.6) / img.width, (ch * 0.6) / img.height);
                    var cascade = replaceObj ? 0 : (self.itemCount % 6) * DPI * 0.15;

                    img.set({
                        originX: 'center',
                        originY: 'center',
                        left: cw / 2 + cascade,
                        top: ch / 2 + cascade,
                        scaleX: scale,
                        scaleY: scale
                    });

                    if (replaceObj) {
                        var idx = canvas.getObjects().indexOf(replaceObj);
                        var dispW = replaceObj.getScaledWidth();
                        var dispH = replaceObj.getScaledHeight();
                        img.set({
                            left: replaceObj.left,
                            top: replaceObj.top,
                            scaleX: dispW / img.width,
                            scaleY: dispH / img.height,
                            angle: replaceObj.angle,
                            flipX: replaceObj.flipX,
                            flipY: replaceObj.flipY,
                            opacity: replaceObj.opacity
                        });
                        canvas.remove(replaceObj);
                        self.applyHandleSize(img);
                        canvas.add(img);
                        if (idx > -1) canvas.moveTo(img, idx);
                    } else {
                        self.applyHandleSize(img);
                        canvas.add(img);
                    }

                    canvas.setActiveObject(img);
                    canvas.renderAll();
                    self.syncActive();
                    self.syncCount();
                    self.pushHistory();
                }, { crossOrigin: 'anonymous' });
            },

            async removeBg() {
                var active = canvas ? canvas.getActiveObject() : null;
                if (!active || active.type !== 'image') return;

                this.bgWorking = true;

                try {
                    var removeFn = await loadRemoveBg();
                    var blob = await removeFn(active.getSrc());
                    // blob URL is intentionally not revoked: undo/redo may bring this image back
                    var url = URL.createObjectURL(blob);
                    this.addImageFromSrc(url, active);
                } catch (e) {
                    this.toast('Background removal failed');
                }

                this.bgWorking = false;
            },

            /* ------------------------------------------------------------ object tools */
            activeObjs() { return canvas ? canvas.getActiveObjects() : []; },

            flipH() {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                a.set('flipX', !a.flipX);
                canvas.renderAll();
                this.pushHistory();
            },

            rotate(deg) {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                a.rotate((((a.angle || 0) + deg) % 360 + 360) % 360);
                a.setCoords();
                canvas.renderAll();
                this.commit();
            },

            setAngle(v) {
                var a = canvas ? canvas.getActiveObject() : null;
                v = parseFloat(v);
                if (!a || isNaN(v)) { this.updateSel(); return; }
                a.rotate(((v % 360) + 360) % 360);
                a.setCoords();
                canvas.renderAll();
                this.commit();
            },

            setOpacity(v) {
                var a = canvas ? canvas.getActiveObject() : null;
                v = parseFloat(v);
                if (!a || isNaN(v)) return;
                a.set('opacity', clamp(v, 10, 100) / 100);
                canvas.renderAll();
                this.commit();
            },

            // --- numeric resize (inches) ---
            setSize(dim, val) {
                var o = canvas ? canvas.getActiveObject() : null;
                if (!o || o.type !== 'image') return;

                val = parseFloat(val);
                if (!(val > 0)) { this.updateSel(); return; }
                val = clamp(val, 0.1, 200);

                var px = val * DPI;
                var sx = o.scaleX, sy = o.scaleY;

                if (dim === 'w') {
                    sx = px / o.width;
                    sy = this.lockRatio ? sx : o.scaleY;
                } else {
                    sy = px / o.height;
                    sx = this.lockRatio ? sy : o.scaleX;
                }

                o.set({ scaleX: sx, scaleY: sy });
                o.setCoords();
                canvas.renderAll();
                this.commit();
            },

            toggleLock() {
                this.lockRatio = !this.lockRatio;
                var o = canvas ? canvas.getActiveObject() : null;
                if (o && o.type === 'image') {
                    this.applyLockToObject(o);
                    canvas.renderAll();
                }
            },

            applyLockToObject(o) {
                // when locked, hide the side handles so only proportional corner scaling remains
                var free = !this.lockRatio;
                o.setControlsVisibility({ ml: free, mr: free, mt: free, mb: free });
            },

            fitToSheet() {
                var o = canvas ? canvas.getActiveObject() : null;
                if (!o || o.type !== 'image') return;

                var m = MARGIN_IN * DPI;
                var cw = canvas.getWidth() - m * 2;
                var ch = canvas.getHeight() - m * 2;

                // account for rotation by fitting against the unrotated size ratio, then verifying
                var s = Math.min(cw / o.width, ch / o.height);
                o.set({ scaleX: s, scaleY: s });
                o.setCoords();

                // shrink further if rotation makes the bounding box larger than the sheet
                var r = o.getBoundingRect(true, true);
                var k = Math.min(cw / r.width, ch / r.height, 1);
                if (k < 1) { o.set({ scaleX: s * k, scaleY: s * k }); o.setCoords(); }

                o.set({ left: canvas.getWidth() / 2, top: canvas.getHeight() / 2 });
                o.setCoords();
                canvas.renderAll();
                this.commit();
            },

            moveBy(dx, dy) {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                a.set({ left: a.left + dx, top: a.top + dy });
                a.setCoords();
                canvas.renderAll();
                this.refreshStats();
            },

            center(axis) {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                var r = a.getBoundingRect(true, true);
                if (axis === 'h') this.moveBy(canvas.getWidth() / 2 - (r.left + r.width / 2), 0);
                else this.moveBy(0, canvas.getHeight() / 2 - (r.top + r.height / 2));
                this.commit();
            },

            layerUp() {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                canvas.bringForward(a);
                canvas.renderAll();
                this.pushHistory();
            },

            layerDown() {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                canvas.sendBackwards(a);
                canvas.renderAll();
                this.pushHistory();
            },

            duplicate() {
                if (!canvas) return;
                var objs = this.activeObjs();
                if (!objs.length) return;

                var self = this;
                canvas.discardActiveObject();

                var off = DPI * 0.2;
                var left = objs.length;
                var clones = [];

                objs.forEach(function (o) {
                    o.clone(function (c) {
                        c.set({ left: o.left + off, top: o.top + off });
                        self.applyHandleSize(c);
                        canvas.add(c);
                        clones.push(c);
                        left--;
                        if (left === 0) {
                            if (clones.length === 1) {
                                canvas.setActiveObject(clones[0]);
                            } else {
                                canvas.setActiveObject(new fabric.ActiveSelection(clones, { canvas: canvas }));
                            }
                            canvas.renderAll();
                            self.syncActive();
                            self.syncCount();
                            self.pushHistory();
                        }
                    });
                });
            },

            removeActive() {
                if (!canvas) return;
                var objs = this.activeObjs();
                if (!objs.length) return;

                canvas.discardActiveObject();
                objs.forEach(function (o) { canvas.remove(o); });
                canvas.renderAll();
                this.syncActive();
                this.syncCount();
                this.pushHistory();
            },

            clearAll() {
                if (!canvas || !this.itemCount) return;
                if (!window.confirm('Remove all items from the sheet?')) return;
                canvas.discardActiveObject();
                canvas.getObjects().slice().forEach(function (o) { canvas.remove(o); });
                canvas.renderAll();
                this.syncActive();
                this.syncCount();
                this.pushHistory();
            },

            // Simple shelf packing: tallest first, left to right, new row when full
            autoArrange() {
                if (!canvas) return;
                canvas.discardActiveObject();

                var objs = canvas.getObjects().filter(function (o) { return o.type === 'image'; });
                if (objs.length < 2) return;

                var cw = canvas.getWidth();
                var m = MARGIN_IN * DPI;
                var gap = GAP_IN * DPI;

                var sorted = objs.slice().sort(function (a, b) {
                    return b.getBoundingRect(true, true).height - a.getBoundingRect(true, true).height;
                });

                var x = m, y = m, rowH = 0;
                sorted.forEach(function (o) {
                    var r = o.getBoundingRect(true, true);
                    if (x + r.width > cw - m && x > m) {
                        x = m;
                        y += rowH + gap;
                        rowH = 0;
                    }
                    o.set({ left: o.left + (x - r.left), top: o.top + (y - r.top) });
                    o.setCoords();
                    x += r.width + gap;
                    rowH = Math.max(rowH, r.height);
                });

                canvas.renderAll();
                this.syncActive();
                this.commit();

                if (this.outCount > 0) {
                    this.toast('Not everything fits — choose a larger sheet');
                }
            },

            /* ------------------------------------------------------------ view */
            zoom(dir) {
                this.zoomLevel = clamp(this.zoomLevel + dir * 0.15, 0.25, 4);
                this.fitStage();
            },

            resetView() {
                this.zoomLevel = 1;
                this.fitStage();
                var wrap = this.$refs.wrap;
                if (wrap) { wrap.scrollLeft = 0; wrap.scrollTop = 0; }
            },

            toggleBg() {
                this.bgMode = this.bgMode === 'white' ? 'checker' : 'white';
            },

            incQty() { this.qty = Math.min(999, (Number(this.qty) || 1) + 1); },
            decQty() { this.qty = Math.max(1, (Number(this.qty) || 1) - 1); },
            normalizeQty() { this.qty = clamp(Math.round(Number(this.qty)) || 1, 1, 999); },

            /* ------------------------------------------------------------ export */
            exportCanvas(multiplier, bg) {
                canvas.discardActiveObject();
                var prev = canvas.backgroundColor;
                canvas.backgroundColor = bg || '';
                canvas.renderAll();
                var el = canvas.toCanvasElement(multiplier);
                canvas.backgroundColor = prev;
                canvas.renderAll();
                this.syncActive();
                return el;
            },

            downloadPng() {
                if (!canvas || !this.itemCount) return;

                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var target = TARGET_DPI / DPI;
                var cap = Math.sqrt(MAX_EXPORT_PIXELS / (cw * ch));
                var mult = Math.max(1, Math.min(target, cap));

                var z = this.currentZone;
                var name = 'gang-sheet-' + (z ? (z.width_inch + 'x' + z.height_inch + 'in') : 'custom') + '.png';
                var self = this;

                try {
                    var el = this.exportCanvas(mult, '');
                    el.toBlob(function (blob) {
                        if (!blob) { self.toast('Export failed'); return; }
                        var a = document.createElement('a');
                        a.href = URL.createObjectURL(blob);
                        a.download = name;
                        document.body.appendChild(a);
                        a.click();
                        a.remove();
                        setTimeout(function () { URL.revokeObjectURL(a.href); }, 5000);
                        self.toast('Downloaded (' + Math.round(mult * DPI) + ' DPI)');
                    }, 'image/png');
                } catch (e) {
                    this.toast('Export failed — try a smaller sheet');
                }
            },

            /* ------------------------------------------------------------ cart */
            async addToCart() {
                if (this.itemCount === 0 || this.adding) return;

                if (this.outCount > 0 || this.lowDpiCount > 0) {
                    var msg = [];
                    if (this.outCount > 0) msg.push(this.outCount + ' item(s) extend beyond the sheet and will be cut off');
                    if (this.lowDpiCount > 0) msg.push(this.lowDpiCount + ' item(s) are below ' + LOW_DPI + ' DPI and may print blurry');
                    if (!window.confirm(msg.join('\n') + '\n\nAdd to cart anyway?')) return;
                }

                this.adding = true;

                var z = this.currentZone;
                var snapshot = null;

                try {
                    var mult = Math.min(1, 1200 / Math.max(canvas.getWidth(), canvas.getHeight()));
                    snapshot = this.exportCanvas(mult, '#ffffff').toDataURL('image/jpeg', 0.8);
                } catch (e) {
                    snapshot = null;
                }

                var attributes = {
                    'Sheet Size': z ? (z.width_inch + ' × ' + z.height_inch + ' in') : 'Custom',
                    'Items': this.itemCount
                };

                if (this.isCustom) attributes['Custom'] = 'yes';

                var payload = {
                    product_id: this.product ? this.product.id : null,
                    name: this.product ? this.product.name : ('Custom Gang Sheet — ' + (z ? z.label : 'Custom')),
                    unit_price: this.basePrice,
                    qty: this.qty,
                    attributes: attributes,
                    print_type: 'custom_size',
                    note: this.itemCount + ' design item(s) · ' + (z ? (z.width_inch + '×' + z.height_inch + ' in') : ''),
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
                el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#0a0715;color:#fff;padding:12px 24px;border-radius:9999px;border:1px solid rgba(99,102,241,0.4);font-size:13px;font-weight:600;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.6);max-width:90vw;text-align:center;';
                document.body.appendChild(el);
                setTimeout(function () { el.remove(); }, 2400);
            }
        };
    };
})();
</script>

@endsection

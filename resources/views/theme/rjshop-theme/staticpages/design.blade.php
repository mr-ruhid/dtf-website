@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Design Studio')
@section('meta_description', 'Build your gang sheet')

@section('content')

<section id="rjHero"
         class="rj-dz"
         x-data="designStudio({
            zones: {{ \Illuminate\Support\Js::from($zones) }},
            product: {{ \Illuminate\Support\Js::from($product) }}
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
                    <select class="rj-dz-select" x-model="zoneId" @change="changeZone()">
                        <template x-for="z in zones" :key="z.id">
                            <option :value="z.id" x-text="z.label"></option>
                        </template>
                    </select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <p class="rj-dz-hint" x-text="currentZone ? (currentZone.width_inch + ' × ' + currentZone.height_inch + ' in') : ''"></p>
            </div>

            <div class="rj-dz-side-block" x-show="product" x-cloak>
                <label class="rj-dz-label">Product</label>
                <div class="rj-dz-product">
                    <template x-if="product && product.image">
                        <img :src="product.image" :alt="product.name">
                    </template>
                    <div>
                        <p class="rj-dz-product-name" x-text="product ? product.name : ''"></p>
                        <p class="rj-dz-product-meta" x-text="product ? ('Base $' + Number(product.base_price).toFixed(2)) : ''"></p>
                    </div>
                </div>
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
                    <span x-text="'$' + basePrice.toFixed(2)"></span>
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
                <span x-text="adding ? 'Adding...' : 'Add to Cart'"></span>
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
        font-family: inherit;
        white-space: nowrap;
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
        width: 320px; flex-shrink: 0;
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

    .rj-dz-product {
        display: flex; gap: 12px; align-items: center;
        padding: 10px;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 10px;
    }
    .rj-dz-product img {
        width: 44px; height: 44px; border-radius: 8px;
        object-fit: cover;
        border: 1px solid rgba(255,255,255,0.08);
        flex-shrink: 0;
    }
    .rj-dz-product-name {
        font-size: 13px; font-weight: 600; color: #fff;
        margin: 0 0 2px; line-height: 1.3;
    }
    .rj-dz-product-meta {
        font-family: ui-monospace, monospace;
        font-size: 10px; color: #818cf8;
        margin: 0; letter-spacing: .05em;
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

    @media (max-width: 900px) {
        .rj-dz { height: calc(100vh - 64px); }
        .rj-dz-sidebar { width: 260px; }
        .rj-dz-tb-btn span { display: none; }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<script type="module">
import removeBg from 'https://esm.sh/@imgly/background-removal@1.4.5';

const DPI = 60;

const FALLBACK_ZONES = [
    { id: 1, name: 'A4', slug: 'a4', width_inch: 8.3, height_inch: 11.7, label: 'A4 (8.3 × 11.7 in)', price_addon: 4.50 },
    { id: 2, name: 'A3', slug: 'a3', width_inch: 11.7, height_inch: 16.5, label: 'A3 (11.7 × 16.5 in)', price_addon: 7.50 },
    { id: 3, name: '12 × 12', slug: '12x12', width_inch: 12, height_inch: 12, label: '12 × 12 in', price_addon: 6.00 },
    { id: 4, name: '12 × 24', slug: '12x24', width_inch: 12, height_inch: 24, label: '12 × 24 in', price_addon: 10.00 },
    { id: 5, name: '13 × 19', slug: '13x19', width_inch: 13, height_inch: 19, label: '13 × 19 in', price_addon: 9.00 },
    { id: 6, name: '22 × 24', slug: '22x24', width_inch: 22, height_inch: 24, label: '22 × 24 in', price_addon: 18.00 },
];

window.designStudio = function (config) {
    const zones = (config.zones && config.zones.length) ? config.zones : FALLBACK_ZONES;

    return {
        zones: zones,
        product: config.product || null,
        zoneId: zones[0].id,
        qty: 1,
        items: [],
        hasActiveImage: false,
        bgWorking: false,
        adding: false,
        zoomLevel: 1,
        fitScale: 1,
        canvas: null,

        init() {
            this.$nextTick(() => this.setupCanvas());
        },

        get currentZone() {
            if (!this.zoneId) return this.zones[0] || null;
            const found = this.zones.find(z => Number(z.id) === Number(this.zoneId));
            return found || this.zones[0] || null;
        },

        get basePrice() {
            const zoneAddon = this.currentZone ? Number(this.currentZone.price_addon) : 0;
            const productBase = this.product ? Number(this.product.base_price) : 0;
            return zoneAddon + productBase;
        },

        get totalPrice() {
            return this.basePrice * (this.qty || 1);
        },

        setupCanvas() {
            const el = document.getElementById('designCanvas');
            if (!el || typeof fabric === 'undefined') return;

            this.canvas = new fabric.Canvas('designCanvas', {
                backgroundColor: '#ffffff',
                preserveObjectStacking: true,
                selection: true,
            });

            this.canvas.on('selection:created', () => this.syncActive());
            this.canvas.on('selection:updated', () => this.syncActive());
            this.canvas.on('selection:cleared', () => this.syncActive());
            this.canvas.on('object:added', () => this.syncCount());
            this.canvas.on('object:removed', () => this.syncCount());

            this.applyZoneSize();
            window.addEventListener('resize', () => this.fitStage());
        },

        syncActive() {
            const obj = this.canvas ? this.canvas.getActiveObject() : null;
            this.hasActiveImage = !!(obj && obj.type === 'image');
        },

        syncCount() {
            this.items = this.canvas ? this.canvas.getObjects().filter(o => o.type === 'image') : [];
        },

        applyZoneSize() {
            const z = this.currentZone;
            if (!z || !this.canvas) return;

            const w = Math.max(1, Math.round(Number(z.width_inch) * DPI));
            const h = Math.max(1, Math.round(Number(z.height_inch) * DPI));

            this.canvas.setWidth(w);
            this.canvas.setHeight(h);
            this.canvas.renderAll();
            this.fitStage();
        },

        fitStage() {
            const stage = this.$refs.stage;
            if (!stage || !this.canvas) return;

            const wrap = stage.parentElement;
            if (!wrap) return;

            const pad = 48;
            const maxW = wrap.clientWidth - pad;
            const maxH = wrap.clientHeight - pad;
            const cw = this.canvas.getWidth();
            const ch = this.canvas.getHeight();

            if (cw <= 0 || ch <= 0) return;

            const fit = Math.min(maxW / cw, maxH / ch, 1);
            this.fitScale = fit;
            stage.style.transform = `scale(${fit * this.zoomLevel})`;
        },

        changeZone() {
            this.applyZoneSize();
            this.canvas.getObjects().slice().forEach(o => this.canvas.remove(o));
            this.syncCount();
            this.syncActive();
        },

        onFiles(e) {
            const files = Array.from(e.target.files || []);
            files.forEach(f => this.addImageFromFile(f));
            e.target.value = '';
        },

        addImageFromFile(file) {
            const reader = new FileReader();
            reader.onload = ev => this.addImageFromSrc(ev.target.result);
            reader.readAsDataURL(file);
        },

        addImageFromSrc(src, replaceObj = null) {
            if (!this.canvas) return;

            fabric.Image.fromURL(src, (img) => {
                const cw = this.canvas.getWidth();
                const ch = this.canvas.getHeight();

                const maxW = cw * 0.6;
                const maxH = ch * 0.6;
                const scale = Math.min(maxW / img.width, maxH / img.height, 1);

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
                    transparentCorners: false,
                });

                if (replaceObj) {
                    img.set({
                        left: replaceObj.left,
                        top: replaceObj.top,
                        scaleX: replaceObj.scaleX,
                        scaleY: replaceObj.scaleY,
                        angle: replaceObj.angle,
                    });
                    this.canvas.remove(replaceObj);
                }

                this.canvas.add(img);
                this.canvas.setActiveObject(img);
                this.canvas.renderAll();
                this.syncActive();
                this.syncCount();
            }, { crossOrigin: 'anonymous' });
        },

        async removeBg() {
            const active = this.canvas ? this.canvas.getActiveObject() : null;
            if (!active || active.type !== 'image') return;

            this.bgWorking = true;

            try {
                const src = active.getSrc();
                const blob = await removeBg(src);
                const url = URL.createObjectURL(blob);
                this.addImageFromSrc(url, active);
            } catch (e) {
                this.toast('Background removal failed');
            }

            this.bgWorking = false;
        },

        flipH() {
            const a = this.canvas ? this.canvas.getActiveObject() : null;
            if (!a) return;
            a.set('flipX', !a.flipX);
            this.canvas.renderAll();
        },

        rotate(deg) {
            const a = this.canvas ? this.canvas.getActiveObject() : null;
            if (!a) return;
            a.rotate(((a.angle || 0) + deg) % 360);
            this.canvas.renderAll();
        },

        layerUp() {
            const a = this.canvas ? this.canvas.getActiveObject() : null;
            if (!a) return;
            this.canvas.bringForward(a);
            this.canvas.renderAll();
        },

        layerDown() {
            const a = this.canvas ? this.canvas.getActiveObject() : null;
            if (!a) return;
            this.canvas.sendBackwards(a);
            this.canvas.renderAll();
        },

        removeActive() {
            const a = this.canvas ? this.canvas.getActiveObject() : null;
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

            const z = this.currentZone;
            const snapshot = this.canvas.toDataURL({ format: 'jpeg', quality: 0.7 });

            const attributes = {
                'Sheet Size': z ? z.label : 'Custom',
                'Items': this.items.length,
            };

            const payload = {
                product_id: this.product ? this.product.id : null,
                name: this.product ? this.product.name : ('Custom Gang Sheet — ' + (z ? z.label : 'Custom')),
                unit_price: this.basePrice,
                qty: this.qty,
                attributes: attributes,
                print_type: 'custom_size',
                note: this.items.length + ' design item(s) on sheet',
                image: snapshot,
            };

            try {
                const store = window.Alpine && window.Alpine.store('cart');
                if (store) {
                    await store.add(payload);
                } else {
                    this.toast('Cart not ready');
                }
            } catch (e) {
                this.toast('Could not add to cart');
            }

            this.adding = false;
        },

        toast(msg) {
            const el = document.createElement('div');
            el.textContent = msg;
            el.style.cssText = 'position:fixed;bottom:24px;left:50%;transform:translateX(-50%);background:#0a0715;color:#fff;padding:12px 24px;border-radius:9999px;border:1px solid rgba(99,102,241,0.4);font-size:13px;font-weight:600;z-index:9999;box-shadow:0 8px 24px rgba(0,0,0,0.6);';
            document.body.appendChild(el);
            setTimeout(() => el.remove(), 2200);
        },
    };
};
</script>

@endsection

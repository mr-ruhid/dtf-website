/*!
 * RJFrame Design Studio
 * Gang sheet editor for RJ Shop (Alpine.js + Fabric.js 5.3.1)
 * Location: public/theme/RJFrame/js/rjframe.js
 */
(function () {
    'use strict';

    var BRAND = 'RJFrame';
    var VERSION = '1.0.0';

    var DPI = 60;              // canvas pixels per inch (working resolution)
    var TARGET_DPI = 300;      // export resolution
    var LOW_DPI = 100;
    var GOOD_DPI = 150;
    var MARGIN_IN = 0.2;
    var GAP_IN = 0.2;
    var GRID_IN = 0.5;
    var MAX_FILE_MB = 30;
    var HISTORY_LIMIT = 40;
    var MAX_EXPORT_PIXELS = 60000000;
    var MAX_FILL = 300;

    // Properties tracked by undo / redo
    var HISTORY_PROPS = [
        'left', 'top', 'scaleX', 'scaleY', 'angle', 'flipX', 'flipY', 'opacity',
        'fill', 'stroke', 'strokeWidth', 'shadow', 'visible', 'paintFirst',
        'lockMovementX', 'lockMovementY', 'lockRotation', 'lockScalingX', 'lockScalingY', 'hasControls',
        'text', 'fontFamily', 'fontSize', 'fontWeight', 'fontStyle', 'underline', 'linethrough',
        'textAlign', 'charSpacing', 'lineHeight', 'rx', 'ry'
    ];

    // Custom properties kept in project files and clones
    var CUSTOM_PROPS = [
        '_rjf', '_rjName', 'paintFirst',
        'lockMovementX', 'lockMovementY', 'lockRotation', 'lockScalingX', 'lockScalingY', 'hasControls'
    ];

    var FONTS = [
        { name: 'Inter', cat: 'Sans', w: 1 },
        { name: 'Poppins', cat: 'Sans', w: 1 },
        { name: 'Montserrat', cat: 'Sans', w: 1 },
        { name: 'Roboto', cat: 'Sans', w: 1 },
        { name: 'Open Sans', cat: 'Sans', w: 1 },
        { name: 'Lato', cat: 'Sans', w: 1 },
        { name: 'Raleway', cat: 'Sans', w: 1 },
        { name: 'Nunito', cat: 'Sans', w: 1 },
        { name: 'Rubik', cat: 'Sans', w: 1 },
        { name: 'Oswald', cat: 'Sans', w: 1 },
        { name: 'Barlow Condensed', cat: 'Sans', w: 1 },

        { name: 'Playfair Display', cat: 'Serif', w: 1 },
        { name: 'Merriweather', cat: 'Serif', w: 1 },
        { name: 'Lora', cat: 'Serif', w: 1 },
        { name: 'Cinzel', cat: 'Serif', w: 1 },
        { name: 'DM Serif Display', cat: 'Serif' },
        { name: 'Abril Fatface', cat: 'Serif' },
        { name: 'Yeseva One', cat: 'Serif' },

        { name: 'Bebas Neue', cat: 'Display' },
        { name: 'Anton', cat: 'Display' },
        { name: 'Archivo Black', cat: 'Display' },
        { name: 'Alfa Slab One', cat: 'Display' },
        { name: 'Staatliches', cat: 'Display' },
        { name: 'Russo One', cat: 'Display' },
        { name: 'Righteous', cat: 'Display' },
        { name: 'Fredoka', cat: 'Display', w: 1 },
        { name: 'Luckiest Guy', cat: 'Display' },
        { name: 'Chewy', cat: 'Display' },
        { name: 'Bangers', cat: 'Display' },
        { name: 'Black Ops One', cat: 'Display' },
        { name: 'Orbitron', cat: 'Display', w: 1 },
        { name: 'Monoton', cat: 'Display' },
        { name: 'Creepster', cat: 'Display' },
        { name: 'Press Start 2P', cat: 'Display' },

        { name: 'Pacifico', cat: 'Script' },
        { name: 'Lobster', cat: 'Script' },
        { name: 'Dancing Script', cat: 'Script', w: 1 },
        { name: 'Great Vibes', cat: 'Script' },
        { name: 'Sacramento', cat: 'Script' },
        { name: 'Satisfy', cat: 'Script' },
        { name: 'Courgette', cat: 'Script' },
        { name: 'Caveat', cat: 'Script', w: 1 },
        { name: 'Permanent Marker', cat: 'Script' },
        { name: 'Shadows Into Light', cat: 'Script' },
        { name: 'Amatic SC', cat: 'Script', w: 1 },

        { name: 'Space Mono', cat: 'Mono', w: 1 },
        { name: 'JetBrains Mono', cat: 'Mono', w: 1 },
        { name: 'Special Elite', cat: 'Mono' },

        { name: 'Arial', cat: 'System', sys: 1 },
        { name: 'Georgia', cat: 'System', sys: 1 },
        { name: 'Times New Roman', cat: 'System', sys: 1 },
        { name: 'Courier New', cat: 'System', sys: 1 }
    ];

    var PALETTE = [
        '#000000', '#ffffff', '#ef4444', '#f97316', '#eab308', '#22c55e',
        '#06b6d4', '#3b82f6', '#6366f1', '#a855f7', '#ec4899', '#78716c'
    ];

    var TEXT_PRESETS = [
        { id: 'heading', label: 'Heading', text: 'HEADING', font: 'Bebas Neue', pt: 72, fill: '#111111' },
        { id: 'sub', label: 'Subheading', text: 'Subheading', font: 'Montserrat', pt: 36, bold: true, fill: '#111111' },
        { id: 'body', label: 'Body text', text: 'Add your text here', font: 'Open Sans', pt: 20, fill: '#111111' },
        { id: 'script', label: 'Script', text: 'Handwritten', font: 'Great Vibes', pt: 64, fill: '#111111' },
        { id: 'outline', label: 'Outline', text: 'OUTLINE', font: 'Anton', pt: 64, fill: '#ffffff', stroke: '#111111', strokeW: 4 },
        { id: 'glow', label: 'Glow', text: 'NEON', font: 'Orbitron', pt: 56, bold: true, fill: '#ffffff', shadow: { color: '#6366f1', blur: 18, off: 0 } },
        { id: 'retro', label: 'Retro', text: 'RETRO', font: 'Alfa Slab One', pt: 56, fill: '#f97316', stroke: '#111111', strokeW: 3, shadow: { color: '#111111', blur: 0, off: 4 } },
        { id: 'stamp', label: 'Stamp', text: 'HANDMADE', font: 'Special Elite', pt: 44, fill: '#b91c1c', spacing: 120 }
    ];

    var SHAPES = [
        { id: 'rect', label: 'Rectangle', svg: '<svg viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" fill="currentColor"/></svg>' },
        { id: 'round', label: 'Rounded', svg: '<svg viewBox="0 0 24 24"><rect x="3" y="6" width="18" height="12" rx="4" fill="currentColor"/></svg>' },
        { id: 'circle', label: 'Circle', svg: '<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" fill="currentColor"/></svg>' },
        { id: 'triangle', label: 'Triangle', svg: '<svg viewBox="0 0 24 24"><polygon points="12,4 21,20 3,20" fill="currentColor"/></svg>' },
        { id: 'star', label: 'Star', svg: '<svg viewBox="0 0 24 24"><polygon points="12,2.5 14.9,9 22,9.6 16.6,14.2 18.3,21 12,17.4 5.7,21 7.4,14.2 2,9.6 9.1,9" fill="currentColor"/></svg>' },
        { id: 'hexagon', label: 'Hexagon', svg: '<svg viewBox="0 0 24 24"><polygon points="12,2.5 20.5,7.2 20.5,16.8 12,21.5 3.5,16.8 3.5,7.2" fill="currentColor"/></svg>' },
        { id: 'heart', label: 'Heart', svg: '<svg viewBox="0 0 24 24"><path d="M12 21 C3 14 2 8 6 5.5 C9 3.8 11 5.5 12 7.5 C13 5.5 15 3.8 18 5.5 C22 8 21 14 12 21Z" fill="currentColor"/></svg>' },
        { id: 'line', label: 'Line', svg: '<svg viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>' }
    ];

    var SHORTCUTS = [
        ['Ctrl + Z', 'Undo'],
        ['Ctrl + Y / Ctrl + Shift + Z', 'Redo'],
        ['Ctrl + D', 'Duplicate'],
        ['Ctrl + A', 'Select all'],
        ['Delete / Backspace', 'Delete selected'],
        ['Arrow keys', 'Nudge (Shift = 10x)'],
        ['Esc', 'Deselect'],
        ['Double click text', 'Edit text on the sheet'],
        ['Ctrl + Mouse wheel', 'Zoom'],
        ['Drag & drop / Ctrl + V', 'Add images']
    ];

    var fontLinks = {};
    var fontReady = {};
    var uidSeq = 0;
    var removeBgModulePromise = null;

    /* ---------------------------------------------------------------- helpers */

    function clamp(v, a, b) { return Math.max(a, Math.min(b, v)); }

    function getQuery(name) {
        return new URLSearchParams(window.location.search).get(name);
    }

    function csrfToken() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.content : '';
    }

    function hex(c, def) {
        def = def || '#000000';
        if (!c || c === 'transparent' || typeof c !== 'string') return def;
        if (/^#[0-9a-f]{6}$/i.test(c)) return c.toLowerCase();
        try { return '#' + new fabric.Color(c).toHex().toLowerCase(); } catch (e) { return def; }
    }

    function blobToDataURL(blob) {
        return new Promise(function (resolve, reject) {
            var r = new FileReader();
            r.onload = function () { resolve(r.result); };
            r.onerror = reject;
            r.readAsDataURL(blob);
        });
    }

    function objKind(o) {
        if (!o) return '';
        if (o.type === 'image') return 'image';
        if (o.type === 'textbox' || o.type === 'i-text' || o.type === 'text') return 'text';
        return 'shape';
    }

    function loadRemoveBg() {
        if (!removeBgModulePromise) {
            removeBgModulePromise = import('https://esm.sh/@imgly/background-removal@1.4.5')
                .then(function (m) { return m.default; });
        }
        return removeBgModulePromise;
    }

    /* ------------------------------------------------------------------ fonts */

    function findFont(name) {
        for (var i = 0; i < FONTS.length; i++) {
            if (FONTS[i].name === name) return FONTS[i];
        }
        return null;
    }

    // Adds the stylesheet only (used for previews in the picker)
    function injectFont(f) {
        if (!f || f.sys || f.custom) return null;
        if (fontLinks[f.name]) return fontLinks[f.name];
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = 'https://fonts.googleapis.com/css2?family=' +
            encodeURIComponent(f.name).replace(/%20/g, '+') +
            (f.w ? ':wght@400;700' : '') + '&display=swap';
        document.head.appendChild(link);
        fontLinks[f.name] = link;
        return link;
    }

    // Makes sure the font files are really downloaded (needed before drawing on canvas)
    function loadFont(f) {
        if (!f) return Promise.resolve();
        if (fontReady[f.name]) return fontReady[f.name];

        if (f.sys || f.custom) {
            fontReady[f.name] = Promise.resolve();
            return fontReady[f.name];
        }

        var link = injectFont(f);

        fontReady[f.name] = new Promise(function (resolve) {
            var finished = false;
            var finish = function () { if (!finished) { finished = true; resolve(); } };

            var fetchFiles = function () {
                if (!document.fonts || !document.fonts.load) { finish(); return; }
                var q = '"' + f.name + '"';
                Promise.all([
                    document.fonts.load('16px ' + q, 'Aəğış'),
                    document.fonts.load('bold 16px ' + q, 'Aəğış')
                ]).then(finish, finish);
            };

            if (link.sheet) fetchFiles();
            else {
                link.addEventListener('load', fetchFiles);
                link.addEventListener('error', finish);
            }
            setTimeout(finish, 6000);
        });

        return fontReady[f.name];
    }

    /* ----------------------------------------------------------------- shapes */

    function polyPoints(n, R, inner) {
        var pts = [];
        var steps = inner ? n * 2 : n;
        for (var i = 0; i < steps; i++) {
            var r = inner ? (i % 2 === 0 ? R : R * inner) : R;
            var a = -Math.PI / 2 + i * 2 * Math.PI / steps;
            pts.push({ x: Math.cos(a) * r, y: Math.sin(a) * r });
        }
        return pts;
    }

    function makeShape(id, size, fill) {
        var base = {
            fill: fill, stroke: '#111111', strokeWidth: 0, strokeUniform: true,
            strokeLineJoin: 'round', originX: 'center', originY: 'center'
        };
        var s = null;

        switch (id) {
            case 'rect':
                s = new fabric.Rect(Object.assign({ width: size, height: size * 0.7 }, base));
                break;
            case 'round':
                s = new fabric.Rect(Object.assign({ width: size, height: size * 0.7, rx: size * 0.12, ry: size * 0.12 }, base));
                break;
            case 'circle':
                s = new fabric.Circle(Object.assign({ radius: size / 2 }, base));
                break;
            case 'triangle':
                s = new fabric.Triangle(Object.assign({ width: size, height: size * 0.9 }, base));
                break;
            case 'star':
                s = new fabric.Polygon(polyPoints(5, size / 2, 0.45), base);
                break;
            case 'hexagon':
                s = new fabric.Polygon(polyPoints(6, size / 2, 0), base);
                break;
            case 'heart':
                s = new fabric.Path('M 0 35 C -75 -5 -40 -60 0 -22 C 40 -60 75 -5 0 35 Z', base);
                s.scaleToWidth(size);
                break;
            case 'line':
                s = new fabric.Line([0, 0, size, 0], {
                    stroke: fill, strokeWidth: DPI * 0.06, strokeUniform: true,
                    strokeLineCap: 'round', originX: 'center', originY: 'center'
                });
                break;
        }
        return s;
    }

    /* -------------------------------------------------------------- component */

    window.designStudio = function (config) {
        var products = config.allProducts || [];
        var initialProduct = config.product || null;

        var canvas = null;
        var hist = [];
        var hIndex = -1;
        var restoring = false;
        var cornerPx = 14;
        var nudgeTimer = null;
        var resizeObs = null;

        return {
            brand: BRAND,
            version: VERSION,

            products: products,
            product: initialProduct,

            measurements: (initialProduct && initialProduct.measurements) ? initialProduct.measurements : [],
            selectedMeasurement: null,

            qty: 1,
            originalUploads: [],

            // counters
            itemCount: 0,
            outCount: 0,
            lowDpiCount: 0,

            // selection
            hasActive: false,
            single: false,
            selCount: 0,
            kind: '',
            locked: false,
            lockRatio: true,
            sel: { w: '', h: '', angle: 0, opacity: 100, dpi: 0 },

            txt: {
                content: '', font: 'Montserrat', size: 40, color: '#111111',
                bold: false, italic: false, underline: false, strike: false,
                align: 'center', spacing: 0, lineHeight: 1.1,
                strokeColor: '#111111', strokeW: 0,
                shadowOn: false, shadowColor: '#000000', shadowBlur: 8, shadowOff: 4
            },
            shp: { fill: '#6366f1', noFill: false, stroke: '#111111', strokeW: 0, radius: 0, isRect: false, isLine: false },
            img: { brightness: 0, contrast: 0, saturation: 0, blur: 0, grayscale: false, sepia: false, invert: false },

            layers: [],

            // ui
            tab: 'product',
            canUndo: false,
            canRedo: false,
            bgMode: 'white',
            dragging: false,
            gridOn: false,
            snapOn: true,
            marginOn: true,
            sheetW: 1,
            sheetH: 1,
            guideV: null,
            guideH: null,
            helpOpen: false,

            // fonts
            fonts: FONTS,
            fontCats: ['All', 'Sans', 'Serif', 'Display', 'Script', 'Mono', 'System', 'Custom'],
            fontOpen: false,
            fontQuery: '',
            fontCat: 'All',
            fontSample: '',
            defaultFont: 'Montserrat',
            newTextColor: '#111111',
            newShapeFill: '#6366f1',

            textPresets: TEXT_PRESETS,
            shapes: SHAPES,
            palette: PALETTE,
            shortcuts: SHORTCUTS,

            bgWorking: false,
            adding: false,
            zoomLevel: 1,
            fitScale: 1,
            ready: false,

            /* ------------------------------------------------------------ init */

            init() {
                var self = this;
                this.buildProductOptions();

                TEXT_PRESETS.forEach(function (p) { injectFont(findFont(p.font)); });
                injectFont(findFont(this.defaultFont));

                this.$nextTick(function () {
                    self.setupCanvas();
                    self.bindGlobalEvents();
                    self.$nextTick(function () {
                        self.selectInitialSize();
                        self.ready = true;
                    });
                });

                if (window.console && console.info) {
                    console.info('%c' + BRAND + '%c Design Studio v' + VERSION,
                        'background:#6366f1;color:#fff;padding:2px 8px;border-radius:4px;font-weight:700',
                        'color:#6366f1');
                }
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

            buildSizeOptions() {
                var s = this.$refs.sizeSelect;
                if (!s) return;

                if (!this.measurements.length) {
                    s.innerHTML = '<option value="">— No sizes —</option>';
                    s.disabled = true;
                    return;
                }

                s.disabled = false;
                s.innerHTML = this.measurements.map(function (m) {
                    var safe = String(m.label).replace(/</g, '&lt;').replace(/>/g, '&gt;');
                    return '<option value="' + m.id + '">' + safe + ' — $' + Number(m.price).toFixed(2) + '</option>';
                }).join('');

                if (this.selectedMeasurement) s.value = this.selectedMeasurement.id;
            },

            selectInitialSize() {
                if (!this.measurements.length) {
                    this.buildSizeOptions();
                    return;
                }

                var qw = parseFloat(getQuery('w'));
                var qh = parseFloat(getQuery('h'));
                var pick = null;

                if (qw > 0 && qh > 0) {
                    pick = this.measurements.find(function (m) {
                        return Math.abs(m.width - qw) < 0.01 && Math.abs(m.height - qh) < 0.01;
                    }) || null;
                }

                if (!pick) {
                    pick = this.measurements.find(function (m) { return m.is_default; }) || this.measurements[0];
                }

                this.selectedMeasurement = pick;
                this.buildSizeOptions();
                this.applyMeasurementSize();
            },

            changeSize(e) {
                var id = parseInt(e.target.value);
                var m = this.measurements.find(function (x) { return x.id === id; });
                if (!m) return;

                this.selectedMeasurement = m;
                this.applyMeasurementSize();
            },

            applyMeasurementSize() {
                var m = this.selectedMeasurement;
                if (!m || !canvas) return;

                var newW = Math.max(1, Math.round(m.width * DPI));
                var newH = Math.max(1, Math.round(m.height * DPI));

                if (newW !== canvas.getWidth() || newH !== canvas.getHeight()) {
                    canvas.setDimensions({ width: newW, height: newH });
                    canvas.renderAll();
                }

                this.sheetW = newW;
                this.sheetH = newH;
                this.fitStage();
                this.refreshStats();
            },

            /* ------------------------------------------------------- getters */

            get unitPrice() {
                return this.selectedMeasurement ? Number(this.selectedMeasurement.price) || 0 : 0;
            },

            get totalPrice() {
                return this.unitPrice * (this.qty || 1);
            },

            get dpiClass() {
                var d = Number(this.sel.dpi) || 0;
                return d >= GOOD_DPI ? 'ok' : (d >= LOW_DPI ? 'warn' : 'bad');
            },

            get kindLabel() {
                var map = { image: 'Image', text: 'Text', shape: 'Shape', mixed: 'Selection' };
                var l = map[this.kind] || 'Selection';
                return this.selCount > 1 ? (l + ' (' + this.selCount + ')') : l;
            },

            get filteredFonts() {
                var q = (this.fontQuery || '').toLowerCase().trim();
                var c = this.fontCat;
                return this.fonts.filter(function (f) {
                    return (c === 'All' || f.cat === c) && (!q || f.name.toLowerCase().indexOf(q) > -1);
                });
            },

            get gridStyle() {
                var w = (GRID_IN * DPI / (this.sheetW || 1)) * 100;
                var h = (GRID_IN * DPI / (this.sheetH || 1)) * 100;
                return 'background-size:' + w + '% ' + h + '%';
            },

            get marginStyle() {
                var x = (MARGIN_IN * DPI / (this.sheetW || 1)) * 100;
                var y = (MARGIN_IN * DPI / (this.sheetH || 1)) * 100;
                return 'left:' + x + '%;right:' + x + '%;top:' + y + '%;bottom:' + y + '%';
            },

            get vGuideStyle() {
                return 'left:' + ((this.guideV || 0) / (this.sheetW || 1) * 100) + '%';
            },

            get hGuideStyle() {
                return 'top:' + ((this.guideH || 0) / (this.sheetH || 1) * 100) + '%';
            },

            /* ---------------------------------------------------------- canvas */

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
                canvas.on('object:resizing', function () { self.updateSel(); });
                canvas.on('object:rotating', function () { self.updateSel(); });
                canvas.on('object:moving', function (e) {
                    if (self.snapOn && e.target) self.snapMoving(e.target);
                    self.refreshStats();
                });
                canvas.on('object:modified', function (e) {
                    var t = e.target;
                    if (t && t.type === 'textbox') self.bakeText(t);
                    self.guideV = null;
                    self.guideH = null;
                    self.commit();
                    self.syncActive();
                });
                canvas.on('mouse:up', function () { self.guideV = null; self.guideH = null; });

                canvas.on('text:changed', function (e) {
                    if (e.target && self.selCount === 1) self.txt.content = e.target.text;
                    self.refreshLayers();
                    self.updateSel();
                });
                canvas.on('text:editing:exited', function () { self.commit(); });

                this.applyMeasurementSize();
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

                if (wrap) {
                    wrap.addEventListener('wheel', function (e) {
                        if (!(e.ctrlKey || e.metaKey)) return;
                        e.preventDefault();
                        self.zoom(e.deltaY < 0 ? 1 : -1);
                    }, { passive: false });
                }

                document.addEventListener('keydown', function (e) {
                    var t = e.target;
                    if (t && (/^(INPUT|TEXTAREA|SELECT)$/.test(t.tagName) || t.isContentEditable)) return;
                    if (!canvas) return;
                    if (self.fontOpen || self.helpOpen) return;

                    var mod = e.ctrlKey || e.metaKey;
                    var k = (e.key || '').toLowerCase();

                    if (mod && k === 'z') { e.preventDefault(); e.shiftKey ? self.redo() : self.undo(); return; }
                    if (mod && k === 'y') { e.preventDefault(); self.redo(); return; }
                    if (mod && k === 'd') { e.preventDefault(); self.duplicate(); return; }
                    if (mod && k === 'a') { e.preventDefault(); self.selectAll(); return; }

                    if (k === 'escape') { self.deselect(); return; }

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

                window.addEventListener('paste', function (e) {
                    var t = e.target;
                    if (t && (/^(INPUT|TEXTAREA)$/.test(t.tagName) || t.isContentEditable)) return;
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

            /* ------------------------------------------------- selection state */

            activeObjs() { return canvas ? canvas.getActiveObjects() : []; },

            activeOf(kind) {
                return this.activeObjs().filter(function (o) { return objKind(o) === kind; });
            },

            syncActive() {
                if (!canvas) return;
                var objs = canvas.getActiveObjects();
                var kinds = objs.map(objKind);

                this.selCount = objs.length;
                this.hasActive = objs.length > 0;
                this.single = objs.length === 1;
                this.locked = objs.length > 0 && objs.every(function (o) { return !!o.lockMovementX; });

                if (!objs.length) this.kind = '';
                else this.kind = kinds.every(function (k) { return k === kinds[0]; }) ? kinds[0] : 'mixed';

                if (objs.length === 1) this.applyLockToObject(objs[0]);

                if (this.kind === 'text') this.readText(this.activeOf('text')[0]);
                if (this.kind === 'shape') this.readShape(this.activeOf('shape')[0]);
                if (this.kind === 'image') this.readImage(this.activeOf('image')[0]);

                this.updateSel();
                this.refreshLayers();
            },

            syncCount() {
                if (!canvas) return;
                this.itemCount = canvas.getObjects().length;
                this.refreshStats();
                this.refreshLayers();
            },

            updateSel() {
                if (!canvas) return;
                var objs = canvas.getActiveObjects();
                if (!objs.length) return;

                var op = Math.round((objs[0].opacity == null ? 1 : objs[0].opacity) * 100);

                if (objs.length === 1) {
                    var o = objs[0];
                    var wIn = o.getScaledWidth() / DPI;
                    var hIn = o.getScaledHeight() / DPI;
                    this.sel = {
                        w: wIn.toFixed(2),
                        h: hIn.toFixed(2),
                        angle: Math.round(((o.angle % 360) + 360) % 360),
                        opacity: op,
                        dpi: (o.type === 'image' && wIn > 0) ? Math.round(o.width / wIn) : 0
                    };
                } else {
                    this.sel = { w: '', h: '', angle: 0, opacity: op, dpi: 0 };
                }
                this.refreshStats();
            },

            refreshStats() {
                if (!canvas) return;
                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var out = 0, low = 0;

                canvas.getObjects().forEach(function (o) {
                    if (o.visible === false) return;
                    var r = o.getBoundingRect(true, true);
                    if (r.left < -1 || r.top < -1 || r.left + r.width > cw + 1 || r.top + r.height > ch + 1) out++;
                    if (o.type === 'image') {
                        var inch = o.getScaledWidth() / DPI;
                        if (inch > 0 && (o.width / inch) < LOW_DPI) low++;
                    }
                });

                this.outCount = out;
                this.lowDpiCount = low;
            },

            /* ------------------------------------------------------- history */

            commit() {
                this.updateSel();
                this.refreshStats();
                this.pushHistory();
            },

            snapshot() {
                return canvas.getObjects().map(function (o) {
                    var p = {};
                    HISTORY_PROPS.forEach(function (k) { if (k in o) p[k] = o[k]; });
                    if (o.type === 'textbox') p.width = o.width;
                    return { o: o, p: p, f: o._rjf ? Object.assign({}, o._rjf) : null };
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
                this.refreshLayers();
            },

            restore(state) {
                var self = this;
                restoring = true;
                canvas.discardActiveObject();
                canvas.getObjects().slice().forEach(function (o) { canvas.remove(o); });

                state.forEach(function (s) {
                    s.o.set(s.p);
                    if (s.o.type === 'image') {
                        var cur = JSON.stringify(s.o._rjf || null);
                        var want = JSON.stringify(s.f || null);
                        if (cur !== want) {
                            s.o._rjf = s.f ? Object.assign({}, s.f) : null;
                            self.rebuildFilters(s.o);
                        }
                    }
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

            /* ---------------------------------------------------------- view */

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

            changeProduct(e) {
                var id = Number(e.target.value);
                var p = this.products.find(function (x) { return Number(x.id) === id; });
                if (!p) return;
                window.location.href = '/design/' + p.slug;
            },

            /* -------------------------------------------------------- snapping */

            snapMoving(o) {
                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var thr = Math.max(3, 8 / ((this.fitScale * this.zoomLevel) || 1));
                var m = MARGIN_IN * DPI;
                var grid = GRID_IN * DPI;
                var r = o.getBoundingRect(true, true);

                var xs = [
                    [r.left + r.width / 2, cw / 2, cw / 2],
                    [r.left, m, m],
                    [r.left + r.width, cw - m, cw - m]
                ];
                var ys = [
                    [r.top + r.height / 2, ch / 2, ch / 2],
                    [r.top, m, m],
                    [r.top + r.height, ch - m, ch - m]
                ];

                function best(list) {
                    var res = null;
                    list.forEach(function (c) {
                        var d = Math.abs(c[0] - c[1]);
                        if (d < thr && (!res || d < res.d)) res = { d: d, delta: c[1] - c[0], guide: c[2] };
                    });
                    return res;
                }

                var bx = best(xs);
                var by = best(ys);
                var dx = bx ? bx.delta : 0;
                var dy = by ? by.delta : 0;

                if (!bx && this.gridOn) {
                    var gx = Math.round(r.left / grid) * grid;
                    if (Math.abs(gx - r.left) < thr) dx = gx - r.left;
                }
                if (!by && this.gridOn) {
                    var gy = Math.round(r.top / grid) * grid;
                    if (Math.abs(gy - r.top) < thr) dy = gy - r.top;
                }

                if (dx || dy) {
                    o.set({ left: o.left + dx, top: o.top + dy });
                    o.setCoords();
                }

                this.guideV = bx ? bx.guide : null;
                this.guideH = by ? by.guide : null;
            },

            /* ------------------------------------------------------ uploading */

            onFiles(e) {
                this.addFiles(Array.from(e.target.files || []));
                e.target.value = '';
            },

            onDrop(e) {
                this.dragging = false;
                var files = Array.from((e.dataTransfer && e.dataTransfer.files) || []);
                this.addFiles(files);
            },

            async addFiles(files) {
                var self = this;

                for (var i = 0; i < files.length; i++) {
                    var f = files[i];

                    var isImage = /^image\/(png|jpe?g|webp)$/i.test(f.type);
                    var isPdf = f.type === 'application/pdf' || /\.pdf$/i.test(f.name);

                    if (!isImage && !isPdf) {
                        self.toast('Only PNG, JPG, WEBP or PDF files are supported');
                        continue;
                    }

                    if (f.size > MAX_FILE_MB * 1024 * 1024) {
                        self.toast(f.name + ' is larger than ' + MAX_FILE_MB + 'MB');
                        continue;
                    }

                    try {
                        var uploaded = await self.uploadFile(f);

                        if (uploaded) {
                            self.originalUploads.push(uploaded);
                        } else {
                            self.toast('Upload failed: ' + f.name);
                            continue;
                        }
                    } catch (err) {
                        self.toast('Upload failed: ' + f.name);
                        continue;
                    }

                    if (isImage) {
                        var dataUrl = await new Promise(function (resolve) {
                            var reader = new FileReader();
                            reader.onload = function (ev) { resolve(ev.target.result); };
                            reader.onerror = function () { resolve(null); };
                            reader.readAsDataURL(f);
                        });

                        if (dataUrl) {
                            await self.addImageFromSrc(dataUrl, null, f.name);
                        }
                    }
                }
            },

            async uploadFile(file) {
                var fd = new FormData();
                fd.append('file', file);
                fd.append('_token', csrfToken());

                var res = await fetch('/design/temp-upload', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken()
                    },
                    credentials: 'same-origin'
                });

                if (!res.ok) return null;

                var data = await res.json();
                if (!data || !data.success) return null;

                return {
                    token: data.token,
                    path: data.path,
                    name: data.name,
                    size: data.size,
                    mime: data.mime
                };
            },

            addImageFromSrc(src, replaceObj, name) {
                var self = this;

                return new Promise(function (resolve) {
                    if (!canvas) { resolve(); return; }

                    fabric.Image.fromURL(src, function (img) {
                        if (!img || !img.width) {
                            self.toast('Could not load image');
                            resolve();
                            return;
                        }

                        var cw = canvas.getWidth();
                        var ch = canvas.getHeight();

                        var scale = Math.min(DPI / TARGET_DPI, (cw * 0.6) / img.width, (ch * 0.6) / img.height);
                        var cascade = replaceObj ? 0 : (self.itemCount % 6) * DPI * 0.15;

                        img._rjName = name || (replaceObj && replaceObj._rjName) || 'Image';

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

                        resolve();
                    }, { crossOrigin: 'anonymous' });
                });
            },

            async removeBg() {
                var active = canvas ? canvas.getActiveObject() : null;
                if (!active || active.type !== 'image') return;

                this.bgWorking = true;

                try {
                    var removeFn = await loadRemoveBg();
                    var blob = await removeFn(active.getSrc());
                    var dataUrl = await blobToDataURL(blob);
                    await this.addImageFromSrc(dataUrl, active);
                } catch (e) {
                    this.toast('Background removal failed');
                }

                this.bgWorking = false;
            },

            /* ------------------------------------------------------------ text */

            addText(preset) {
                if (!canvas) return;
                var self = this;
                preset = preset || {};

                var f = findFont(preset.font || this.defaultFont) || FONTS[0];

                loadFont(f).then(function () {
                    var cw = canvas.getWidth();
                    var ch = canvas.getHeight();
                    var cascade = (self.itemCount % 6) * DPI * 0.15;

                    var shadow = null;
                    if (preset.shadow) {
                        shadow = new fabric.Shadow({
                            color: preset.shadow.color, blur: preset.shadow.blur,
                            offsetX: preset.shadow.off, offsetY: preset.shadow.off
                        });
                    }

                    var t = new fabric.Textbox(preset.text || 'Your text', {
                        originX: 'center',
                        originY: 'center',
                        left: cw / 2 + cascade,
                        top: ch / 2 + cascade,
                        width: Math.min(cw * 0.7, DPI * 6),
                        fontFamily: f.name,
                        fontSize: (preset.pt || 40) * DPI / 72,
                        fontWeight: preset.bold ? 'bold' : 'normal',
                        fill: preset.fill || self.newTextColor,
                        stroke: preset.stroke || '#111111',
                        strokeWidth: preset.strokeW || 0,
                        strokeUniform: true,
                        strokeLineJoin: 'round',
                        paintFirst: 'stroke',
                        textAlign: 'center',
                        lineHeight: 1.1,
                        charSpacing: preset.spacing || 0,
                        shadow: shadow
                    });

                    t._rjName = 'Text';
                    self.applyHandleSize(t);
                    canvas.add(t);
                    canvas.setActiveObject(t);
                    canvas.renderAll();
                    self.syncActive();
                    self.syncCount();
                    self.pushHistory();

                    try { t.enterEditing(); t.selectAll(); } catch (e) { /* ignore */ }
                });
            },

            readText(o) {
                if (!o) return;
                var sh = o.shadow;
                this.txt = {
                    content: o.text || '',
                    font: o.fontFamily || 'Arial',
                    size: Math.round((o.fontSize / DPI * 72) * 10) / 10,
                    color: hex(o.fill, '#111111'),
                    bold: String(o.fontWeight) === 'bold' || Number(o.fontWeight) >= 600,
                    italic: o.fontStyle === 'italic',
                    underline: !!o.underline,
                    strike: !!o.linethrough,
                    align: o.textAlign || 'left',
                    spacing: o.charSpacing || 0,
                    lineHeight: o.lineHeight || 1.16,
                    strokeColor: hex(o.stroke, '#111111'),
                    strokeW: o.strokeWidth || 0,
                    shadowOn: !!sh,
                    shadowColor: sh ? hex(sh.color, '#000000') : '#000000',
                    shadowBlur: sh ? sh.blur : 8,
                    shadowOff: sh ? sh.offsetX : 4
                };
            },

            // Live change (no history entry). Call txtDone() when the user finishes.
            txtSet(key, val) {
                var self = this;
                var texts = this.activeOf('text');
                if (!texts.length) return;

                if (key === 'size') val = clamp(parseFloat(val) || 1, 1, 600);
                if (key === 'strokeW' || key === 'shadowBlur' || key === 'shadowOff' || key === 'spacing' || key === 'lineHeight') {
                    val = Number(val) || 0;
                }

                this.txt[key] = val;

                texts.forEach(function (o) { self.applyText(o, key, val); });
                canvas.requestRenderAll();
                this.updateSel();
                if (key === 'content') this.refreshLayers();
            },

            txtDone() { this.commit(); },

            txtToggle(key) {
                this.txtSet(key, !this.txt[key]);
                this.txtDone();
            },

            txtAlign(v) {
                this.txtSet('align', v);
                this.txtDone();
            },

            txtCase(mode) {
                var s = String(this.txt.content || '');
                if (mode === 'upper') s = s.toUpperCase();
                else if (mode === 'lower') s = s.toLowerCase();
                else s = s.toLowerCase().replace(/(^|\s)(\S)/g, function (m, a, b) { return a + b.toUpperCase(); });
                this.txtSet('content', s);
                this.txtDone();
            },

            applyText(o, key, v) {
                switch (key) {
                    case 'content': o.set('text', v); break;
                    case 'size': o.set('fontSize', v * DPI / 72); break;
                    case 'color': o.set('fill', v); break;
                    case 'bold': o.set('fontWeight', v ? 'bold' : 'normal'); break;
                    case 'italic': o.set('fontStyle', v ? 'italic' : 'normal'); break;
                    case 'underline': o.set('underline', !!v); break;
                    case 'strike': o.set('linethrough', !!v); break;
                    case 'align': o.set('textAlign', v); break;
                    case 'spacing': o.set('charSpacing', v); break;
                    case 'lineHeight': o.set('lineHeight', v); break;
                    case 'strokeColor': o.set('stroke', v); break;
                    case 'strokeW': o.set({ strokeWidth: v, paintFirst: 'stroke', stroke: this.txt.strokeColor }); break;
                    case 'shadowOn':
                    case 'shadowColor':
                    case 'shadowBlur':
                    case 'shadowOff':
                        this.applyShadow(o);
                        break;
                }
                o.setCoords();
            },

            applyShadow(o) {
                var t = this.txt;
                o.set('shadow', t.shadowOn ? new fabric.Shadow({
                    color: t.shadowColor,
                    blur: Number(t.shadowBlur) || 0,
                    offsetX: Number(t.shadowOff) || 0,
                    offsetY: Number(t.shadowOff) || 0
                }) : null);
            },

            bakeText(o) {
                if (!o || o.type !== 'textbox') return;
                if (o.scaleX !== 1 || o.scaleY !== 1) {
                    o.set({
                        fontSize: o.fontSize * o.scaleY,
                        width: o.width * o.scaleX,
                        scaleX: 1,
                        scaleY: 1
                    });
                    o.setCoords();
                }
            },

            swatchText(c) {
                this.txtSet('color', c);
                this.txtDone();
            },

            /* ------------------------------------------------------------ fonts */

            openFonts() {
                this.fontOpen = true;
                FONTS.forEach(injectFont);
            },

            setFont(name) {
                var self = this;
                var f = findFont(name);
                if (!f) return;

                this.defaultFont = name;
                var texts = this.activeOf('text');

                loadFont(f).then(function () {
                    try { fabric.util.clearFabricFontCache(name); } catch (e) { /* ignore */ }

                    if (!texts.length) {
                        self.toast('Default font: ' + name);
                        return;
                    }

                    texts.forEach(function (o) {
                        o.set('fontFamily', name);
                        o.initDimensions();
                        o.setCoords();
                    });
                    canvas.requestRenderAll();
                    self.txt.font = name;
                    self.commit();
                });
            },

            pickFont(name) {
                this.setFont(name);
                this.fontOpen = false;
            },

            fontCss(name) {
                var f = findFont(name);
                var fallback = (f && f.cat === 'Serif') ? 'serif' : ((f && f.cat === 'Mono') ? 'monospace' : 'sans-serif');
                return "font-family:'" + String(name).replace(/'/g, '') + "'," + fallback;
            },

            presetCss(p) {
                var s = this.fontCss(p.font) + ';color:' + p.fill + ';';
                if (p.bold) s += 'font-weight:700;';
                if (p.fill === '#ffffff') s += 'background:#2a2540;';
                if (p.stroke) s += '-webkit-text-stroke:1px ' + p.stroke + ';';
                return s;
            },

            onFontFile(e) {
                var self = this;
                var files = Array.from(e.target.files || []);
                e.target.value = '';

                files.forEach(function (f) {
                    if (!/\.(ttf|otf|woff2?)$/i.test(f.name)) {
                        self.toast('Use TTF, OTF or WOFF font files');
                        return;
                    }

                    var name = f.name.replace(/\.[^.]+$/, '').replace(/[^\w\- ]+/g, ' ').trim() || 'Custom font';
                    var reader = new FileReader();

                    reader.onload = function (ev) {
                        var face = new FontFace(name, ev.target.result);
                        face.load().then(function (ff) {
                            document.fonts.add(ff);
                            if (!findFont(name)) self.fonts.push({ name: name, cat: 'Custom', custom: true });
                            fontReady[name] = Promise.resolve();
                            self.setFont(name);
                            self.toast('Font added: ' + name);
                        }).catch(function () {
                            self.toast('Could not read the font file');
                        });
                    };
                    reader.readAsArrayBuffer(f);
                });
            },

            refreshFontsInUse() {
                if (!canvas) return;
                canvas.getObjects().forEach(function (o) {
                    if (objKind(o) !== 'text') return;
                    var f = findFont(o.fontFamily);
                    if (!f) return;
                    loadFont(f).then(function () {
                        try { fabric.util.clearFabricFontCache(f.name); } catch (e) { /* ignore */ }
                        o.initDimensions();
                        o.setCoords();
                        canvas.requestRenderAll();
                    });
                });
            },

            /* ----------------------------------------------------------- shapes */

            addShape(id) {
                if (!canvas) return;

                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var size = Math.min(cw, ch) * 0.3;
                var s = makeShape(id, size, this.newShapeFill);
                if (!s) return;

                var cascade = (this.itemCount % 6) * DPI * 0.15;
                s.set({ left: cw / 2 + cascade, top: ch / 2 + cascade });
                s._rjName = id.charAt(0).toUpperCase() + id.slice(1);

                this.applyHandleSize(s);
                canvas.add(s);
                canvas.setActiveObject(s);
                canvas.renderAll();
                this.syncActive();
                this.syncCount();
                this.pushHistory();
            },

            readShape(o) {
                if (!o) return;
                var isLine = o.type === 'line';
                this.shp = {
                    fill: hex(isLine ? o.stroke : o.fill, '#6366f1'),
                    noFill: !isLine && (!o.fill || o.fill === 'transparent'),
                    stroke: hex(o.stroke, '#111111'),
                    strokeW: o.strokeWidth || 0,
                    radius: o.rx || 0,
                    isRect: o.type === 'rect',
                    isLine: isLine
                };
            },

            shpSet(key, val) {
                var shapes = this.activeOf('shape');
                if (!shapes.length) return;

                if (key === 'strokeW' || key === 'radius') val = Number(val) || 0;
                this.shp[key] = val;

                var self = this;
                shapes.forEach(function (o) {
                    var line = o.type === 'line';
                    switch (key) {
                        case 'fill':
                            if (line) o.set('stroke', val);
                            else { o.set('fill', val); self.shp.noFill = false; }
                            break;
                        case 'noFill':
                            if (!line) o.set('fill', val ? 'transparent' : self.shp.fill);
                            break;
                        case 'stroke': o.set('stroke', val); break;
                        case 'strokeW': o.set('strokeWidth', val); break;
                        case 'radius':
                            if (o.type === 'rect') o.set({ rx: val, ry: val });
                            break;
                    }
                    o.setCoords();
                });

                canvas.requestRenderAll();
            },

            shpDone() { this.commit(); },

            swatchShape(c) {
                this.shpSet('fill', c);
                this.shpDone();
            },

            /* ----------------------------------------------------- image filters */

            readImage(o) {
                if (!o) return;
                var f = o._rjf || {};
                this.img = {
                    brightness: f.brightness || 0,
                    contrast: f.contrast || 0,
                    saturation: f.saturation || 0,
                    blur: f.blur || 0,
                    grayscale: !!f.grayscale,
                    sepia: !!f.sepia,
                    invert: !!f.invert
                };
            },

            imgSet(key, val) {
                var self = this;
                var imgs = this.activeOf('image');
                if (!imgs.length) return;

                if (typeof val !== 'boolean') val = Number(val) || 0;
                this.img[key] = val;

                imgs.forEach(function (o) {
                    var next = Object.assign({}, o._rjf || {});
                    next[key] = val;
                    o._rjf = next;
                    self.rebuildFilters(o);
                });

                canvas.requestRenderAll();
                this.commit();
            },

            imgReset() {
                var self = this;
                this.activeOf('image').forEach(function (o) {
                    o._rjf = null;
                    self.rebuildFilters(o);
                });
                this.readImage({ _rjf: null });
                canvas.requestRenderAll();
                this.commit();
            },

            rebuildFilters(o) {
                if (!o || o.type !== 'image') return;
                var f = o._rjf || {};
                var F = fabric.Image.filters;
                var list = [];

                if (+f.brightness) list.push(new F.Brightness({ brightness: f.brightness / 100 }));
                if (+f.contrast) list.push(new F.Contrast({ contrast: f.contrast / 100 }));
                if (+f.saturation) list.push(new F.Saturation({ saturation: f.saturation / 100 }));
                if (+f.blur) list.push(new F.Blur({ blur: f.blur / 100 }));
                if (f.grayscale) list.push(new F.Grayscale());
                if (f.sepia) list.push(new F.Sepia());
                if (f.invert) list.push(new F.Invert());

                o.filters = list;
                o.applyFilters();
            },

            /* ----------------------------------------------- transform actions */

            flipH() {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                a.set('flipX', !a.flipX);
                canvas.renderAll();
                this.pushHistory();
            },

            flipV() {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;
                a.set('flipY', !a.flipY);
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
                v = parseFloat(v);
                if (isNaN(v)) return;
                var val = clamp(v, 10, 100) / 100;
                this.activeObjs().forEach(function (o) { o.set('opacity', val); });
                canvas.renderAll();
                this.commit();
            },

            setSize(dim, val) {
                var o = canvas ? canvas.getActiveObject() : null;
                if (!o || this.selCount !== 1) return;

                val = parseFloat(val);
                if (!(val > 0)) { this.updateSel(); return; }
                val = clamp(val, 0.1, 200);

                var px = val * DPI;
                var sx = o.scaleX, sy = o.scaleY;

                if (dim === 'w') {
                    var fw = px / o.getScaledWidth();
                    sx = o.scaleX * fw;
                    if (this.lockRatio) sy = o.scaleY * fw;
                } else {
                    var fh = px / o.getScaledHeight();
                    sy = o.scaleY * fh;
                    if (this.lockRatio) sx = o.scaleX * fh;
                }

                o.set({ scaleX: sx, scaleY: sy });
                this.bakeText(o);
                o.setCoords();
                canvas.renderAll();
                this.commit();
            },

            toggleRatio() {
                this.lockRatio = !this.lockRatio;
                var o = canvas ? canvas.getActiveObject() : null;
                if (o && this.selCount === 1) {
                    this.applyLockToObject(o);
                    canvas.renderAll();
                }
            },

            applyLockToObject(o) {
                if (objKind(o) === 'text') {
                    o.setControlsVisibility({ ml: true, mr: true, mt: false, mb: false });
                    return;
                }
                var free = !this.lockRatio;
                o.setControlsVisibility({ ml: free, mr: free, mt: free, mb: free });
            },

            fitToSheet() {
                var o = canvas ? canvas.getActiveObject() : null;
                if (!o || this.selCount !== 1) return;

                var m = MARGIN_IN * DPI;
                var cw = canvas.getWidth() - m * 2;
                var ch = canvas.getHeight() - m * 2;

                var s = Math.min(cw / o.width, ch / o.height);
                o.set({ scaleX: s, scaleY: s });
                o.setCoords();

                var r = o.getBoundingRect(true, true);
                var k = Math.min(cw / r.width, ch / r.height, 1);
                if (k < 1) { o.set({ scaleX: s * k, scaleY: s * k }); o.setCoords(); }

                this.bakeText(o);
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

            // where: left | right | top | bottom | h | v
            alignTo(where) {
                var a = canvas ? canvas.getActiveObject() : null;
                if (!a) return;

                var r = a.getBoundingRect(true, true);
                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var m = MARGIN_IN * DPI;

                switch (where) {
                    case 'left': this.moveBy(m - r.left, 0); break;
                    case 'right': this.moveBy(cw - m - (r.left + r.width), 0); break;
                    case 'top': this.moveBy(0, m - r.top); break;
                    case 'bottom': this.moveBy(0, ch - m - (r.top + r.height)); break;
                    case 'h': this.moveBy(cw / 2 - (r.left + r.width / 2), 0); break;
                    case 'v': this.moveBy(0, ch / 2 - (r.top + r.height / 2)); break;
                }
                this.commit();
            },

            center(axis) { this.alignTo(axis); },

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

            toggleLockActive() {
                var objs = this.activeObjs();
                if (!objs.length) return;
                var v = !objs[0].lockMovementX;
                objs.forEach(function (o) { o.set(lockProps(v)); });
                canvas.renderAll();
                this.syncActive();
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
                    }, CUSTOM_PROPS);
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

            selectAll() {
                if (!canvas) return;
                var objs = canvas.getObjects().filter(function (o) { return o.visible !== false; });
                if (!objs.length) return;
                canvas.discardActiveObject();
                canvas.setActiveObject(objs.length === 1 ? objs[0] : new fabric.ActiveSelection(objs, { canvas: canvas }));
                canvas.requestRenderAll();
                this.syncActive();
            },

            deselect() {
                if (!canvas) return;
                canvas.discardActiveObject();
                canvas.requestRenderAll();
                this.syncActive();
            },

            autoArrange() {
                if (!canvas) return;
                canvas.discardActiveObject();

                var objs = canvas.getObjects().filter(function (o) { return o.visible !== false; });
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
                    this.toast('Not everything fits — choose a larger size');
                }
            },

            // Repeats the selected item to fill the whole sheet
            async fillSheet() {
                var o = canvas ? canvas.getActiveObject() : null;
                if (!o || this.selCount !== 1) { this.toast('Select one item first'); return; }

                var cw = canvas.getWidth();
                var ch = canvas.getHeight();
                var m = MARGIN_IN * DPI;
                var gap = GAP_IN * DPI;
                var r = o.getBoundingRect(true, true);

                var cols = Math.floor((cw - m * 2 + gap) / (r.width + gap));
                var rows = Math.floor((ch - m * 2 + gap) / (r.height + gap));
                var total = cols * rows;

                if (cols < 1 || rows < 1 || total < 2) {
                    this.toast('The item is too large to repeat on this sheet');
                    return;
                }

                if (total > MAX_FILL) {
                    this.toast('Limited to ' + MAX_FILL + ' copies');
                    total = MAX_FILL;
                }

                if (!window.confirm('Fill the sheet with ' + total + ' copies of this item?')) return;

                var self = this;
                var offX = o.left - r.left;
                var offY = o.top - r.top;
                var jobs = [];
                var n = 0;

                canvas.discardActiveObject();
                restoring = true;

                outer:
                for (var j = 0; j < rows; j++) {
                    for (var i = 0; i < cols; i++) {
                        if (n >= total) break outer;
                        var tx = m + i * (r.width + gap) + offX;
                        var ty = m + j * (r.height + gap) + offY;

                        if (n === 0) {
                            o.set({ left: tx, top: ty });
                            o.setCoords();
                        } else {
                            jobs.push(new Promise(function (resolve) {
                                var px = tx, py = ty;
                                o.clone(function (c) {
                                    c.set({ left: px, top: py });
                                    self.applyHandleSize(c);
                                    canvas.add(c);
                                    resolve();
                                }, CUSTOM_PROPS);
                            }));
                        }
                        n++;
                    }
                }

                await Promise.all(jobs);

                restoring = false;
                canvas.renderAll();
                this.syncActive();
                this.syncCount();
                this.pushHistory();
                this.toast(n + ' copies placed');
            },

            /* ------------------------------------------------------------ layers */

            uid(o) {
                if (!o._rjId) o._rjId = 'l' + (++uidSeq);
                return o._rjId;
            },

            layerName(o) {
                var k = objKind(o);
                if (k === 'text') {
                    var t = String(o.text || '').replace(/\s+/g, ' ').trim();
                    return t ? t.slice(0, 22) : 'Text';
                }
                return o._rjName || (k === 'image' ? 'Image' : 'Shape');
            },

            refreshLayers() {
                if (!canvas) return;
                var self = this;
                var active = canvas.getActiveObjects();
                var objs = canvas.getObjects();
                var out = [];

                for (var i = objs.length - 1; i >= 0; i--) {
                    var o = objs[i];
                    out.push({
                        id: self.uid(o),
                        name: self.layerName(o),
                        kind: objKind(o),
                        visible: o.visible !== false,
                        locked: !!o.lockMovementX,
                        active: active.indexOf(o) > -1
                    });
                }
                this.layers = out;
            },

            findById(id) {
                if (!canvas) return null;
                var objs = canvas.getObjects();
                for (var i = 0; i < objs.length; i++) {
                    if (objs[i]._rjId === id) return objs[i];
                }
                return null;
            },

            selectLayer(id) {
                var o = this.findById(id);
                if (!o) return;
                if (o.visible === false) { this.toast('Layer is hidden'); return; }
                canvas.discardActiveObject();
                canvas.setActiveObject(o);
                canvas.requestRenderAll();
                this.syncActive();
            },

            toggleVisible(id) {
                var o = this.findById(id);
                if (!o) return;
                var show = o.visible === false;
                if (!show) canvas.discardActiveObject();
                o.set('visible', show);
                canvas.requestRenderAll();
                this.syncActive();
                this.commit();
            },

            toggleLockLayer(id) {
                var o = this.findById(id);
                if (!o) return;
                o.set(lockProps(!o.lockMovementX));
                canvas.requestRenderAll();
                this.syncActive();
                this.pushHistory();
            },

            moveLayer(id, dir) {
                var o = this.findById(id);
                if (!o) return;
                if (dir > 0) canvas.bringForward(o); else canvas.sendBackwards(o);
                canvas.requestRenderAll();
                this.pushHistory();
            },

            deleteLayer(id) {
                var o = this.findById(id);
                if (!o) return;
                canvas.discardActiveObject();
                canvas.remove(o);
                canvas.requestRenderAll();
                this.syncActive();
                this.syncCount();
                this.pushHistory();
            },

            /* ---------------------------------------------------------- projects */

            saveProject() {
                if (!canvas || !this.itemCount) { this.toast('Nothing to save yet'); return; }

                canvas.discardActiveObject();
                canvas.renderAll();
                this.syncActive();

                var m = this.selectedMeasurement;
                var data = {
                    app: BRAND,
                    version: VERSION,
                    savedAt: new Date().toISOString(),
                    product: this.product ? this.product.id : null,
                    measurement: m ? m.id : null,
                    width: m ? m.width : null,
                    height: m ? m.height : null,
                    canvas: canvas.toJSON(CUSTOM_PROPS)
                };

                var blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
                var a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = BRAND + '-project-' + new Date().toISOString().slice(0, 10) + '.rjframe.json';
                document.body.appendChild(a);
                a.click();
                a.remove();
                setTimeout(function () { URL.revokeObjectURL(a.href); }, 5000);
                this.toast('Project saved');
            },

            openProject(e) {
                var self = this;
                var f = e.target.files && e.target.files[0];
                e.target.value = '';
                if (!f) return;

                var reader = new FileReader();
                reader.onload = function (ev) {
                    var data;
                    try { data = JSON.parse(ev.target.result); }
                    catch (err) { self.toast('Invalid project file'); return; }

                    if (!data || data.app !== BRAND || !data.canvas) {
                        self.toast('This is not an ' + BRAND + ' project');
                        return;
                    }

                    if (self.itemCount > 0 && !window.confirm('Replace the current design with this project?')) return;
                    self.loadProject(data);
                };
                reader.readAsText(f);
            },

            loadProject(data) {
                var self = this;
                var m = null;

                if (data.measurement != null) {
                    m = this.measurements.find(function (x) { return x.id === data.measurement; }) || null;
                }
                if (!m && data.width > 0 && data.height > 0) {
                    m = this.measurements.find(function (x) {
                        return Math.abs(x.width - data.width) < 0.01 && Math.abs(x.height - data.height) < 0.01;
                    }) || null;
                }

                if (m) {
                    this.selectedMeasurement = m;
                    this.buildSizeOptions();
                    this.applyMeasurementSize();
                } else {
                    this.toast('Original sheet size is not available — using the current size');
                }

                restoring = true;
                canvas.discardActiveObject();

                canvas.loadFromJSON(data.canvas, function () {
                    restoring = false;
                    canvas.getObjects().forEach(function (o) { self.applyHandleSize(o); });
                    hist = [];
                    hIndex = -1;
                    self.pushHistory();
                    self.canUndo = false;
                    canvas.renderAll();
                    self.syncActive();
                    self.syncCount();
                    self.refreshFontsInUse();
                    self.reuploadImages();
                    self.toast('Project loaded');
                });
            },

            // Images from an opened project need their originals on the server again
            async reuploadImages() {
                var imgs = canvas.getObjects().filter(function (o) { return o.type === 'image'; });
                if (!imgs.length) return;

                this.originalUploads = [];
                var seen = {};

                for (var i = 0; i < imgs.length; i++) {
                    var o = imgs[i];
                    try {
                        var src = o.getSrc();
                        if (seen[src]) continue;
                        seen[src] = true;

                        var blob = await (await fetch(src)).blob();
                        var ext = (blob.type.split('/')[1] || 'png').replace('jpeg', 'jpg');
                        var base = String(o._rjName || 'image').replace(/\.[a-z0-9]+$/i, '');
                        var file = new File([blob], base + '.' + ext, { type: blob.type || 'image/png' });
                        var up = await this.uploadFile(file);
                        if (up) this.originalUploads.push(up);
                    } catch (err) { /* skip */ }
                }
            },

            /* ---------------------------------------------------------- quantity */

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

                var m = this.selectedMeasurement;
                var name = BRAND + '-gang-sheet-' + (m ? (m.width + 'x' + m.height + 'in') : 'sheet') + '.png';
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
                    this.toast('Export failed — try a smaller size');
                }
            },

            async addToCart() {
                if (this.itemCount === 0 || this.adding || !this.selectedMeasurement) return;

                if (this.outCount > 0 || this.lowDpiCount > 0) {
                    var msg = [];
                    if (this.outCount > 0) msg.push(this.outCount + ' item(s) extend beyond the sheet and will be cut off');
                    if (this.lowDpiCount > 0) msg.push(this.lowDpiCount + ' item(s) are below ' + LOW_DPI + ' DPI and may print blurry');
                    if (!window.confirm(msg.join('\n') + '\n\nAdd to cart anyway?')) return;
                }

                this.adding = true;

                var m = this.selectedMeasurement;
                var widthIn = Number(m.width);
                var heightIn = Number(m.height);
                var unitPrice = Number(m.price);
                var sizeLabel = m.label;

                var compositeUpload = null;
                var compositeImage = null;
                var effectiveDpi = DPI;

                try {
                    var cw = canvas.getWidth();
                    var ch = canvas.getHeight();

                    var targetMult = TARGET_DPI / DPI;
                    var capMult = Math.sqrt(MAX_EXPORT_PIXELS / Math.max(1, cw * ch));
                    var mult = Math.max(1, Math.min(targetMult, capMult));

                    effectiveDpi = Math.round(mult * DPI);

                    var el = this.exportCanvas(mult, '');

                    var blob = await new Promise(function (resolve) {
                        try { el.toBlob(resolve, 'image/png'); }
                        catch (err) { resolve(null); }
                    });

                    if (blob) {
                        var exportName = BRAND + '-gang-sheet-' + widthIn + 'x' + heightIn + 'in.png';
                        var file = new File([blob], exportName, { type: 'image/png' });
                        compositeUpload = await this.uploadFile(file);
                    }

                    if (!compositeUpload) {
                        compositeImage = el.toDataURL('image/png');
                    }
                } catch (e) {
                    console.error('Composite export failed', e);
                }

                if (!compositeUpload && !compositeImage) {
                    this.toast('Could not prepare print file — try again');
                    this.adding = false;
                    return;
                }

                var canvasState = null;
                try {
                    var state = canvas.toJSON(CUSTOM_PROPS);
                    if (state && Array.isArray(state.objects)) {
                        state.objects.forEach(function (o) {
                            if (o && o.type === 'image' && typeof o.src === 'string') {
                                if (o.src.indexOf('data:') === 0) o.src = '';
                            }
                        });
                    }
                    canvasState = state;
                } catch (e) {
                    canvasState = null;
                }

                var attributes = {
                    'Size': sizeLabel,
                    'Width (in)': widthIn,
                    'Height (in)': heightIn,
                    'Items': this.itemCount,
                    'DPI': effectiveDpi
                };

                var payload = {
                    product_id: this.product ? this.product.id : null,
                    product_name: this.product ? this.product.name : null,
                    name: this.product ? this.product.name : 'Custom Gang Sheet',
                    unit_price: unitPrice,
                    product_price: unitPrice,
                    sheet_price: 0,
                    width_inch: widthIn,
                    height_inch: heightIn,
                    qty: this.qty,
                    attributes: attributes,
                    print_type: 'custom_size',
                    note: BRAND + ' · ' + this.itemCount + ' design item(s) · ' + sizeLabel,
                    file_name: BRAND + '-gang-sheet-' + widthIn + 'x' + heightIn + 'in.png',
                    original_uploads: this.originalUploads.slice(),
                    composite_upload: compositeUpload,
                    composite_image: compositeImage,
                    canvas_state: canvasState
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
                        this.toast('Added to cart ✓ (' + effectiveDpi + ' DPI)');
                        this.originalUploads = [];
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

    function lockProps(v) {
        return {
            lockMovementX: v, lockMovementY: v, lockRotation: v,
            lockScalingX: v, lockScalingY: v, hasControls: !v
        };
    }

    window.RJFrame = { name: BRAND, version: VERSION, fonts: FONTS };
})();

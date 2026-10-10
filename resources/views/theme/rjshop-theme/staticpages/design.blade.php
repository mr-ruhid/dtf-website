@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'RJFrame Design Studio')
@section('meta_description', 'Build your gang sheet with RJFrame')

@push('styles')
<link rel="stylesheet" href="{{ asset('theme/rjshop-theme/css/design-studio.css') }}">
<link rel="stylesheet" href="{{ asset('theme/RJFrame/css/rjframe.css') }}">
@endpush

@section('content')

@if($adminMode ?? false)
    <div class="rjf-admin-banner">
        <div class="rjf-admin-banner-inner">
            <div class="rjf-admin-banner-icon">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="rjf-admin-banner-body">
                <p class="rjf-admin-banner-title">Admin mode — editing order artwork</p>
                <p class="rjf-admin-banner-sub">
                    Order item <strong class="rjf-admin-mono">#{{ $canvasState['order_item_id'] ?? '—' }}</strong>
                    · {{ $canvasState['product_name'] ?? '' }}
                    @if(!empty($canvasState['width_inch']) && !empty($canvasState['height_inch']))
                        · {{ $canvasState['width_inch'] }} × {{ $canvasState['height_inch'] }} in
                    @endif
                </p>
            </div>
            <div class="rjf-admin-banner-actions">
                <a href="{{ url('admin/orders/' . ($canvasState['order_id'] ?? '')) }}" class="rjf-admin-link">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to order
                </a>
            </div>
        </div>
    </div>
@endif

<section id="rjHero"
         class="rj-dz"
         x-data="designStudio({
            product: {{ \Illuminate\Support\Js::from($product) }},
            allProducts: {{ \Illuminate\Support\Js::from($allProducts) }},
            canvasState: {{ \Illuminate\Support\Js::from($canvasState) }},
            adminMode: {{ ($adminMode ?? false) ? 'true' : 'false' }}
         })">

    {{-- ===================== TOOLBAR ===================== --}}
    <div class="rj-dz-toolbar">

        <div class="rjf-brand" title="RJFrame Design Studio">
            <span class="rjf-brand-mark">RJ</span>
            <span class="rjf-brand-name">Frame</span>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <label class="rj-dz-tb-btn rj-dz-tb-primary" title="Upload images (or drag & drop / paste)">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Upload</span>
                <input type="file" accept="image/png,image/jpeg,image/webp,application/pdf" multiple class="hidden" @change="onFiles($event)">
            </label>

            <button type="button" class="rj-dz-tb-btn" @click="addText()" title="Add text">
                <i class="fa-solid fa-font"></i>
                <span>Text</span>
            </button>

            <button type="button" class="rj-dz-tb-btn" @click="removeBg()" :disabled="!(kind === 'image' && single) || bgWorking">
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
            <button type="button" class="rj-dz-tb-btn" @click="flipV()" :disabled="!hasActive" title="Flip vertically">
                <i class="fa-solid fa-up-down"></i>
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
            <button type="button" class="rj-dz-tb-btn" :class="{ 'is-on': locked }" @click="toggleLockActive()" :disabled="!hasActive" title="Lock / unlock">
                <i class="fa-solid" :class="locked ? 'fa-lock' : 'fa-lock-open'"></i>
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
            <button type="button" class="rj-dz-tb-btn" @click="fillSheet()" :disabled="!single" title="Repeat the selected item to fill the sheet">
                <i class="fa-solid fa-grip"></i>
                <span>Fill</span>
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
            <button type="button" class="rj-dz-tb-btn" :class="{ 'is-on': gridOn }" @click="gridOn = !gridOn" title="Show grid">
                <i class="fa-solid fa-border-all"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" :class="{ 'is-on': snapOn }" @click="snapOn = !snapOn" title="Snap to guides and grid">
                <i class="fa-solid fa-magnet"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" :class="{ 'is-on': marginOn }" @click="marginOn = !marginOn" title="Show safe margin">
                <i class="fa-solid fa-vector-square"></i>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

        <div class="rj-dz-tb-group">
            <button type="button" class="rj-dz-tb-btn" @click="saveProject()" :disabled="itemCount === 0" title="Save project file">
                <i class="fa-solid fa-floppy-disk"></i>
            </button>
            <button type="button" class="rj-dz-tb-btn" @click="$refs.projectInput.click()" title="Open project file">
                <i class="fa-solid fa-folder-open"></i>
            </button>
            <input type="file" class="hidden" accept=".json,application/json" x-ref="projectInput" @change="openProject($event)">
            <button type="button" class="rj-dz-tb-btn" @click="helpOpen = true" title="Keyboard shortcuts">
                <i class="fa-solid fa-keyboard"></i>
            </button>
        </div>

        <div class="rj-dz-tb-sep"></div>

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

        {{-- ===================== LEFT PANEL ===================== --}}
        <aside class="rj-dz-left">
            <div class="rj-dz-left-inner">

                <div class="rjf-tabs">
                    <button type="button" class="rjf-tab" :class="{ 'is-on': tab === 'product' }" @click="tab = 'product'">
                        <i class="fa-solid fa-box"></i><span>Product</span>
                    </button>
                    <button type="button" class="rjf-tab" :class="{ 'is-on': tab === 'text' }" @click="tab = 'text'">
                        <i class="fa-solid fa-font"></i><span>Text</span>
                    </button>
                    <button type="button" class="rjf-tab" :class="{ 'is-on': tab === 'shapes' }" @click="tab = 'shapes'">
                        <i class="fa-solid fa-shapes"></i><span>Shapes</span>
                    </button>
                    <button type="button" class="rjf-tab" :class="{ 'is-on': tab === 'layers' }" @click="tab = 'layers'">
                        <i class="fa-solid fa-layer-group"></i><span>Layers</span>
                    </button>
                </div>

                {{-- ---------- Product ---------- --}}
                <div x-show="tab === 'product'">
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
                                    <span x-text="selectedMeasurement ? selectedMeasurement.label : 'No size'"></span>
                                </div>
                            </div>

                            <p class="rj-dz-left-name" x-text="product.name"></p>
                            <p class="rj-dz-left-meta">
                                <span x-text="'$' + Number(unitPrice).toFixed(2) + ' / sheet'"></span>
                                <span class="rj-dz-left-sep">·</span>
                                <span x-text="selectedMeasurement ? selectedMeasurement.label : '—'"></span>
                            </p>
                        </div>
                    </template>

                    <div class="rj-dz-left-tip">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Upload artwork, add text and shapes, arrange everything on the sheet, then add to cart.</span>
                    </div>
                </div>

                {{-- ---------- Text ---------- --}}
                <div x-show="tab === 'text'" x-cloak>
                    <button type="button" class="rjf-btn rjf-btn-primary rjf-btn-block" @click="addText()">
                        <i class="fa-solid fa-plus"></i><span>Add text box</span>
                    </button>

                    <p class="rjf-panel-title" style="margin-top:14px">Styles</p>
                    <div class="rjf-presets">
                        <template x-for="p in textPresets" :key="p.id">
                            <button type="button" class="rjf-preset" :style="presetCss(p)" @click="addText(p)" x-text="p.label"></button>
                        </template>
                    </div>

                    <p class="rjf-panel-title" style="margin-top:14px">Font for new text</p>
                    <button type="button" class="rjf-font-btn" @click="openFonts()">
                        <span :style="fontCss(defaultFont)" x-text="defaultFont"></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>

                    <div class="rjf-row">
                        <span class="rjf-sub-title">Color</span>
                        <input type="color" class="rjf-color" x-model="newTextColor">
                    </div>

                    <label class="rjf-btn rjf-btn-block" style="margin-top:12px">
                        <i class="fa-solid fa-file-arrow-up"></i><span>Upload your own font</span>
                        <input type="file" accept=".ttf,.otf,.woff,.woff2" class="hidden" @change="onFontFile($event)">
                    </label>

                    <div class="rj-dz-left-tip">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Double-click a text on the sheet to edit it directly.</span>
                    </div>
                </div>

                {{-- ---------- Shapes ---------- --}}
                <div x-show="tab === 'shapes'" x-cloak>
                    <p class="rjf-panel-title">Add a shape</p>
                    <div class="rjf-shape-grid">
                        <template x-for="s in shapes" :key="s.id">
                            <button type="button" class="rjf-shape" :title="s.label" @click="addShape(s.id)" x-html="s.svg"></button>
                        </template>
                    </div>

                    <div class="rjf-row">
                        <span class="rjf-sub-title">Fill for new shapes</span>
                        <input type="color" class="rjf-color" x-model="newShapeFill">
                    </div>
                </div>

                {{-- ---------- Layers ---------- --}}
                <div x-show="tab === 'layers'" x-cloak>
                    <p class="rjf-panel-title" x-text="'Layers (' + layers.length + ')'"></p>

                    <div class="rjf-empty" x-show="layers.length === 0">Nothing on the sheet yet.</div>

                    <div class="rjf-layers">
                        <template x-for="l in layers" :key="l.id">
                            <div class="rjf-layer" :class="{ 'is-active': l.active, 'is-hidden': !l.visible }">
                                <button type="button" class="rjf-layer-main" @click="selectLayer(l.id)">
                                    <i class="fa-solid" :class="l.kind === 'text' ? 'fa-font' : (l.kind === 'image' ? 'fa-image' : 'fa-shapes')"></i>
                                    <span x-text="l.name"></span>
                                </button>
                                <button type="button" class="rjf-ico" title="Move up" @click="moveLayer(l.id, 1)"><i class="fa-solid fa-chevron-up"></i></button>
                                <button type="button" class="rjf-ico" title="Move down" @click="moveLayer(l.id, -1)"><i class="fa-solid fa-chevron-down"></i></button>
                                <button type="button" class="rjf-ico" title="Show / hide" @click="toggleVisible(l.id)"><i class="fa-solid" :class="l.visible ? 'fa-eye' : 'fa-eye-slash'"></i></button>
                                <button type="button" class="rjf-ico" title="Lock / unlock" @click="toggleLockLayer(l.id)"><i class="fa-solid" :class="l.locked ? 'fa-lock' : 'fa-lock-open'"></i></button>
                                <button type="button" class="rjf-ico" title="Delete" @click="deleteLayer(l.id)"><i class="fa-solid fa-xmark"></i></button>
                            </div>
                        </template>
                    </div>
                </div>

                <p class="rjf-powered">Powered by <b>RJFrame</b> <span x-text="'v' + version"></span></p>
            </div>
        </aside>

        {{-- ===================== STAGE ===================== --}}
        <div class="rj-dz-stage-wrap"
             x-ref="wrap"
             @dragover.prevent="dragging = true"
             @dragleave.prevent="dragging = false"
             @drop.prevent="onDrop($event)"
             :class="{ 'rj-dz-dragging': dragging }">
            <div class="rj-dz-stage" x-ref="stage" :class="'rj-dz-bg-' + bgMode">
                <canvas id="designCanvas"></canvas>

                <div class="rjf-overlay rjf-grid" x-show="gridOn" :style="gridStyle"></div>
                <div class="rjf-overlay" x-show="marginOn">
                    <div class="rjf-margin" :style="marginStyle"></div>
                </div>
                <div class="rjf-overlay">
                    <div class="rjf-guide-v" x-show="guideV !== null" :style="vGuideStyle"></div>
                    <div class="rjf-guide-h" x-show="guideH !== null" :style="hGuideStyle"></div>
                </div>

                <div class="rj-dz-empty" x-show="itemCount === 0">
                    <i class="fa-regular fa-image"></i>
                    <p>Upload, drop or paste artwork to start — or add text and shapes</p>
                </div>
            </div>
        </div>

        {{-- ===================== RIGHT SIDEBAR ===================== --}}
        <aside class="rj-dz-sidebar">

            <div class="rj-dz-side-block" x-show="measurements.length > 0">
                <label class="rj-dz-label">Size</label>
                <div class="rj-dz-select-wrap">
                    <select class="rj-dz-select" x-ref="sizeSelect" @change="changeSize($event)"></select>
                    <i class="fa-solid fa-chevron-down"></i>
                </div>
                <p class="rj-dz-hint" x-text="selectedMeasurement ? selectedMeasurement.label : ''"></p>
            </div>

            <div class="rj-dz-side-block" x-show="measurements.length === 0" x-cloak>
                <div class="rj-dz-measure-missing">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>No sizes available for this product</span>
                </div>
            </div>

            {{-- ----- Selected object: common ----- --}}
            <div class="rj-dz-side-block rj-dz-sel" x-show="hasActive" x-cloak>
                <label class="rj-dz-label">
                    <span x-text="kindLabel"></span>
                    <span class="rj-dz-dpi" x-show="kind === 'image' && single" :class="'rj-dz-dpi-' + dpiClass" x-text="sel.dpi + ' DPI'"></span>
                </label>

                <div class="rj-dz-custom" x-show="single">
                    <input type="number" step="0.01" min="0.1" :value="sel.w" placeholder="W" @change="setSize('w', $event.target.value)">
                    <button type="button" class="rj-dz-lock" :class="{ 'is-on': lockRatio }" @click="toggleRatio()">
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
                    <button type="button" class="rj-dz-mini-btn" @click="fitToSheet()" :disabled="!single">
                        <i class="fa-solid fa-maximize"></i><span>Fit</span>
                    </button>
                    <button type="button" class="rj-dz-mini-btn" @click="alignTo('h')">
                        <i class="fa-solid fa-arrows-left-right-to-line"></i><span>Center H</span>
                    </button>
                    <button type="button" class="rj-dz-mini-btn" @click="alignTo('v')">
                        <i class="fa-solid fa-arrows-up-to-line"></i><span>Center V</span>
                    </button>
                </div>

                <div class="rjf-seg" style="margin-top:8px;width:100%">
                    <button type="button" class="rjf-btn rjf-grow" title="Align left" @click="alignTo('left')"><i class="fa-solid fa-align-left"></i></button>
                    <button type="button" class="rjf-btn rjf-grow" title="Align right" @click="alignTo('right')"><i class="fa-solid fa-align-right"></i></button>
                    <button type="button" class="rjf-btn rjf-grow" title="Align top" @click="alignTo('top')"><i class="fa-solid fa-arrow-up-long"></i></button>
                    <button type="button" class="rjf-btn rjf-grow" title="Align bottom" @click="alignTo('bottom')"><i class="fa-solid fa-arrow-down-long"></i></button>
                </div>
            </div>

            {{-- ----- Text controls ----- --}}
            <div class="rj-dz-side-block" x-show="kind === 'text'" x-cloak>
                <label class="rj-dz-label">Text</label>

                <textarea class="rjf-textarea" rows="2"
                          :value="txt.content"
                          @input="txtSet('content', $event.target.value)"
                          @change="txtDone()"></textarea>

                <div class="rjf-row">
                    <button type="button" class="rjf-font-btn" @click="openFonts()">
                        <span :style="fontCss(txt.font)" x-text="txt.font"></span>
                        <i class="fa-solid fa-chevron-down"></i>
                    </button>
                </div>

                <div class="rjf-row">
                    <div class="rjf-field rjf-grow">
                        <span>Size (pt)</span>
                        <input type="number" min="1" max="600" step="1" :value="txt.size"
                               @change="txtSet('size', $event.target.value); txtDone()">
                    </div>
                    <div class="rjf-field">
                        <span>Color</span>
                        <input type="color" class="rjf-color" :value="txt.color"
                               @input="txtSet('color', $event.target.value)" @change="txtDone()">
                    </div>
                </div>

                <div class="rjf-swatches">
                    <template x-for="c in palette" :key="'t' + c">
                        <button type="button" class="rjf-swatch" :style="'background:' + c" @click="swatchText(c)"></button>
                    </template>
                </div>

                <div class="rjf-row">
                    <div class="rjf-seg">
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.bold }" title="Bold" @click="txtToggle('bold')"><i class="fa-solid fa-bold"></i></button>
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.italic }" title="Italic" @click="txtToggle('italic')"><i class="fa-solid fa-italic"></i></button>
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.underline }" title="Underline" @click="txtToggle('underline')"><i class="fa-solid fa-underline"></i></button>
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.strike }" title="Strikethrough" @click="txtToggle('strike')"><i class="fa-solid fa-strikethrough"></i></button>
                    </div>
                </div>

                <div class="rjf-row">
                    <div class="rjf-seg">
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.align === 'left' }" @click="txtAlign('left')"><i class="fa-solid fa-align-left"></i></button>
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.align === 'center' }" @click="txtAlign('center')"><i class="fa-solid fa-align-center"></i></button>
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.align === 'right' }" @click="txtAlign('right')"><i class="fa-solid fa-align-right"></i></button>
                        <button type="button" class="rjf-btn" :class="{ 'is-on': txt.align === 'justify' }" @click="txtAlign('justify')"><i class="fa-solid fa-align-justify"></i></button>
                    </div>
                    <div class="rjf-seg">
                        <button type="button" class="rjf-btn" title="UPPERCASE" @click="txtCase('upper')">AA</button>
                        <button type="button" class="rjf-btn" title="lowercase" @click="txtCase('lower')">aa</button>
                        <button type="button" class="rjf-btn" title="Title Case" @click="txtCase('title')">Aa</button>
                    </div>
                </div>

                <div class="rjf-label-line"><span>Letter spacing</span><b x-text="txt.spacing"></b></div>
                <input type="range" class="rjf-range" min="-100" max="800" step="10" :value="txt.spacing"
                       @input="txtSet('spacing', $event.target.value)" @change="txtDone()">

                <div class="rjf-label-line"><span>Line height</span><b x-text="Number(txt.lineHeight).toFixed(2)"></b></div>
                <input type="range" class="rjf-range" min="0.6" max="3" step="0.05" :value="txt.lineHeight"
                       @input="txtSet('lineHeight', $event.target.value)" @change="txtDone()">

                <div class="rjf-sub">
                    <div class="rjf-sub-title">Outline</div>
                    <div class="rjf-row">
                        <input type="color" class="rjf-color" :value="txt.strokeColor"
                               @input="txtSet('strokeColor', $event.target.value)" @change="txtDone()">
                        <input type="range" class="rjf-range" min="0" max="20" step="1" :value="txt.strokeW"
                               @input="txtSet('strokeW', $event.target.value)" @change="txtDone()">
                        <b x-text="txt.strokeW"></b>
                    </div>
                </div>

                <div class="rjf-sub">
                    <label class="rjf-check">
                        <input type="checkbox" :checked="txt.shadowOn" @change="txtSet('shadowOn', $event.target.checked); txtDone()">
                        <span class="rjf-sub-title">Shadow / glow</span>
                    </label>

                    <div x-show="txt.shadowOn" x-cloak>
                        <div class="rjf-row">
                            <input type="color" class="rjf-color" :value="txt.shadowColor"
                                   @input="txtSet('shadowColor', $event.target.value)" @change="txtDone()">
                            <div class="rjf-grow">
                                <div class="rjf-label-line" style="margin-top:0"><span>Blur</span><b x-text="txt.shadowBlur"></b></div>
                                <input type="range" class="rjf-range" min="0" max="40" step="1" :value="txt.shadowBlur"
                                       @input="txtSet('shadowBlur', $event.target.value)" @change="txtDone()">
                            </div>
                        </div>
                        <div class="rjf-label-line"><span>Offset</span><b x-text="txt.shadowOff"></b></div>
                        <input type="range" class="rjf-range" min="0" max="30" step="1" :value="txt.shadowOff"
                               @input="txtSet('shadowOff', $event.target.value)" @change="txtDone()">
                    </div>
                </div>
            </div>

            {{-- ----- Shape controls ----- --}}
            <div class="rj-dz-side-block" x-show="kind === 'shape'" x-cloak>
                <label class="rj-dz-label">Shape</label>

                <div class="rjf-row" x-show="!shp.isLine">
                    <div class="rjf-field">
                        <span>Fill</span>
                        <input type="color" class="rjf-color" :value="shp.fill"
                               @input="shpSet('fill', $event.target.value)" @change="shpDone()">
                    </div>
                    <label class="rjf-check">
                        <input type="checkbox" :checked="shp.noFill" @change="shpSet('noFill', $event.target.checked); shpDone()">
                        <span>No fill</span>
                    </label>
                </div>

                <div class="rjf-row" x-show="shp.isLine">
                    <div class="rjf-field">
                        <span>Color</span>
                        <input type="color" class="rjf-color" :value="shp.fill"
                               @input="shpSet('fill', $event.target.value)" @change="shpDone()">
                    </div>
                </div>

                <div class="rjf-swatches">
                    <template x-for="c in palette" :key="'s' + c">
                        <button type="button" class="rjf-swatch" :style="'background:' + c" @click="swatchShape(c)"></button>
                    </template>
                </div>

                <div class="rjf-sub" x-show="!shp.isLine">
                    <div class="rjf-sub-title">Border</div>
                    <div class="rjf-row">
                        <input type="color" class="rjf-color" :value="shp.stroke"
                               @input="shpSet('stroke', $event.target.value)" @change="shpDone()">
                        <input type="range" class="rjf-range" min="0" max="30" step="1" :value="shp.strokeW"
                               @input="shpSet('strokeW', $event.target.value)" @change="shpDone()">
                        <b x-text="shp.strokeW"></b>
                    </div>
                </div>

                <div x-show="shp.isLine">
                    <div class="rjf-label-line"><span>Thickness</span><b x-text="shp.strokeW"></b></div>
                    <input type="range" class="rjf-range" min="1" max="40" step="1" :value="shp.strokeW"
                           @input="shpSet('strokeW', $event.target.value)" @change="shpDone()">
                </div>

                <div x-show="shp.isRect">
                    <div class="rjf-label-line"><span>Corner radius</span><b x-text="shp.radius"></b></div>
                    <input type="range" class="rjf-range" min="0" max="120" step="1" :value="shp.radius"
                           @input="shpSet('radius', $event.target.value)" @change="shpDone()">
                </div>
            </div>

            {{-- ----- Image adjustments ----- --}}
            <div class="rj-dz-side-block" x-show="kind === 'image'" x-cloak>
                <label class="rj-dz-label">Adjustments</label>

                <div class="rjf-label-line" style="margin-top:0"><span>Brightness</span><b x-text="img.brightness"></b></div>
                <input type="range" class="rjf-range" min="-100" max="100" step="1" :value="img.brightness"
                       @input="img.brightness = $event.target.value" @change="imgSet('brightness', $event.target.value)">

                <div class="rjf-label-line"><span>Contrast</span><b x-text="img.contrast"></b></div>
                <input type="range" class="rjf-range" min="-100" max="100" step="1" :value="img.contrast"
                       @input="img.contrast = $event.target.value" @change="imgSet('contrast', $event.target.value)">

                <div class="rjf-label-line"><span>Saturation</span><b x-text="img.saturation"></b></div>
                <input type="range" class="rjf-range" min="-100" max="100" step="1" :value="img.saturation"
                       @input="img.saturation = $event.target.value" @change="imgSet('saturation', $event.target.value)">

                <div class="rjf-label-line"><span>Blur</span><b x-text="img.blur"></b></div>
                <input type="range" class="rjf-range" min="0" max="100" step="1" :value="img.blur"
                       @input="img.blur = $event.target.value" @change="imgSet('blur', $event.target.value)">

                <div class="rjf-row" style="flex-wrap:wrap">
                    <button type="button" class="rjf-btn" :class="{ 'is-on': img.grayscale }" @click="imgSet('grayscale', !img.grayscale)">Gray</button>
                    <button type="button" class="rjf-btn" :class="{ 'is-on': img.sepia }" @click="imgSet('sepia', !img.sepia)">Sepia</button>
                    <button type="button" class="rjf-btn" :class="{ 'is-on': img.invert }" @click="imgSet('invert', !img.invert)">Invert</button>
                    <button type="button" class="rjf-btn" @click="imgReset()"><i class="fa-solid fa-rotate-left"></i><span>Reset</span></button>
                </div>
            </div>

            @if(!($adminMode ?? false))
                <div class="rj-dz-side-block">
                    <label class="rj-dz-label">Quantity</label>
                    <div class="rj-dz-qty">
                        <button type="button" @click="decQty()">−</button>
                        <input type="number" x-model.number="qty" min="1" max="999" @blur="normalizeQty()">
                        <button type="button" @click="incQty()">+</button>
                    </div>
                </div>
            @endif

            <div class="rj-dz-side-spacer"></div>

            <div class="rj-dz-warn" x-show="outCount > 0" x-cloak>
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span x-text="outCount + ' item(s) extend beyond the sheet and will be cut off.'"></span>
            </div>
            <div class="rj-dz-warn rj-dz-warn-soft" x-show="lowDpiCount > 0" x-cloak>
                <i class="fa-solid fa-circle-exclamation"></i>
                <span x-text="lowDpiCount + ' item(s) below 100 DPI may print blurry.'"></span>
            </div>

            @if(!($adminMode ?? false))
                <div class="rj-dz-side-total">
                    <div class="rj-dz-total-row">
                        <span>Unit price</span>
                        <span x-text="'$' + unitPrice.toFixed(2)"></span>
                    </div>
                    <div class="rj-dz-total-row">
                        <span x-text="'Quantity × ' + (qty || 1)"></span>
                        <span x-text="'$' + (unitPrice * (qty || 1)).toFixed(2)"></span>
                    </div>
                    <div class="rj-dz-total-row rj-dz-total-grand">
                        <span>Total</span>
                        <span x-text="'$' + totalPrice.toFixed(2)"></span>
                    </div>
                </div>

                <button type="button"
                        class="rj-dz-add"
                        :disabled="itemCount === 0 || adding || !selectedMeasurement"
                        @click="addToCart()">
                    <i class="fa-solid" :class="adding ? 'fa-spinner fa-spin' : 'fa-cart-plus'"></i>
                    <span x-text="adding ? 'Adding...' : (itemCount === 0 ? 'Add something to start' : 'Add to Cart')"></span>
                </button>
            @else
                <button type="button"
                        class="rj-dz-add rj-dz-add-admin"
                        :disabled="itemCount === 0 || adminSaving"
                        @click="saveToOrder()">
                    <i class="fa-solid" :class="adminSaving ? 'fa-spinner fa-spin' : 'fa-floppy-disk'"></i>
                    <span x-text="adminSaving ? 'Saving...' : 'Save changes to order'"></span>
                </button>
            @endif
        </aside>

    </div>

    {{-- ===================== FONT PICKER ===================== --}}
    <div class="rjf-modal" x-show="fontOpen" x-cloak @keydown.escape.window="fontOpen = false">
        <div class="rjf-modal-backdrop" @click="fontOpen = false"></div>
        <div class="rjf-modal-card">
            <div class="rjf-modal-head">
                <span x-text="'Choose a font (' + filteredFonts.length + ')'"></span>
                <button type="button" class="rjf-ico" @click="fontOpen = false"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="rjf-modal-body">
                <input type="text" class="rjf-input" placeholder="Search fonts..." x-model="fontQuery">
                <input type="text" class="rjf-input" style="margin-top:8px" placeholder="Preview text (optional)" x-model="fontSample">

                <div class="rjf-chips">
                    <template x-for="c in fontCats" :key="c">
                        <button type="button" class="rjf-chip" :class="{ 'is-on': fontCat === c }" @click="fontCat = c" x-text="c"></button>
                    </template>
                </div>

                <div class="rjf-font-list">
                    <template x-for="f in filteredFonts" :key="f.name">
                        <button type="button" class="rjf-font-item"
                                :class="{ 'is-on': (kind === 'text' ? txt.font : defaultFont) === f.name }"
                                @click="pickFont(f.name)">
                            <span class="rjf-font-name" x-text="f.name"></span>
                            <span class="rjf-font-sample" :style="fontCss(f.name)" x-text="fontSample || f.name"></span>
                        </button>
                    </template>
                </div>

                <div class="rjf-empty" x-show="filteredFonts.length === 0">No fonts match your search.</div>
            </div>
        </div>
    </div>

    {{-- ===================== SHORTCUTS ===================== --}}
    <div class="rjf-modal" x-show="helpOpen" x-cloak @keydown.escape.window="helpOpen = false">
        <div class="rjf-modal-backdrop" @click="helpOpen = false"></div>
        <div class="rjf-modal-card">
            <div class="rjf-modal-head">
                <span>RJFrame shortcuts</span>
                <button type="button" class="rjf-ico" @click="helpOpen = false"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="rjf-modal-body">
                <table class="rjf-keys">
                    <template x-for="s in shortcuts" :key="s[0]">
                        <tr><td x-text="s[0]"></td><td x-text="s[1]"></td></tr>
                    </template>
                </table>
            </div>
        </div>
    </div>
</section>

<style>
    .rjf-admin-banner {
        background: linear-gradient(135deg, rgba(168, 85, 247, 0.12), rgba(99, 102, 241, 0.12));
        border-bottom: 1px solid rgba(168, 85, 247, 0.3);
        padding: 0.875rem 1.5rem;
    }
    .rjf-admin-banner-inner {
        max-width: 88rem;
        margin: 0 auto;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .rjf-admin-banner-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, #a855f7, #6366f1);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .rjf-admin-banner-body { flex: 1; min-width: 0; }
    .rjf-admin-banner-title {
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 2px;
    }
    .rjf-admin-banner-sub {
        font-size: 12px;
        color: #c7d2fe;
        margin: 0;
    }
    .rjf-admin-mono { font-family: ui-monospace, monospace; color: #fff; }
    .rjf-admin-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 8px;
        color: #e5e7eb;
        text-decoration: none;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .rjf-admin-link:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
    }
    .rjf-admin-link i { font-size: 10px; }

    .rj-dz-add-admin {
        background: linear-gradient(135deg, #a855f7, #6366f1) !important;
        color: #fff !important;
    }
    .rj-dz-add-admin:hover:not(:disabled) {
        filter: brightness(1.1);
        box-shadow: 0 0 40px rgba(168, 85, 247, 0.5);
    }

    @media (max-width: 640px) {
        .rjf-admin-banner-inner { flex-wrap: wrap; }
        .rjf-admin-banner-actions { width: 100%; }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
<script src="{{ asset('theme/RJFrame/js/rjframe.js') }}"></script>

@endsection

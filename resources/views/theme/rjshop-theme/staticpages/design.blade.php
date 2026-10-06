@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', 'Design Studio')
@section('meta_description', 'Upload your design and get instant pricing')

@section('content')

<section class="relative bg-[#05030f] min-h-screen text-white overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03]" style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 50px 50px;"></div>
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-pink-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-14">

        <div class="text-center mb-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 backdrop-blur-md border border-white/10 mb-4">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-indigo-300">Design Studio</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight mb-3">
                Build your <span class="bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">gang sheet</span>
            </h1>
            <p class="text-gray-400 max-w-xl mx-auto">Upload your artwork, arrange it on the canvas, and see instant pricing.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2">

                <div class="bg-white/[0.02] border border-white/10 rounded-2xl p-4 mb-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="cursor-pointer inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:shadow-[0_0_20px_rgba(99,102,241,0.5)] transition-all">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                            <span>Upload Image</span>
                            <input type="file" id="imageInput" accept="image/png,image/jpeg,image/webp" class="hidden" multiple>
                        </label>

                        <div class="h-8 w-px bg-white/10"></div>

                        <div class="flex items-center gap-2">
                            <label class="text-xs text-gray-400 font-mono uppercase tracking-wider">Size</label>
                            <select id="sheetSize" class="bg-white/5 border border-white/10 rounded-lg text-sm text-white px-3 py-2 focus:outline-none focus:border-indigo-500/60">
                                <option value="a4">A4 (8.3 × 11.7 in)</option>
                                <option value="a3">A3 (11.7 × 16.5 in)</option>
                                <option value="12x12">12 × 12 in</option>
                                <option value="12x24">12 × 24 in</option>
                                <option value="13x19">13 × 19 in</option>
                                <option value="22x24">22 × 24 in</option>
                            </select>
                        </div>

                        <div class="h-8 w-px bg-white/10"></div>

                        <button id="clearBtn" class="inline-flex items-center gap-2 text-sm text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/10 px-4 py-2 rounded-lg transition">
                            <i class="fa-solid fa-trash text-xs"></i>
                            <span>Clear</span>
                        </button>
                    </div>
                </div>

                <div class="relative bg-white/[0.02] border border-white/10 rounded-2xl p-4 overflow-hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                            <span class="font-mono text-[10px] uppercase tracking-widest text-gray-500">Canvas</span>
                        </div>
                        <div class="font-mono text-[10px] text-gray-500">
                            <span id="dimensions">0 × 0</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-center bg-[#0a0715] rounded-xl p-4 relative" style="min-height: 500px;">
                        <div id="sheetFrame" class="relative bg-white rounded-md shadow-2xl transition-all" style="width: 400px; height: 565px;">
                            <canvas id="designCanvas" width="400" height="565" class="absolute inset-0 w-full h-full rounded-md"></canvas>
                            <div id="dropHint" class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none opacity-30">
                                <i class="fa-solid fa-image text-gray-400 text-5xl mb-3"></i>
                                <p class="text-gray-500 text-sm">Drop image here or click Upload</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-2 mt-3 text-[10px] text-gray-500 font-mono uppercase tracking-wider">
                        <i class="fa-solid fa-arrows-up-down-left-right"></i>
                        <span>Drag to move</span>
                        <span class="mx-2">·</span>
                        <i class="fa-solid fa-expand"></i>
                        <span>Scroll to resize</span>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white/[0.02] border border-white/10 rounded-2xl p-6 sticky top-24">
                    <div class="flex items-center gap-2 mb-5">
                        <span class="font-mono text-[10px] uppercase tracking-widest text-indigo-400">// Order Summary</span>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Sheet size</span>
                            <span id="summarySize" class="font-mono text-white">A4</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Images</span>
                            <span id="summaryCount" class="font-mono text-white">0</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Coverage</span>
                            <span id="summaryCoverage" class="font-mono text-white">0%</span>
                        </div>
                    </div>

                    <div class="border-t border-white/10 pt-5 mb-6">
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-gray-400 text-sm">Total</span>
                            <div class="text-right">
                                <span class="text-3xl font-black text-white">$<span id="totalPrice">0.00</span></span>
                            </div>
                        </div>
                        <div class="font-mono text-[10px] text-gray-500 text-right">per sheet</div>
                    </div>

                    <div class="space-y-3 mb-5">
                        <div>
                            <label class="block font-mono text-[10px] uppercase tracking-widest text-gray-500 mb-2">Quantity</label>
                            <div class="flex items-center gap-2">
                                <button id="qtyMinus" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 text-white hover:bg-white/10 transition">−</button>
                                <input type="number" id="qty" value="1" min="1" max="999" class="flex-1 bg-white/5 border border-white/10 rounded-lg text-center text-white font-mono py-2.5 focus:outline-none focus:border-indigo-500/60">
                                <button id="qtyPlus" class="w-10 h-10 rounded-lg bg-white/5 border border-white/10 text-white hover:bg-white/10 transition">+</button>
                            </div>
                        </div>
                    </div>

                    <button id="addToCartBtn" class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-white font-semibold py-4 rounded-xl hover:shadow-[0_0_40px_rgba(168,85,247,0.5)] hover:scale-[1.02] transition-all disabled:opacity-40 disabled:cursor-not-allowed disabled:hover:scale-100" disabled>
                        <i class="fa-solid fa-cart-plus"></i>
                        <span>Add to Cart</span>
                    </button>

                    <div class="mt-4 text-center font-mono text-[10px] text-gray-600 uppercase tracking-widest">
                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full inline-block mr-1.5 animate-pulse"></span>
                        Ready to print
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<style>
    @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.4; } }
</style>

<script>
(function() {
    var canvas = document.getElementById('designCanvas');
    var ctx = canvas.getContext('2d');
    var dropHint = document.getElementById('dropHint');
    var imageInput = document.getElementById('imageInput');
    var sheetSize = document.getElementById('sheetSize');
    var sheetFrame = document.getElementById('sheetFrame');
    var clearBtn = document.getElementById('clearBtn');
    var dimensions = document.getElementById('dimensions');
    var summarySize = document.getElementById('summarySize');
    var summaryCount = document.getElementById('summaryCount');
    var summaryCoverage = document.getElementById('summaryCoverage');
    var totalPrice = document.getElementById('totalPrice');
    var qtyInput = document.getElementById('qty');
    var qtyMinus = document.getElementById('qtyMinus');
    var qtyPlus = document.getElementById('qtyPlus');
    var addToCartBtn = document.getElementById('addToCartBtn');

    var sizes = {
        a4: { w: 400, h: 565, label: 'A4', basePrice: 4.50 },
        a3: { w: 565, h: 800, label: 'A3', basePrice: 7.50 },
        '12x12': { w: 565, h: 565, label: '12×12', basePrice: 6.00 },
        '12x24': { w: 565, h: 1130, label: '12×24', basePrice: 10.00 },
        '13x19': { w: 612, h: 894, label: '13×19', basePrice: 9.00 },
        '22x24': { w: 1035, h: 1130, label: '22×24', basePrice: 18.00 }
    };

    var images = [];
    var activeImage = null;
    var dragging = false;
    var dragOffset = { x: 0, y: 0 };
    var currentSize = 'a4';

    function resizeCanvas() {
        var s = sizes[currentSize];
        canvas.width = s.w;
        canvas.height = s.h;
        sheetFrame.style.width = Math.min(s.w, 500) + 'px';
        sheetFrame.style.height = (Math.min(s.w, 500) / s.w * s.h) + 'px';
        dimensions.textContent = s.w + ' × ' + s.h + ' px';
        summarySize.textContent = s.label;
        draw();
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        var scale = canvas.width / parseFloat(sheetFrame.style.width || canvas.width);
        var displayScale = canvas.width / (parseFloat(sheetFrame.style.width) || canvas.width);

        images.forEach(function(img, i) {
            var x = img.x * displayScale;
            var y = img.y * displayScale;
            var w = img.w * displayScale;
            var h = img.h * displayScale;
            var rot = img.rotation || 0;

            ctx.save();
            ctx.translate(x + w / 2, y + h / 2);
            ctx.rotate(rot * Math.PI / 180);
            ctx.drawImage(img.el, -w / 2, -h / 2, w, h);

            if (i === images.indexOf(activeImage)) {
                ctx.strokeStyle = '#6366f1';
                ctx.lineWidth = 2 / displayScale;
                ctx.setLineDash([6 / displayScale, 4 / displayScale]);
                ctx.strokeRect(-w / 2 - 4, -h / 2 - 4, w + 8, h + 8);
                ctx.setLineDash([]);

                ctx.fillStyle = '#6366f1';
                var handles = [[-w/2 - 4, -h/2 - 4], [w/2 + 4, -h/2 - 4], [-w/2 - 4, h/2 + 4], [w/2 + 4, h/2 + 4]];
                var hs = 8 / displayScale;
                handles.forEach(function(h) {
                    ctx.fillRect(h[0] - hs/2, h[1] - hs/2, hs, hs);
                });
            }
            ctx.restore();
        });

        var totalArea = 0;
        images.forEach(function(img) { totalArea += img.w * img.h; });
        var coverage = Math.min(100, Math.round(totalArea / (canvas.width * canvas.height) * 100));
        summaryCoverage.textContent = coverage + '%';

        updatePrice(coverage);
    }

    function updatePrice(coverage) {
        var s = sizes[currentSize];
        var price = s.basePrice;
        if (coverage > 30) price += (coverage - 30) * 0.08;
        price = price * Math.max(1, Math.log2(images.length + 1));
        totalPrice.textContent = price.toFixed(2);
    }

    function addImage(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = new Image();
            img.onload = function() {
                var displayScale = canvas.width / (parseFloat(sheetFrame.style.width) || canvas.width);
                var maxW = canvas.width * 0.6;
                var maxH = canvas.height * 0.6;
                var ratio = Math.min(maxW / img.width, maxH / img.height, 1);
                var w = img.width * ratio / displayScale;
                var h = img.height * ratio / displayScale;

                var obj = {
                    el: img,
                    x: canvas.width / displayScale / 2 - w / 2,
                    y: canvas.height / displayScale / 2 - h / 2,
                    w: w,
                    h: h,
                    rotation: 0
                };
                images.push(obj);
                activeImage = obj;
                dropHint.style.opacity = '0';
                summaryCount.textContent = images.length;
                addToCartBtn.disabled = false;
                draw();
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function getCanvasCoords(e) {
        var rect = sheetFrame.getBoundingClientRect();
        var clientX = e.touches ? e.touches[0].clientX : e.clientX;
        var clientY = e.touches ? e.touches[0].clientY : e.clientY;
        var x = clientX - rect.left;
        var y = clientY - rect.top;
        var scale = canvas.width / rect.width;
        return { x: x * scale, y: y * scale, displayScale: scale };
    }

    function hitTest(cx, cy) {
        for (var i = images.length - 1; i >= 0; i--) {
            var img = images[i];
            if (cx >= img.x && cx <= img.x + img.w && cy >= img.y && cy <= img.y + img.h) {
                return img;
            }
        }
        return null;
    }

    sheetFrame.addEventListener('mousedown', function(e) {
        var c = getCanvasCoords(e);
        var hit = hitTest(c.x, c.y);
        if (hit) {
            activeImage = hit;
            dragging = true;
            dragOffset.x = c.x - hit.x;
            dragOffset.y = c.y - hit.y;
            draw();
        }
    });

    window.addEventListener('mousemove', function(e) {
        if (!dragging || !activeImage) return;
        var c = getCanvasCoords(e);
        activeImage.x = c.x - dragOffset.x;
        activeImage.y = c.y - dragOffset.y;
        draw();
    });

    window.addEventListener('mouseup', function() {
        dragging = false;
    });

    sheetFrame.addEventListener('wheel', function(e) {
        if (!activeImage) return;
        e.preventDefault();
        var factor = e.deltaY > 0 ? 0.95 : 1.05;
        activeImage.w *= factor;
        activeImage.h *= factor;
        draw();
    }, { passive: false });

    sheetFrame.addEventListener('touchstart', function(e) {
        var c = getCanvasCoords(e);
        var hit = hitTest(c.x, c.y);
        if (hit) {
            activeImage = hit;
            dragging = true;
            dragOffset.x = c.x - hit.x;
            dragOffset.y = c.y - hit.y;
            draw();
        }
    }, { passive: true });

    sheetFrame.addEventListener('touchmove', function(e) {
        if (!dragging || !activeImage) return;
        var c = getCanvasCoords(e);
        activeImage.x = c.x - dragOffset.x;
        activeImage.y = c.y - dragOffset.y;
        draw();
    }, { passive: true });

    sheetFrame.addEventListener('touchend', function() {
        dragging = false;
    });

    imageInput.addEventListener('change', function(e) {
        Array.from(e.target.files).forEach(addImage);
    });

    sheetSize.addEventListener('change', function() {
        currentSize = this.value;
        resizeCanvas();
    });

    clearBtn.addEventListener('click', function() {
        images = [];
        activeImage = null;
        summaryCount.textContent = '0';
        summaryCoverage.textContent = '0%';
        totalPrice.textContent = '0.00';
        addToCartBtn.disabled = true;
        dropHint.style.opacity = '0.3';
        draw();
    });

    qtyMinus.addEventListener('click', function() {
        var v = parseInt(qtyInput.value) || 1;
        if (v > 1) qtyInput.value = v - 1;
    });
    qtyPlus.addEventListener('click', function() {
        var v = parseInt(qtyInput.value) || 1;
        qtyInput.value = v + 1;
    });

    addToCartBtn.addEventListener('click', function() {
        alert('Added to cart: ' + images.length + ' design(s), ' + qtyInput.value + ' sheet(s)');
    });

    resizeCanvas();
})();
</script>

@endsection

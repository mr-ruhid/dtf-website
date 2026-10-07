<div x-data
     x-cloak
     @keydown.escape.window="$store.cart.close()">

    <div x-show="$store.cart.isOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="$store.cart.close()"
         class="fixed inset-0 z-[90] bg-black/60 backdrop-blur-sm">
    </div>

    <div x-show="$store.cart.isOpen"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         class="fixed top-0 right-0 bottom-0 z-[95] w-full max-w-[420px] flex flex-col bg-[#05030f] border-l border-indigo-500/20 shadow-[-20px_0_60px_rgba(0,0,0,0.6)]">

        <div class="absolute inset-0 opacity-[0.025] pointer-events-none"
             style="background-image: linear-gradient(rgba(99,102,241,0.5) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.5) 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="relative flex items-center justify-between px-5 py-4 border-b border-white/5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500/20 to-pink-500/20 border border-indigo-500/30 flex items-center justify-center">
                    <i class="fa-solid fa-bag-shopping text-indigo-300 text-sm"></i>
                </div>
                <div>
                    <p class="font-mono text-[10px] text-indigo-400 uppercase tracking-[0.25em]">// Cart</p>
                    <p class="text-white font-bold text-sm">
                        <span x-text="$store.cart.count"></span>
                        <span x-text="$store.cart.count === 1 ? 'item' : 'items'"></span>
                    </p>
                </div>
            </div>
            <button type="button"
                    @click="$store.cart.close()"
                    class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/5 transition"
                    aria-label="Close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="relative flex-1 overflow-y-auto px-5 py-5">

            <template x-if="$store.cart.items.length === 0">
                <div class="h-full flex flex-col items-center justify-center text-center gap-3">
                    <div class="w-16 h-16 rounded-full bg-white/[0.03] border border-white/10 flex items-center justify-center">
                        <i class="fa-solid fa-bag-shopping text-gray-600 text-xl"></i>
                    </div>
                    <p class="text-white font-semibold text-sm">Your cart is empty</p>
                    <p class="text-gray-500 text-xs font-mono">// Add something to get started</p>
                </div>
            </template>

            <template x-if="$store.cart.items.length > 0">
                <div class="space-y-3">
                    <template x-for="item in $store.cart.items" :key="item.key">
                        <div class="group flex gap-3 p-3 rounded-xl bg-white/[0.015] border border-white/5 hover:border-indigo-500/30 transition">

                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-[#0a0715] border border-white/5 shrink-0">
                                <template x-if="item.image">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!item.image">
                                    <div class="w-full h-full flex items-center justify-center text-white/10">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                </template>
                            </div>

                            <div class="flex-1 min-w-0 flex flex-col gap-1.5">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-white text-[13px] font-semibold leading-snug line-clamp-2" x-text="item.name"></p>
                                    <button type="button"
                                            @click="$store.cart.remove(item.key)"
                                            class="text-gray-500 hover:text-pink-400 transition shrink-0"
                                            aria-label="Remove">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>

                                <template x-if="item.attributes && Object.keys(item.attributes).length">
                                    <p class="font-mono text-[10px] text-gray-500 truncate"
                                       x-text="Object.entries(item.attributes).map(([k,v]) => k + ': ' + v).join(' · ')"></p>
                                </template>

                                <div class="flex items-center justify-between mt-auto pt-1 gap-2">
                                    <div class="inline-flex items-center rounded-lg bg-white/[0.03] border border-white/10">
                                        <button type="button"
                                                @click="$store.cart.update(item.key, item.qty - 1)"
                                                class="w-7 h-7 text-gray-400 hover:text-white hover:bg-indigo-500/15 transition rounded-l-lg shrink-0"
                                                :disabled="item.qty <= 1">−</button>
                                        <input type="number"
                                               :value="item.qty"
                                               min="1"
                                               max="9999"
                                               @change="$store.cart.update(item.key, $event.target.value)"
                                               @keydown.enter="$event.target.blur()"
                                               @focus="$event.target.select()"
                                               class="h-7 text-center text-white text-xs font-mono font-bold bg-transparent border-0 outline-none focus:bg-indigo-500/10 transition shrink-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                               :style="'width: ' + (String(item.qty).length > 2 ? (String(item.qty).length * 9 + 10) + 'px' : '44px')">
                                        <button type="button"
                                                @click="$store.cart.update(item.key, item.qty + 1)"
                                                class="w-7 h-7 text-gray-400 hover:text-white hover:bg-indigo-500/15 transition rounded-r-lg shrink-0">+</button>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="font-mono text-white text-sm font-bold">$<span x-text="Number(item.total).toFixed(2)"></span></p>
                                        <p class="font-mono text-gray-500 text-[10px]">$<span x-text="Number(item.unit_price).toFixed(2)"></span> ea</p>
                                    </div>
                                </div>

                                <template x-if="item.tier_label">
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
                                        <span class="font-mono text-[9px] text-emerald-400 uppercase tracking-wider" x-text="item.tier_label"></span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

        </div>

        <div class="relative border-t border-white/5 px-5 py-5 space-y-4 bg-[#05030f]">

            <div class="flex items-center justify-between">
                <span class="font-mono text-[10px] text-indigo-400 uppercase tracking-[0.3em]">// Subtotal</span>
                <span class="font-mono text-white text-lg font-bold">$<span x-text="Number($store.cart.subtotal).toFixed(2)"></span></span>
            </div>

            <div class="flex items-center justify-between text-[11px] text-gray-500 font-mono">
                <span>Shipping & tax</span>
                <span>Calculated at checkout</span>
            </div>

            <div class="flex flex-col gap-2">
                <a href="/checkout"
                   class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-full bg-white text-[#05030f] text-sm font-bold hover:bg-indigo-50 transition"
                   :class="$store.cart.items.length === 0 ? 'opacity-40 pointer-events-none' : ''">
                    <span>Proceed to Checkout</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>

                <button type="button"
                        @click="$store.cart.clear()"
                        class="inline-flex items-center justify-center gap-2 w-full px-5 py-2.5 rounded-full bg-white/[0.03] border border-white/10 text-gray-400 text-xs font-semibold hover:text-pink-400 hover:border-pink-500/30 transition"
                        x-show="$store.cart.items.length > 0">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span>Clear cart</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('cart', {
        isOpen: false,
        items: [],
        count: 0,
        subtotal: 0,
        loading: false,

        open() {
            this.isOpen = true;
            this.fetch();
        },

        close() {
            this.isOpen = false;
        },

        toggle() {
            this.isOpen ? this.close() : this.open();
        },

        csrf() {
            const m = document.querySelector('meta[name="csrf-token"]');
            return m ? m.content : '';
        },

        headers(json = false) {
            const h = {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.csrf(),
            };
            if (json) h['Content-Type'] = 'application/json';
            return h;
        },

        apply(d) {
            this.items = d.items || [];
            this.count = d.count || 0;
            this.subtotal = d.subtotal || 0;
        },

        async fetch() {
            try {
                const r = await fetch('/cart/items', { headers: this.headers() });
                this.apply(await r.json());
            } catch (e) {}
        },

        async update(key, qty) {
            qty = Math.max(1, Math.min(9999, parseInt(qty) || 1));
            try {
                const r = await fetch('/cart/update/' + key, {
                    method: 'PUT',
                    headers: this.headers(true),
                    body: JSON.stringify({ qty })
                });
                this.apply(await r.json());
            } catch (e) {}
        },

        async remove(key) {
            try {
                const r = await fetch('/cart/remove/' + key, {
                    method: 'DELETE',
                    headers: this.headers()
                });
                this.apply(await r.json());
            } catch (e) {}
        },

        async clear() {
            try {
                const r = await fetch('/cart/clear', {
                    method: 'POST',
                    headers: this.headers()
                });
                this.apply(await r.json());
            } catch (e) {}
        },

        async add(payload) {
            try {
                const r = await fetch('/cart/add', {
                    method: 'POST',
                    headers: this.headers(true),
                    body: JSON.stringify(payload)
                });
                const d = await r.json();
                this.apply(d);
                this.isOpen = true;
                return d;
            } catch (e) {
                return { success: false };
            }
        }
    });
});
</script>

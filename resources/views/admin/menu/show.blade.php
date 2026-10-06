@extends('admin.app')

@section('title', 'Edit Menu')

@section('content')

<div x-data="menuEditor({
    menuId: {{ $menu->id }},
    items: {{ json_encode($menu->items->map(fn($i) => [
        'id' => $i->id,
        'label' => $i->label,
        'url' => $i->url,
        'target' => $i->target,
        'icon' => $i->icon,
        'status' => (bool) $i->status,
        'sort_order' => $i->sort_order,
        'parent_id' => $i->parent_id,
        'children' => $i->children->map(fn($c) => [
            'id' => $c->id,
            'label' => $c->label,
            'url' => $c->url,
            'target' => $c->target,
            'icon' => $c->icon,
            'status' => (bool) $c->status,
            'sort_order' => $c->sort_order,
        ])->values(),
    ])->values()) }},
    pages: {{ json_encode($pages->map(fn($p) => [
        'id' => $p->id,
        'title' => $p->title,
        'slug' => $p->slug,
        'key' => $p->key,
    ])->values()) }}
})">

    <div class="mb-6">
        <a href="{{ route('admin.menus.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to menus
        </a>
        <div class="flex items-center gap-3 mt-2 flex-wrap">
            <h2 class="text-xl font-semibold text-gray-800">{{ $menu->name }}</h2>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700 font-mono">
                {{ $menu->location }}
            </span>
            @if (!$menu->status)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-medium bg-gray-200 text-gray-600">
                    Inactive
                </span>
            @endif
        </div>
        <p class="text-xs text-gray-400 font-mono mt-1">{{ $menu->slug }}</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden sticky top-24">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-indigo-500"></i>
                        Available Pages
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">Click to add to the menu</p>
                </div>

                <div class="max-h-[600px] overflow-y-auto">
                    <ul class="divide-y divide-gray-100">
                        @foreach ($pages as $page)
                            <li>
                                <button type="button"
                                        @click="addFromPage({{ $page->id }})"
                                        class="w-full px-5 py-3 flex items-center gap-3 hover:bg-indigo-50/40 transition text-left group">
                                    <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-indigo-100 flex items-center justify-center shrink-0 transition">
                                        <i class="fa-solid fa-file text-gray-400 group-hover:text-indigo-600 text-xs transition"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $page->title }}</p>
                                        <p class="text-[10px] text-gray-400 font-mono truncate">{{ $page->slug }}</p>
                                    </div>
                                    <i class="fa-solid fa-plus text-gray-300 group-hover:text-indigo-600 text-xs transition"></i>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 space-y-2">
                    <button type="button" @click="openCustomModal(null)"
                            class="w-full inline-flex items-center justify-center gap-2 text-xs font-medium text-indigo-600 hover:text-indigo-700 py-2 rounded-lg border border-dashed border-indigo-200 hover:bg-indigo-50 transition">
                        <i class="fa-solid fa-link text-[10px]"></i> Add Custom Link
                    </button>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-bars-staggered text-indigo-500"></i>
                            Menu Structure
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">Drag to reorder · items are saved instantly</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-mono text-gray-400 uppercase tracking-wider">Items</span>
                        <span class="text-sm font-bold text-gray-800" x-text="totalItems"></span>
                    </div>
                </div>

                <div x-show="items.length === 0" class="py-16 text-center">
                    <i class="fa-solid fa-bars text-3xl text-gray-200 mb-3"></i>
                    <p class="text-sm text-gray-500 mb-1">Menu is empty</p>
                    <p class="text-xs text-gray-400">Add pages from the left panel</p>
                </div>

                <ul x-show="items.length > 0" id="menuSortable" class="divide-y divide-gray-100 min-h-[200px]">
                    <template x-for="(item, index) in items" :key="item.id">
                        <li :data-id="item.id" :data-parent-id="item.parent_id || ''" class="group">
                            <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50/60 transition">
                                <div class="text-gray-300 cursor-grab drag-handle">
                                    <i class="fa-solid fa-grip-vertical text-sm"></i>
                                </div>

                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                                     :class="item.status ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-400'">
                                    <i class="fa-solid text-xs" :class="item.icon || 'fa-link'"></i>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium text-gray-800 truncate" x-text="item.label"></p>
                                        <span x-show="!item.status" class="text-[9px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-medium">Off</span>
                                        <span x-show="item.target === '_blank'" class="text-[9px] bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded font-medium">
                                            <i class="fa-solid fa-external-link text-[8px]"></i> new tab
                                        </span>
                                    </div>
                                    <p class="text-[10px] text-gray-400 font-mono truncate" x-text="item.url"></p>
                                </div>

                                <div class="flex items-center gap-1">
                                    <button type="button" @click="openCustomModal(item)"
                                            class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition"
                                            title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </button>

                                    <button type="button" @click="toggleItem(item)"
                                            class="w-8 h-8 flex items-center justify-center rounded transition"
                                            :class="item.status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100'"
                                            title="Toggle status">
                                        <i class="fa-solid text-base" :class="item.status ? 'fa-toggle-on' : 'fa-toggle-off'"></i>
                                    </button>

                                    <button type="button" @click="removeItem(item)"
                                            class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition"
                                            title="Remove">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <template x-if="item.children && item.children.length">
                                <ul class="bg-gray-50/60 border-t border-gray-100">
                                    <template x-for="(child, childIndex) in item.children" :key="child.id">
                                        <li :data-id="child.id" :data-parent-id="item.id" class="group">
                                            <div class="pl-14 pr-5 py-2.5 flex items-center gap-3 hover:bg-white transition">
                                                <div class="text-gray-300 cursor-grab drag-handle">
                                                    <i class="fa-solid fa-grip-vertical text-xs"></i>
                                                </div>
                                                <i class="fa-solid fa-turn-up text-gray-300 text-xs rotate-90"></i>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-gray-700 truncate" x-text="child.label"></p>
                                                    <p class="text-[10px] text-gray-400 font-mono truncate" x-text="child.url"></p>
                                                </div>
                                                <div class="flex items-center gap-1">
                                                    <button type="button" @click="openCustomModal(child)"
                                                            class="w-7 h-7 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                                        <i class="fa-solid fa-pen text-[10px]"></i>
                                                    </button>
                                                    <button type="button" @click="toggleItem(child)"
                                                            class="w-7 h-7 flex items-center justify-center rounded transition"
                                                            :class="child.status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100'">
                                                        <i class="fa-solid text-sm" :class="child.status ? 'fa-toggle-on' : 'fa-toggle-off'"></i>
                                                    </button>
                                                    <button type="button" @click="removeItem(child)"
                                                            class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </template>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

    </div>

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Item' : 'Add Item'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form :action="editing ? '{{ url('admin/menus/' . $menu->id . '/items') }}/' + editing.id : '{{ route('admin.menus.items.store', $menu) }}'"
                  method="POST" class="p-6 space-y-4">
                @csrf
                <template x-if="editing">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Label <span class="text-red-500">*</span></label>
                    <input type="text" name="label" x-model="form.label" required maxlength="100"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL <span class="text-red-500">*</span></label>
                    <input type="text" name="url" x-model="form.url" required maxlength="255"
                           placeholder="/about-us or https://..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Target</label>
                        <select name="target" x-model="form.target"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="_self">Same tab</option>
                            <option value="_blank">New tab</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Parent</label>
                        <select name="parent_id" x-model="form.parent_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">— None (top-level) —</option>
                            <template x-for="it in items" :key="it.id">
                                <option :value="it.id" x-show="!editing || editing.id !== it.id" x-text="it.label"></option>
                            </template>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Icon (optional)</label>
                    <input type="text" name="icon" x-model="form.icon" maxlength="100"
                           placeholder="fa-print"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Font Awesome class (without 'fa-solid')</p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="status" value="1" x-model="form.status"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Active
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2 border-t border-gray-100">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editing ? 'Update' : 'Add'"></span>
                    </button>
                    <button type="button" @click="showModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <form x-ref="deleteForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form x-ref="toggleForm" method="POST" class="hidden">
        @csrf
        @method('PUT')
    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function menuEditor(config) {
    return {
        menuId: config.menuId,
        items: config.items,
        pages: config.pages,
        showModal: false,
        editing: null,
        form: {
            label: '',
            url: '',
            target: '_self',
            icon: '',
            parent_id: '',
            status: true,
        },

        get totalItems() {
            return this.items.reduce((sum, it) => sum + 1 + (it.children ? it.children.length : 0), 0);
        },

        init() {
            this.$nextTick(() => {
                const el = document.getElementById('menuSortable');
                if (el) {
                    Sortable.create(el, {
                        handle: '.drag-handle',
                        animation: 150,
                        onEnd: () => this.saveOrder()
                    });
                }
            });
        },

        addFromPage(pageId) {
            const page = this.pages.find(p => p.id === pageId);
            if (!page) return;

            this.editing = null;
            this.form = {
                label: page.title,
                url: '/' + page.slug,
                target: '_self',
                icon: '',
                parent_id: '',
                status: true,
            };
            this.showModal = true;
        },

        openCustomModal(item) {
            if (item) {
                this.editing = item;
                this.form = {
                    label: item.label,
                    url: item.url,
                    target: item.target || '_self',
                    icon: item.icon || '',
                    parent_id: item.parent_id || '',
                    status: !!item.status,
                };
            } else {
                this.editing = null;
                this.form = {
                    label: '',
                    url: '',
                    target: '_self',
                    icon: '',
                    parent_id: '',
                    status: true,
                };
            }
            this.showModal = true;
        },

        toggleItem(item) {
            const form = this.$refs.toggleForm;
            form.action = '{{ url('admin/menus/' . $menu->id . '/items') }}/' + item.id + '/toggle';
            form.submit();
        },

        removeItem(item) {
            if (!confirm('Remove "' + item.label + '" from the menu?')) return;
            const form = this.$refs.deleteForm;
            form.action = '{{ url('admin/menus/' . $menu->id . '/items') }}/' + item.id;
            form.submit();
        },

        saveOrder() {
            const rows = document.querySelectorAll('#menuSortable li[data-id]');
            const order = [];
            rows.forEach(li => {
                if (li.parentElement.closest('li[data-id]')) return;
                order.push({
                    id: parseInt(li.dataset.id),
                    parent_id: null
                });
                li.querySelectorAll('ul li[data-id]').forEach(child => {
                    order.push({
                        id: parseInt(child.dataset.id),
                        parent_id: parseInt(li.dataset.id)
                    });
                });
            });

            fetch('{{ route('admin.menus.reorder', $menu) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ order })
            });
        }
    };
}
</script>

<style>[x-cloak]{display:none!important;}</style>

@endsection

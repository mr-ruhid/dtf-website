@extends('admin.app')

@section('title', 'Edit Widget')

@section('content')

<div x-data="featuresEditor({{ json_encode($widget->settings ?? []) }})">

    <div class="mb-6">
        <a href="{{ route('admin.widgets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to widgets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800">Edit Widget</h2>
            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">
                <i class="fa-solid fa-puzzle-piece text-[9px]"></i> {{ $widget->name }}
            </span>
        </div>
        <p class="text-xs text-gray-400 font-mono mt-1">{{ $widget->key }}</p>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.widgets.update', $widget) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i class="fa-solid fa-heading text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Section Header</h3>
                            <p class="text-xs text-gray-400">Title above the feature items</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" x-model="title" maxlength="200"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <textarea name="subtitle" x-model="subtitle" rows="3" maxlength="500"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fa-solid fa-list text-xs"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-sm">Features</h3>
                                <p class="text-xs text-gray-400">Between 3 and 6 features</p>
                            </div>
                        </div>
                        <button type="button" @click="addItem"
                                :disabled="items.length >= 6"
                                :class="items.length >= 6 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-indigo-50'"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 px-3 py-1.5 rounded-lg border border-indigo-200 transition">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add Feature
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="relative bg-gray-50 rounded-xl border border-gray-200 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white text-xs font-bold flex items-center justify-center" x-text="String(index + 1).padStart(2, '0')"></span>
                                        <span class="text-xs font-medium text-gray-600">Feature <span x-text="index + 1"></span></span>
                                    </div>
                                    <button type="button" @click="removeItem(index)"
                                            :disabled="items.length <= 3"
                                            :class="items.length <= 3 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-red-50'"
                                            class="w-7 h-7 flex items-center justify-center rounded text-red-600 transition">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Icon</label>
                                        <input type="text" :name="'items[' + index + '][icon]'" x-model="item.icon" maxlength="50"
                                               placeholder="fa-check"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        <div class="mt-1.5 flex items-center gap-2">
                                            <div class="w-7 h-7 rounded border border-gray-200 bg-white flex items-center justify-center text-indigo-600 text-xs">
                                                <i class="fa-solid" :class="item.icon || 'fa-check'"></i>
                                            </div>
                                            <span class="text-[10px] text-gray-400">Preview</span>
                                        </div>
                                    </div>

                                    <div class="md:col-span-3 space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                            <input type="text" :name="'items[' + index + '][title]'" x-model="item.title" maxlength="100"
                                                   placeholder="Strong Adhesion"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                            <textarea :name="'items[' + index + '][description]'" x-model="item.description" rows="2" maxlength="500"
                                                      placeholder="Transfers bond firmly to fabric — no peeling."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Status</h3>

                    <div class="flex items-center gap-3 px-3 py-3 rounded-lg border {{ $widget->is_active ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-50 border-gray-200' }}">
                        <div class="w-8 h-8 rounded-lg {{ $widget->is_active ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-500' }} flex items-center justify-center">
                            <i class="fa-solid {{ $widget->is_active ? 'fa-circle-check' : 'fa-circle-xmark' }} text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ $widget->is_active ? 'Active' : 'Inactive' }}</p>
                            <p class="text-xs text-gray-500">Toggle from widgets list</p>
                        </div>
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Save Changes
                        </button>
                        <a href="{{ route('admin.widgets.index') }}"
                           class="block text-center text-sm text-gray-600 hover:text-gray-800 py-2">Cancel</a>
                    </div>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                    <i class="fa-solid fa-circle-info text-amber-600 mt-0.5"></i>
                    <div class="text-xs text-amber-800">
                        <p class="font-medium mb-1">Icons</p>
                        <p>Use any <strong>Font Awesome 6</strong> solid icon name without the <code class="bg-amber-100 px-1 rounded font-mono">fa-solid</code> prefix — e.g. <code class="bg-amber-100 px-1 rounded font-mono">fa-check</code>, <code class="bg-amber-100 px-1 rounded font-mono">fa-bolt</code>.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function featuresEditor(settings) {
    var s = settings || {};
    var defaults = [
        { icon: 'fa-check', title: 'Strong Adhesion', description: 'Transfers bond firmly to fabric — no peeling, lifting, or edge curling.' },
        { icon: 'fa-check', title: 'Soft Hand Feel', description: 'Enjoy a smooth, comfortable finish without a thick or stiff texture.' },
        { icon: 'fa-check', title: 'Crack-Resistant Flexibility', description: 'Built to stretch with garments while staying clean and intact.' },
        { icon: 'fa-check', title: 'Works on Multiple Fabrics', description: 'Perfect results on cotton, polyester, blends, and more.' }
    ];

    var initial = (s.items && s.items.length) ? s.items.map(function (it) {
        return {
            icon: it.icon || 'fa-check',
            title: it.title || '',
            description: it.description || ''
        };
    }) : defaults;

    while (initial.length < 3) initial.push({ icon: 'fa-check', title: '', description: '' });

    return {
        title: s.title || 'Premium DTF Transfer Performance You Can Trust',
        subtitle: s.subtitle || 'Our premium DTF transfers are engineered to deliver consistent, professional-grade results across a wide range of fabrics.',
        items: initial,

        addItem: function () {
            if (this.items.length >= 6) return;
            this.items.push({ icon: 'fa-check', title: '', description: '' });
        },

        removeItem: function (index) {
            if (this.items.length <= 3) return;
            this.items.splice(index, 1);
        }
    };
}
</script>

@endsection

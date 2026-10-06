@extends('admin.app')

@section('title', 'Edit Widget')

@section('content')

<div x-data="stepsEditor({{ json_encode($widget->settings ?? []) }})">

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

    <form method="POST" action="{{ route('admin.widgets.update', $widget) }}" enctype="multipart/form-data" class="space-y-6">
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
                            <p class="text-xs text-gray-400">Top text above the steps</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="eyebrow" x-model="eyebrow" maxlength="100"
                               placeholder="Read this first"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Small text above the title (optional)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" x-model="title" maxlength="200" required
                               placeholder="Cut. Place. Press. Peel."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <textarea name="subtitle" x-model="subtitle" rows="2" maxlength="500"
                                  placeholder="We print the film. You press it onto the shirt."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fa-solid fa-list-ol text-xs"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-800 text-sm">Steps</h3>
                                <p class="text-xs text-gray-400">Between 4 and 5 steps</p>
                            </div>
                        </div>
                        <button type="button" @click="addStep"
                                :disabled="items.length >= 5"
                                :class="items.length >= 5 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-indigo-50'"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 px-3 py-1.5 rounded-lg border border-indigo-200 transition">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add Step
                        </button>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="relative bg-gray-50 rounded-xl border border-gray-200 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white text-xs font-bold flex items-center justify-center" x-text="String(index + 1).padStart(2, '0')"></span>
                                        <span class="text-xs font-medium text-gray-600">Step <span x-text="index + 1"></span></span>
                                    </div>
                                    <button type="button" @click="removeStep(index)"
                                            :disabled="items.length <= 4"
                                            :class="items.length <= 4 ? 'opacity-40 cursor-not-allowed' : 'hover:bg-red-50'"
                                            class="w-7 h-7 flex items-center justify-center rounded text-red-600 transition">
                                        <i class="fa-solid fa-trash text-[10px]"></i>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                                    <div class="md:col-span-2 space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                            <input type="text" :name="'items[' + index + '][title]'" x-model="item.title" maxlength="100"
                                                   placeholder="Cut"
                                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                            <textarea :name="'items[' + index + '][description]'" x-model="item.description" rows="2" maxlength="500"
                                                      placeholder="Your sheet arrives uncut. Snip each design off."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                                        </div>

                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Link text</label>
                                                <input type="text" :name="'items[' + index + '][link_text]'" x-model="item.link_text" maxlength="50"
                                                       placeholder="See the guide"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Link URL</label>
                                                <input type="text" :name="'items[' + index + '][link_url]'" x-model="item.link_url" maxlength="255"
                                                       placeholder="/how-to-press"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Image</label>
                                        <div class="relative">
                                            <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 bg-white mb-2">
                                                <template x-if="item.image_preview">
                                                    <img :src="item.image_preview" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!item.image_preview">
                                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                                        <i class="fa-solid fa-image text-3xl mb-1"></i>
                                                        <span class="text-[10px]">No image</span>
                                                    </div>
                                                </template>
                                            </div>
                                            <input type="file" :name="'items[' + index + '][image_file]'" accept="image/*"
                                                   @change="handleImage($event, index)"
                                                   class="w-full text-xs border border-gray-300 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100">
                                        </div>
                                        <input type="hidden" :name="'items[' + index + '][image]'" x-model="item.image">
                                        <p class="text-[10px] text-gray-400 mt-1">JPG, PNG, WEBP · Max 5MB</p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-pointer text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Bottom Button</h3>
                            <p class="text-xs text-gray-400">Main call-to-action below the steps</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button text</label>
                            <input type="text" name="button_text" x-model="button_text" maxlength="50"
                                   placeholder="Build a DTF Gangsheet"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <input type="text" name="button_url" x-model="button_url" maxlength="255"
                                   placeholder="/products"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
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
                        <p class="font-medium mb-1">Steps limit</p>
                        <p>Minimum <strong>4</strong>, maximum <strong>5</strong> steps. The layout adapts automatically.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function stepsEditor(settings) {
    var defaults = [
        { title: 'Cut', description: 'Your sheet arrives uncut. Snip each design off. Keep extras in a drawer.', link_text: 'See the guide', link_url: '/how-to-press', image: '', image_preview: '' },
        { title: 'Place', description: 'Park it on the garment, print facing down. Cotton, poly, or a blend.', link_text: 'See the guide', link_url: '/how-to-press', image: '', image_preview: '' },
        { title: 'Press', description: '310°F / 155°C. Medium pressure. 12-15 seconds. Heat press or EasyPress.', link_text: 'See the guide', link_url: '/how-to-press', image: '', image_preview: '' },
        { title: 'Peel', description: 'Wait 5 seconds. Peel warm. Enjoy your custom print.', link_text: 'See the guide', link_url: '/how-to-press', image: '', image_preview: '' }
    ];

    var initial = (settings && settings.items && settings.items.length) ? settings.items.map(function(it) {
        return {
            title: it.title || '',
            description: it.description || '',
            link_text: it.link_text || '',
            link_url: it.link_url || '',
            image: it.image || '',
            image_preview: it.image_preview || it.image || ''
        };
    }) : defaults;

    while (initial.length < 4) initial.push({ title: '', description: '', link_text: '', link_url: '', image: '', image_preview: '' });

    return {
        eyebrow: (settings && settings.eyebrow) || '',
        title: (settings && settings.title) || 'Cut. Place. Press. Peel.',
        subtitle: (settings && settings.subtitle) || 'We print the film. You press it onto the shirt. That is the whole product.',
        button_text: (settings && settings.button_text) || 'Build a DTF Gangsheet',
        button_url: (settings && settings.button_url) || '/products',
        items: initial,

        addStep: function () {
            if (this.items.length >= 5) return;
            this.items.push({ title: '', description: '', link_text: '', link_url: '', image: '', image_preview: '' });
        },

        removeStep: function (index) {
            if (this.items.length <= 4) return;
            this.items.splice(index, 1);
        },

        handleImage: function (e, index) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                this.items[index].image_preview = ev.target.result;
            }.bind(this);
            reader.readAsDataURL(file);
        }
    };
}
</script>

@endsection

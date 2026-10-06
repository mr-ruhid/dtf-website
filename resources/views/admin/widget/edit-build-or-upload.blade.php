@extends('admin.app')

@section('title', 'Edit Widget')

@section('content')

<div x-data="buildOrUploadEditor({{ json_encode($widget->settings ?? []) }})">

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
                            <p class="text-xs text-gray-400">Top text above the cards</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="eyebrow" x-model="form.eyebrow" maxlength="100"
                               placeholder="Got a file?"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" x-model="form.title" maxlength="200"
                               placeholder="Two ways to start. One result."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <textarea name="subtitle" x-model="form.subtitle" rows="2" maxlength="500"
                                  placeholder="Build it or upload it."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-cube text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Build Card</h3>
                            <p class="text-xs text-gray-400">Left card — design in builder</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Badge</label>
                                    <input type="text" name="build_card[badge]" x-model="form.build_card.badge" maxlength="30"
                                           placeholder="No file"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                    <input type="text" name="build_card[title]" x-model="form.build_card.title" maxlength="100"
                                           placeholder="Build it"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                <textarea name="build_card[description]" x-model="form.build_card.description" rows="2" maxlength="500"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Button text</label>
                                    <input type="text" name="build_card[button_text]" x-model="form.build_card.button_text" maxlength="50"
                                           placeholder="Open the builder"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Button URL</label>
                                    <input type="text" name="build_card[button_url]" x-model="form.build_card.button_url" maxlength="255"
                                           placeholder="/design"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Image</label>
                            <div class="aspect-[4/3] rounded-lg overflow-hidden border border-gray-200 bg-gray-50 mb-2">
                                <template x-if="form.build_card.image_preview">
                                    <img :src="form.build_card.image_preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!form.build_card.image_preview">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                        <i class="fa-solid fa-image text-2xl mb-1"></i>
                                        <span class="text-[10px]">No image</span>
                                    </div>
                                </template>
                            </div>
                            <input type="file" name="build_card[image_file]" accept="image/*"
                                   @change="handleImage($event, 'build_card')"
                                   class="w-full text-xs border border-gray-300 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100">
                            <input type="hidden" name="build_card[image]" x-model="form.build_card.image">
                            <p class="text-[10px] text-gray-400 mt-1">JPG, PNG, WEBP · Max 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                            <i class="fa-solid fa-cloud-arrow-up text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Upload Card</h3>
                            <p class="text-xs text-gray-400">Middle card — send ready file</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 space-y-3">
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Badge</label>
                                    <input type="text" name="upload_card[badge]" x-model="form.upload_card.badge" maxlength="30"
                                           placeholder="Ready"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                                    <input type="text" name="upload_card[title]" x-model="form.upload_card.title" maxlength="100"
                                           placeholder="Upload it"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                                <textarea name="upload_card[description]" x-model="form.upload_card.description" rows="2" maxlength="500"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Button text</label>
                                    <input type="text" name="upload_card[button_text]" x-model="form.upload_card.button_text" maxlength="50"
                                           placeholder="Send the file"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Button URL</label>
                                    <input type="text" name="upload_card[button_url]" x-model="form.upload_card.button_url" maxlength="255"
                                           placeholder="/contact-us"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Image</label>
                            <div class="aspect-[4/3] rounded-lg overflow-hidden border border-gray-200 bg-gray-50 mb-2">
                                <template x-if="form.upload_card.image_preview">
                                    <img :src="form.upload_card.image_preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!form.upload_card.image_preview">
                                    <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                        <i class="fa-solid fa-image text-2xl mb-1"></i>
                                        <span class="text-[10px]">No image</span>
                                    </div>
                                </template>
                            </div>
                            <input type="file" name="upload_card[image_file]" accept="image/*"
                                   @change="handleImage($event, 'upload_card')"
                                   class="w-full text-xs border border-gray-300 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100">
                            <input type="hidden" name="upload_card[image]" x-model="form.upload_card.image">
                            <p class="text-[10px] text-gray-400 mt-1">JPG, PNG, WEBP · Max 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                            <i class="fa-solid fa-circle-info text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Info Card</h3>
                            <p class="text-xs text-gray-400">Right card — closing message</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Badge</label>
                            <input type="text" name="info_card[badge]" x-model="form.info_card.badge" maxlength="30"
                                   placeholder="That's all"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Number</label>
                            <input type="text" name="info_card[number]" x-model="form.info_card.number" maxlength="10"
                                   placeholder="3"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Title</label>
                            <input type="text" name="info_card[title]" x-model="form.info_card.title" maxlength="100"
                                   placeholder="No third step"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                        <textarea name="info_card[description]" x-model="form.info_card.description" rows="3" maxlength="500"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
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
                        <p class="font-medium mb-1">Layout</p>
                        <p>Two clickable cards (Build / Upload) with an info card on the right. On mobile, cards stack vertically.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function buildOrUploadEditor(settings) {
    var s = settings || {};

    return {
        form: {
            eyebrow: s.eyebrow || 'Got a file?',
            title: s.title || 'Two ways to start. One result.',
            subtitle: s.subtitle || 'Build it or upload it. Same film, same day. That\'s the whole menu.',
            build_card: {
                badge: (s.build_card && s.build_card.badge) || 'No file',
                title: (s.build_card && s.build_card.title) || 'Build it',
                description: (s.build_card && s.build_card.description) || 'Design your gang sheet in our online builder. Drag, drop, done.',
                button_text: (s.build_card && s.build_card.button_text) || 'Open the builder',
                button_url: (s.build_card && s.build_card.button_url) || '/design',
                image: (s.build_card && s.build_card.image) || '',
                image_preview: (s.build_card && s.build_card.image) ? (s.build_card.image.startsWith('http') ? s.build_card.image : '/storage/' + s.build_card.image) : '',
            },
            upload_card: {
                badge: (s.upload_card && s.upload_card.badge) || 'Ready',
                title: (s.upload_card && s.upload_card.title) || 'Upload it',
                description: (s.upload_card && s.upload_card.description) || 'Already have a print-ready file? Send it and we\'ll handle the rest.',
                button_text: (s.upload_card && s.upload_card.button_text) || 'Send the file',
                button_url: (s.upload_card && s.upload_card.button_url) || '/contact-us',
                image: (s.upload_card && s.upload_card.image) || '',
                image_preview: (s.upload_card && s.upload_card.image) ? (s.upload_card.image.startsWith('http') ? s.upload_card.image : '/storage/' + s.upload_card.image) : '',
            },
            info_card: {
                badge: (s.info_card && s.info_card.badge) || 'That\'s all',
                number: (s.info_card && s.info_card.number) || '3',
                title: (s.info_card && s.info_card.title) || 'No third step',
                description: (s.info_card && s.info_card.description) || 'Build it or upload it. Same film, same day. That\'s the whole menu.',
            },
        },

        handleImage: function (e, key) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                this.form[key].image_preview = ev.target.result;
            }.bind(this);
            reader.readAsDataURL(file);
        }
    };
}
</script>

@endsection

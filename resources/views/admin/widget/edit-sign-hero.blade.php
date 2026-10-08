@extends('admin.app')

@section('title', 'Edit Sign Hero Widget')

@section('content')

<div x-data="signHeroEditor({{ json_encode($widget->settings ?? []) }})">

    <div class="mb-6">
        <a href="{{ route('admin.widgets.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to widgets
        </a>
        <div class="flex items-center gap-3 mt-2">
            <h2 class="text-xl font-semibold text-gray-800">Edit Sign Hero Widget</h2>
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
                            <h3 class="font-semibold text-gray-800 text-sm">Text Content</h3>
                            <p class="text-xs text-gray-400">Eyebrow, title and subtitle on the left</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Eyebrow</label>
                        <input type="text" name="eyebrow" x-model="form.eyebrow" maxlength="100"
                               placeholder="Large-Format Print"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <p class="text-[10px] text-gray-400 mt-1">Small label above the title (orange)</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Title
                            <span class="text-[10px] font-normal text-gray-400 ml-1">(use Enter for line breaks)</span>
                        </label>
                        <textarea name="title" x-model="form.title" rows="3" maxlength="200"
                                  placeholder="Custom Signs, Vinyl&#10;& Banners"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Subtitle
                            <span class="text-[10px] font-normal text-gray-400 ml-1">(use Enter for line breaks)</span>
                        </label>
                        <textarea name="subtitle" x-model="form.subtitle" rows="2" maxlength="300"
                                  placeholder="Send the artwork. We print it.&#10;You collect."
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono"></textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center">
                            <i class="fa-solid fa-images text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Image Gallery</h3>
                            <p class="text-xs text-gray-400">1 large + 2 small images on the right</p>
                        </div>
                    </div>

                    <div class="space-y-5">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                                Main Image <span class="text-[10px] text-gray-400 normal-case font-normal">(large left)</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="aspect-[4/3] rounded-lg overflow-hidden border border-gray-200 bg-gray-50">
                                    <template x-if="form.main_image_preview">
                                        <img :src="form.main_image_preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!form.main_image_preview">
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                            <i class="fa-solid fa-image text-3xl mb-2"></i>
                                            <span class="text-[11px]">No image</span>
                                        </div>
                                    </template>
                                </div>
                                <div class="flex flex-col justify-center gap-2">
                                    <input type="file" name="main_image_file" accept="image/*"
                                           @change="handleImage($event, 'main_image')"
                                           class="w-full text-xs border border-gray-300 rounded-lg file:mr-2 file:py-2 file:px-3 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs file:font-semibold hover:file:bg-indigo-100">
                                    <input type="hidden" name="main_image" x-model="form.main_image">
                                    <p class="text-[10px] text-gray-400">Recommended: 1200×900px · JPG, PNG, WEBP · Max 5MB</p>
                                    <button type="button" x-show="form.main_image_preview"
                                            @click="clearImage('main_image')"
                                            class="self-start text-[11px] text-red-600 hover:text-red-700 font-medium">
                                        <i class="fa-solid fa-trash text-[10px] mr-1"></i> Remove image
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-gray-100">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                                    Small Image 2 <span class="text-[10px] text-gray-400 normal-case font-normal">(top right)</span>
                                </label>
                                <div class="aspect-[4/3] rounded-lg overflow-hidden border border-gray-200 bg-gray-50 mb-2">
                                    <template x-if="form.image_2_preview">
                                        <img :src="form.image_2_preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!form.image_2_preview">
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                            <i class="fa-solid fa-image text-2xl mb-1"></i>
                                            <span class="text-[10px]">No image</span>
                                        </div>
                                    </template>
                                </div>
                                <input type="file" name="image_2_file" accept="image/*"
                                       @change="handleImage($event, 'image_2')"
                                       class="w-full text-xs border border-gray-300 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100">
                                <input type="hidden" name="image_2" x-model="form.image_2">
                                <button type="button" x-show="form.image_2_preview"
                                        @click="clearImage('image_2')"
                                        class="text-[10px] text-red-600 hover:text-red-700 font-medium mt-1">
                                    <i class="fa-solid fa-trash text-[9px] mr-1"></i> Remove
                                </button>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">
                                    Small Image 3 <span class="text-[10px] text-gray-400 normal-case font-normal">(bottom right)</span>
                                </label>
                                <div class="aspect-[4/3] rounded-lg overflow-hidden border border-gray-200 bg-gray-50 mb-2">
                                    <template x-if="form.image_3_preview">
                                        <img :src="form.image_3_preview" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!form.image_3_preview">
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                            <i class="fa-solid fa-image text-2xl mb-1"></i>
                                            <span class="text-[10px]">No image</span>
                                        </div>
                                    </template>
                                </div>
                                <input type="file" name="image_3_file" accept="image/*"
                                       @change="handleImage($event, 'image_3')"
                                       class="w-full text-xs border border-gray-300 rounded-lg file:mr-2 file:py-1.5 file:px-3 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-xs hover:file:bg-indigo-100">
                                <input type="hidden" name="image_3" x-model="form.image_3">
                                <button type="button" x-show="form.image_3_preview"
                                        @click="clearImage('image_3')"
                                        class="text-[10px] text-red-600 hover:text-red-700 font-medium mt-1">
                                    <i class="fa-solid fa-trash text-[9px] mr-1"></i> Remove
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i class="fa-solid fa-list-ol text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Process Steps</h3>
                            <p class="text-xs text-gray-400">Three steps at the bottom</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-orange-500 text-white text-[10px] font-bold mr-1">1</span>
                                Step 1
                            </label>
                            <input type="text" name="step1_text" x-model="form.step1_text" maxlength="100"
                                   placeholder="Send the file"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-orange-500 text-white text-[10px] font-bold mr-1">2</span>
                                Step 2
                            </label>
                            <input type="text" name="step2_text" x-model="form.step2_text" maxlength="100"
                                   placeholder="We print it"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-orange-500 text-white text-[10px] font-bold mr-1">3</span>
                                Step 3
                            </label>
                            <input type="text" name="step3_text" x-model="form.step3_text" maxlength="100"
                                   placeholder="You collect"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                        <p class="font-medium mb-1">Layout</p>
                        <p>Left side shows text (eyebrow + title + subtitle). Right side shows 1 large image + 2 small stacked images. Below: 3 process steps.</p>
                    </div>
                </div>

            </div>

        </div>

    </form>

</div>

<script>
function signHeroEditor(settings) {
    var s = settings || {};

    var preview = function (path) {
        if (!path) return '';
        return path.startsWith('http') ? path : '/storage/' + path;
    };

    return {
        form: {
            eyebrow: s.eyebrow || 'Large-Format Print',
            title: s.title || 'Custom Signs, Vinyl\n& Banners',
            subtitle: s.subtitle || 'Send the artwork. We print it.\nYou collect.',
            main_image: s.main_image || '',
            main_image_preview: preview(s.main_image),
            image_2: s.image_2 || '',
            image_2_preview: preview(s.image_2),
            image_3: s.image_3 || '',
            image_3_preview: preview(s.image_3),
            step1_text: s.step1_text || 'Send the file',
            step2_text: s.step2_text || 'We print it',
            step3_text: s.step3_text || 'You collect',
        },

        handleImage(e, key) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                this.form[key + '_preview'] = ev.target.result;
            }.bind(this);
            reader.readAsDataURL(file);
        },

        clearImage(key) {
            this.form[key] = '';
            this.form[key + '_preview'] = '';
        }
    };
}
</script>

@endsection

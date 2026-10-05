@extends('admin.app')

@section('title', 'Gallery')

@section('content')

<div x-data="{
    showModal: false,
    showPreview: false,
    previewItem: null,
    editing: null,
    editMode: false,
    formType: 'image',
    formAction: '{{ route('admin.gallery.store') }}'
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Gallery</h2>
            <p class="text-sm text-gray-500 mt-1">Showcase your work with images and videos</p>
        </div>
        <button @click="showModal = true; editing = null; editMode = false; formType = 'image'; formAction = '{{ route('admin.gallery.store') }}'"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Add Item
        </button>
    </div>

    @if (session('status'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    @if ($items->count())
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($items as $item)
                <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-square bg-gray-100 relative cursor-pointer"
                         @click='previewItem = @json($item); showPreview = true'>
                        <img src="{{ $item->thumbnail_url }}" class="w-full h-full object-cover" alt="{{ $item->title }}">
                        @if ($item->type === 'video')
                            <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-white/90 flex items-center justify-center">
                                    <i class="fa-solid fa-play text-indigo-600 ml-0.5"></i>
                                </div>
                            </div>
                        @endif
                        @if (!$item->status)
                            <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] bg-gray-800/80 text-white font-medium">Inactive</div>
                        @endif
                        <div class="absolute top-2 right-2 px-2 py-0.5 rounded text-[10px] bg-black/60 text-white font-mono">#{{ $item->sort_order }}</div>
                    </div>
                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-800 truncate">{{ $item->title ?: '(no title)' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <form method="POST" action="{{ route('admin.gallery.toggle', $item) }}" class="flex-shrink-0">
                                @csrf
                                @method('PUT')
                                <button class="w-7 h-7 flex items-center justify-center rounded {{ $item->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                    <i class="fa-solid {{ $item->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>
                            <button @click='showModal = true; editing = @json($item); editMode = true; formType = "{{ $item->type }}"; formAction = "{{ route('admin.gallery.update', $item) }}"'
                                    class="flex-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-1.5 rounded transition">
                                <i class="fa-solid fa-pen text-xs mr-1"></i> Edit
                            </button>
                            <form method="POST" action="{{ route('admin.gallery.destroy', $item) }}"
                                  onsubmit="return confirm('Delete this item?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-images text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-400 text-sm">No items yet. Click "Add Item" to create the first one.</p>
        </div>
    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-gray-800" x-text="editMode ? 'Edit Item' : 'Add Item'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <template x-if="!editMode">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="image" x-model="formType" class="peer sr-only">
                                <div class="flex items-center justify-center gap-2 py-3 rounded-lg border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                                    <i class="fa-solid fa-image text-slate-500 peer-checked:text-indigo-600"></i>
                                    <span class="text-sm font-medium text-gray-700">Image</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="video" x-model="formType" class="peer sr-only">
                                <div class="flex items-center justify-center gap-2 py-3 rounded-lg border-2 border-gray-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition">
                                    <i class="fa-solid fa-video text-slate-500"></i>
                                    <span class="text-sm font-medium text-gray-700">Video</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" :value="editing?.title || ''"
                           placeholder="Optional title"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <template x-if="formType === 'image'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Image <span x-show="editMode" class="text-gray-400 font-normal">(leave empty to keep current)</span>
                        </label>
                        <input type="file" name="image" accept="image/*" :required="!editMode && formType === 'image'"
                               class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                        <template x-if="editMode && editing?.type === 'image'">
                            <img :src="editing.image_url" class="mt-2 h-24 rounded border">
                        </template>
                    </div>
                </template>

                <template x-if="formType === 'video'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Video <span x-show="editMode" class="text-gray-400 font-normal">(leave empty to keep current)</span>
                        </label>
                        <input type="file" name="video" accept="video/*" :required="!editMode && formType === 'video'"
                               class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                        <p class="text-xs text-gray-500 mt-1">Max 100MB. Formats: MP4, MOV, WEBM</p>
                        <template x-if="editMode && editing?.type === 'video'">
                            <video :src="editing.video_url" controls class="mt-2 h-32 rounded border w-full"></video>
                        </template>
                    </div>
                </template>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editing?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end pb-2.5">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" :checked="editing ? editing.status : true"
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition"
                            :disabled="uploading">
                        <span x-text="editMode ? 'Update' : 'Create'"></span>
                    </button>
                    <button type="button" @click="showModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

    <div x-show="showPreview" x-cloak
         class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
         @click.self="showPreview = false">
        <button @click="showPreview = false" class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 text-white transition">
            <i class="fa-solid fa-xmark text-lg"></i>
        </button>
        <div class="max-w-4xl w-full">
            <template x-if="previewItem">
                <div>
                    <template x-if="previewItem.type === 'image'">
                        <img :src="previewItem.image_url" class="w-full max-h-[80vh] object-contain rounded-lg">
                    </template>
                    <template x-if="previewItem.type === 'video'">
                        <video :src="previewItem.video_url" controls autoplay class="w-full max-h-[80vh] rounded-lg"></video>
                    </template>
                    <p class="text-white text-center mt-4 text-sm" x-text="previewItem.title"></p>
                </div>
            </template>
        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

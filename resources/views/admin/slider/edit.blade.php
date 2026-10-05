@extends('admin.app')

@section('title', 'Edit Slider')

@section('content')

<div x-data="{
    showItemModal: false,
    editing: null,
    itemAction: '{{ route('admin.sliders.items.store', $slider) }}',
    itemMethod: 'POST'
}">

    <div class="mb-6">
        <a href="{{ route('admin.sliders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to sliders
        </a>
        <h2 class="text-xl font-semibold text-gray-800 mt-2">{{ $slider->name }}</h2>
        <p class="text-sm text-gray-500 mt-1 font-mono">{{ $slider->location }}</p>
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

    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <h3 class="font-semibold text-gray-700 mb-4">Slider Settings</h3>
        <form method="POST" action="{{ route('admin.sliders.update', $slider) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name', $slider->name) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" value="{{ old('location', $slider->location) }}" required
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="slider" {{ $slider->type === 'slider' ? 'selected' : '' }}>Slider</option>
                        <option value="banner" {{ $slider->type === 'banner' ? 'selected' : '' }}>Banner</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer pb-2.5">
                        <input type="checkbox" name="status" value="1" {{ $slider->status ? 'checked' : '' }}
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Active
                    </label>
                </div>
            </div>

            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                Save Settings
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-700">Items ({{ $slider->items->count() }})</h3>
            <button @click="showItemModal = true; editing = null; itemAction = '{{ route('admin.sliders.items.store', $slider) }}'; itemMethod = 'POST'"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fa-solid fa-plus text-xs"></i> Add Item
            </button>
        </div>

        <div class="p-6">
            @if ($slider->items->count())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($slider->items as $item)
                        <div class="border border-gray-200 rounded-lg overflow-hidden group">
                            <div class="aspect-video bg-gray-100 relative">
                                <img src="{{ $item->image_url }}" class="w-full h-full object-cover">
                                @if (!$item->status)
                                    <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-xs bg-gray-800/80 text-white">Inactive</div>
                                @endif
                                <div class="absolute top-2 right-2 px-2 py-0.5 rounded text-xs bg-black/60 text-white">#{{ $item->sort_order }}</div>
                            </div>
                            <div class="p-3">
                                <p class="text-sm font-medium text-gray-800 truncate">{{ $item->title ?: '(no title)' }}</p>
                                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $item->subtitle }}</p>
                                <div class="flex items-center gap-2 mt-3">
                                    <button @click='showItemModal = true; editing = @json($item); itemAction = "{{ route('admin.sliders.items.update', [$slider, $item]) }}"; itemMethod = "PUT"'
                                            class="flex-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-1.5 rounded transition">
                                        <i class="fa-solid fa-pen text-xs mr-1"></i> Edit
                                    </button>
                                    <form method="POST" action="{{ route('admin.sliders.items.destroy', [$slider, $item]) }}"
                                          onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-xs bg-red-50 hover:bg-red-100 text-red-600 py-1.5 px-3 rounded transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10 text-gray-400 text-sm">
                    No items yet. Add the first one.
                </div>
            @endif
        </div>
    </div>

    <div x-show="showItemModal" x-cloak
         class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
         @click.self="showItemModal = false">
        <div class="bg-white rounded-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Item' : 'Add Item'"></h3>
                <button @click="showItemModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="itemAction" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <template x-if="itemMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image <span x-show="editing" class="text-gray-400 font-normal">(leave empty to keep current)</span></label>
                    <input type="file" name="image" accept="image/*" :required="!editing"
                           class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                    <template x-if="editing">
                        <img :src="editing.image_url" class="mt-2 h-24 rounded border">
                    </template>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" :value="editing?.title || ''"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                        <input type="text" name="subtitle" :value="editing?.subtitle || ''"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" x-text="editing?.description || ''"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                        <input type="text" name="button_text" :value="editing?.button_text || ''"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                        <input type="text" name="button_link" :value="editing?.button_link || ''"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Text Position</label>
                        <select name="text_position" :value="editing?.text_position || 'left'"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="left">Left</option>
                            <option value="center">Center</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Text Color</label>
                        <select name="text_color" :value="editing?.text_color || 'light'"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" :value="editing?.sort_order ?? 0"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                        <input type="checkbox" name="status" value="1" :checked="editing ? editing.status : true"
                               class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Active
                    </label>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editing ? 'Update Item' : 'Add Item'"></span>
                    </button>
                    <button type="button" @click="showItemModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

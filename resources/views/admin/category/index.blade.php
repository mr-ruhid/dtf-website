@extends('admin.app')

@section('title', 'Categories')

@section('content')

<div x-data="{
    showModal: false,
    editing: null,
    formAction: '{{ route('admin.categories.store') }}',
    formMethod: 'POST'
}">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">Categories</h2>
            <p class="text-sm text-gray-500 mt-1">Manage categories and subcategories for each model</p>
        </div>
        <button @click="showModal = true; editing = null; formAction = '{{ route('admin.categories.store') }}'; formMethod = 'POST'"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
            <i class="fa-solid fa-plus text-xs"></i> Add Category
        </button>
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

    @if ($models->isEmpty())
        <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
            <i class="fa-solid fa-cube text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm mb-1">No models yet.</p>
            <p class="text-gray-400 text-xs">Create a model first before adding categories.</p>
            <a href="{{ route('admin.models.create') }}" class="inline-flex items-center gap-2 mt-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-plus text-xs"></i> Create Model
            </a>
        </div>
    @else

        @php
            $grouped = $categories->groupBy('model_id');
        @endphp

        <div class="space-y-4">
            @foreach ($models as $model)
                @if (isset($grouped[$model->id]))
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="px-5 py-3 bg-gradient-to-r from-slate-50 to-white border-b border-gray-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xs font-bold">
                                {{ strtoupper(substr($model->name, 0, 1)) }}
                            </div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800 text-sm">{{ $model->name }}</h3>
                                <p class="text-xs text-gray-500">{{ $grouped[$model->id]->count() }} top-level categories</p>
                            </div>
                            <a href="{{ route('admin.models.edit', $model) }}" class="text-xs text-gray-500 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach ($grouped[$model->id] as $category)
                                <div>
                                    <div class="px-5 py-3 flex items-center gap-3 hover:bg-gray-50/60 transition">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center shrink-0">
                                            @if ($category->image)
                                                <img src="{{ $category->image_url }}" class="w-full h-full object-cover">
                                            @else
                                                <i class="fa-solid fa-folder text-gray-400 text-sm"></i>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <p class="font-medium text-gray-800 text-sm truncate">{{ $category->name }}</p>
                                                @if (!$category->status)
                                                    <span class="px-2 py-0.5 rounded text-[10px] bg-gray-200 text-gray-600">Inactive</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-400 font-mono truncate">{{ $category->slug }}</p>
                                        </div>
                                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-mono">#{{ $category->sort_order }}</span>
                                        <div class="flex items-center gap-1.5">
                                            <form method="POST" action="{{ route('admin.categories.toggle', $category) }}">
                                                @csrf
                                                @method('PUT')
                                                <button class="w-8 h-8 flex items-center justify-center rounded {{ $category->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                                    <i class="fa-solid {{ $category->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                                </button>
                                            </form>
                                            <button @click='showModal = true; editing = @json($category); formAction = "{{ route('admin.categories.update', $category) }}"; formMethod = "PUT"'
                                                    class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                                  onsubmit="return confirm('Delete this category and all subcategories?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    @if ($category->children->count())
                                        <div class="pl-14 pr-5 pb-2 space-y-1">
                                            @foreach ($category->children as $child)
                                                <div class="flex items-center gap-3 py-2 px-3 rounded-lg bg-slate-50/60 hover:bg-slate-100/80 transition group">
                                                    <i class="fa-solid fa-turn-up text-gray-300 text-xs rotate-90"></i>
                                                    <div class="w-7 h-7 rounded bg-gray-100 overflow-hidden flex items-center justify-center shrink-0">
                                                        @if ($child->image)
                                                            <img src="{{ $child->image_url }}" class="w-full h-full object-cover">
                                                        @else
                                                            <i class="fa-solid fa-folder text-gray-400 text-xs"></i>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <p class="text-sm text-gray-700 truncate">{{ $child->name }}</p>
                                                            @if (!$child->status)
                                                                <span class="px-1.5 py-0.5 rounded text-[9px] bg-gray-200 text-gray-600">Inactive</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <span class="text-[10px] bg-white text-gray-400 px-2 py-0.5 rounded-full font-mono border border-gray-200">#{{ $child->sort_order }}</span>
                                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition">
                                                        <form method="POST" action="{{ route('admin.categories.toggle', $child) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            <button class="w-7 h-7 flex items-center justify-center rounded {{ $child->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                                                <i class="fa-solid {{ $child->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                            </button>
                                                        </form>
                                                        <button @click='showModal = true; editing = @json($child); formAction = "{{ route('admin.categories.update', $child) }}"; formMethod = "PUT"'
                                                                class="w-7 h-7 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600">
                                                            <i class="fa-solid fa-pen text-[10px]"></i>
                                                        </button>
                                                        <form method="POST" action="{{ route('admin.categories.destroy', $child) }}"
                                                              onsubmit="return confirm('Delete this subcategory?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-600">
                                                                <i class="fa-solid fa-trash text-[10px]"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

    @endif

    <div x-show="showModal" x-cloak
         class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
         @click.self="showModal = false">
        <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between sticky top-0 bg-white z-10">
                <h3 class="font-semibold text-gray-800" x-text="editing ? 'Edit Category' : 'Add Category'"></h3>
                <button @click="showModal = false" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" :action="formAction" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <template x-if="formMethod === 'PUT'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                    <select name="model_id" required
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            x-init="$watch('editing', v => { if(v) $el.value = v.model_id })">
                        <option value="">Select a model</option>
                        @foreach ($models as $model)
                            <option value="{{ $model->id }}" :selected="editing && editing.model_id === {{ $model->id }}">{{ $model->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                    <select name="parent_id"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">— None (top-level) —</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" :selected="editing && editing.parent_id === {{ $cat->id }}">{{ $cat->name }}</option>
                            @foreach ($cat->children as $child)
                                <option value="{{ $child->id }}" :selected="editing && editing.parent_id === {{ $child->id }}">— {{ $child->name }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Leave empty to create a top-level category</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" :value="editing?.name || ''" required
                           @input="if(!editing) { $el.form.querySelector('[name=slug]').value = $event.target.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '') }"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" :value="editing?.slug || ''"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" rows="2" maxlength="1000" x-text="editing?.description || ''"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                    <template x-if="editing && editing.image_url">
                        <img :src="editing.image_url" class="mb-2 h-20 rounded-lg border border-gray-200">
                    </template>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                </div>

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

                <div class="pt-2 border-t border-gray-100">
                    <details class="group">
                        <summary class="cursor-pointer flex items-center gap-2 text-sm font-medium text-gray-700 py-2">
                            <i class="fa-solid fa-chevron-right text-xs transition-transform group-open:rotate-90"></i>
                            SEO Settings
                        </summary>
                        <div class="space-y-3 pt-3">
                            <input type="text" name="meta_title" :value="editing?.meta_title || ''" maxlength="200" placeholder="Meta Title"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <textarea name="meta_description" rows="2" maxlength="300" placeholder="Meta Description" x-text="editing?.meta_description || ''"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                            <input type="text" name="meta_keywords" :value="editing?.meta_keywords || ''" maxlength="300" placeholder="Meta Keywords"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </details>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        <span x-text="editing ? 'Update' : 'Create'"></span>
                    </button>
                    <button type="button" @click="showModal = false"
                            class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</button>
                </div>

            </form>

        </div>
    </div>

</div>

<style>[x-cloak]{display:none!important;}</style>

@endsection

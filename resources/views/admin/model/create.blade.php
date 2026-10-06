@extends('admin.app')

@section('title', 'New Model')

@section('content')

<div x-data="{ slug: '' }">

    <div class="mb-6">
        <a href="{{ route('admin.models.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to models
        </a>
        <h2 class="text-xl font-semibold text-gray-800 mt-2">New Model</h2>
    </div>

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.models.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '')"
                               placeholder="e.g. DTF Transfers"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" :value="slug" value="{{ old('slug') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from name.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" maxlength="1000"
                                  placeholder="Short description"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Icon Class (optional)</label>
                        <input type="text" name="icon" value="{{ old('icon') }}"
                               placeholder="e.g. fa-print or fa-cube"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Font Awesome class name (without 'fa-solid' prefix)</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4" x-data="{ open: true }">
                    <button type="button" @click="open = !open" class="w-full flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-magnifying-glass text-xs"></i>
                            </div>
                            <div class="text-left">
                                <h3 class="font-semibold text-gray-700 text-sm">SEO Settings</h3>
                                <p class="text-xs text-gray-400">Search engine optimization</p>
                            </div>
                        </div>
                        <i :class="open ? 'rotate-180' : ''" class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform"></i>
                    </button>

                    <div x-show="open" x-collapse class="space-y-4 pt-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                            <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="200"
                                   placeholder="Defaults to model name"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                            <textarea name="meta_description" rows="3" maxlength="300"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('meta_description') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" maxlength="300"
                                   placeholder="keyword1, keyword2"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        </div>
                    </div>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Publish</h3>

                    <div class="space-y-3">
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Active
                        </label>

                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="show_in_header" value="1" {{ old('show_in_header', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Show in Header
                        </label>
                        <p class="text-xs text-gray-500 pl-6">Display this model in the top category bar</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Lower numbers appear first</p>
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            Create Model
                        </button>
                        <a href="{{ route('admin.models.index') }}"
                           class="block text-center text-sm text-gray-600 hover:text-gray-800 py-2">Cancel</a>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-3">
                    <h3 class="font-semibold text-gray-700 text-sm">Image</h3>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500">JPG, PNG, WEBP, SVG · Max 5MB</p>
                </div>

            </div>

        </div>

    </form>

</div>

@endsection

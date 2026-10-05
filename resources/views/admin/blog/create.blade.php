@extends('admin.app')

@section('title', 'New Post')

@section('content')

<div x-data="{ slug: '' }">

    <div class="mb-6">
        <a href="{{ route('admin.blog.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to blog
        </a>
        <h2 class="text-xl font-semibold text-gray-800 mt-2">New Post</h2>
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

    <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '')"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" :value="slug" value="{{ old('slug') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate from title. Must be unique.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                        <textarea name="excerpt" rows="2" maxlength="500"
                                  placeholder="Short summary shown in listings"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('excerpt') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="content" id="editor" rows="14"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('content') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">You can use the editor to add text, headings, images, links and HTML.</p>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-700 text-sm">Publish</h3>

                    <div>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Publish immediately
                        </label>
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            Create Post
                        </button>
                        <a href="{{ route('admin.blog.index') }}"
                           class="block text-center text-sm text-gray-600 hover:text-gray-800 py-2">Cancel</a>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-3">
                    <h3 class="font-semibold text-gray-700 text-sm">Featured Image</h3>
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm border border-gray-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:bg-indigo-50 file:text-indigo-700 file:text-sm hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500">JPG, PNG, WEBP · Max 5MB</p>
                </div>

            </div>

        </div>

    </form>

</div>

<script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#editor',
        height: 500,
        menubar: false,
        plugins: 'lists link image code table preview wordcount',
        toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright | bullist numlist | link image table | code preview',
        content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
        branding: false,
        promotion: false,
        skin: 'oxide',
    });
</script>

@endsection

@extends('admin.app')

@section('title', 'New Page')

@section('content')

<div x-data="{ slug: '' }">

    <div class="mb-6">
        <a href="{{ route('admin.pages.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to pages
        </a>
        <h2 class="text-xl font-semibold text-gray-800 mt-2">New Page</h2>
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

    <div class="mb-6 px-4 py-3 bg-amber-50 border border-amber-200 rounded-lg flex gap-3">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 mt-0.5"></i>
        <div class="text-xs text-amber-800">
            <p class="font-semibold mb-1">Custom HTML Guide</p>
            <ul class="space-y-1 list-disc list-inside">
                <li>Use unique class names with prefix <code class="bg-amber-100 px-1 rounded font-mono">page-</code> to avoid conflicts with the site's global CSS</li>
                <li>Example: <code class="bg-amber-100 px-1 rounded font-mono">page-wrapper</code>, <code class="bg-amber-100 px-1 rounded font-mono">page-title</code>, <code class="bg-amber-100 px-1 rounded font-mono">page-cta</code></li>
                <li>Wrap all your HTML inside <code class="bg-amber-100 px-1 rounded font-mono">&lt;div class="page-custom-xxx"&gt;...&lt;/div&gt;</code></li>
                <li>Use inline styles or scoped <code class="bg-amber-100 px-1 rounded font-mono">&lt;style&gt;</code> inside the content if needed</li>
                <li>Do not modify global classes like <code class="bg-amber-100 px-1 rounded font-mono">container</code>, <code class="bg-amber-100 px-1 rounded font-mono">btn</code>, <code class="bg-amber-100 px-1 rounded font-mono">card</code></li>
            </ul>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <h3 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                        <i class="fa-solid fa-file-lines text-indigo-500"></i> Basic Information
                    </h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               @input="slug = $event.target.value.toLowerCase().replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '')"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" :value="slug" value="{{ old('slug') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to auto-generate. Must be unique.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                        <textarea name="excerpt" rows="2" maxlength="500"
                                  placeholder="Short summary for listings"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('excerpt') }}</textarea>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-medium text-gray-700">Content (HTML)</label>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="document.getElementById('editor').value = ''; tinymce.get('editor').setContent('');"
                                    class="text-xs text-red-600 hover:bg-red-50 px-2 py-1 rounded transition">
                                <i class="fa-solid fa-trash text-[10px] mr-1"></i> Clear
                            </button>
                            <span class="text-[10px] text-gray-400">HTML allowed</span>
                        </div>
                    </div>
                    <textarea name="content" id="editor" rows="18"
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('content') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Use the <code class="bg-gray-100 px-1 rounded font-mono">&lt;&gt;</code> (code) button to edit raw HTML.</p>
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
                            <input type="checkbox" name="show_in_header" value="1" {{ old('show_in_header') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Show in Header
                        </label>
                        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="show_in_footer" value="1" {{ old('show_in_footer', 1) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            Show in Footer
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-2">
                        <button type="submit"
                                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                            <i class="fa-solid fa-floppy-disk text-xs mr-1"></i> Create Page
                        </button>
                        <a href="{{ route('admin.pages.index') }}"
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
    plugins: 'lists link image code table preview fullscreen wordcount',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright | bullist numlist | link image table | code fullscreen preview',
    content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; }',
    branding: false,
    promotion: false,
    valid_elements: '*[*]',
    extended_valid_elements: '*[*]',
    verify_html: false,
    allow_script_urls: true,
    valid_children: '+body[style],+div[style]',
});
</script>

@endsection

@extends('admin.app')

@section('title', 'Edit Widget')

@section('content')

<div>
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
                            <p class="text-xs text-gray-400">Optional title above the content</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" name="title" value="{{ old('title', $widget->getSetting('title')) }}" maxlength="200"
                               placeholder="Seven shops. Pickup today."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                @include('admin.widget.partials.html-editor', [
                    'name' => 'content',
                    'id' => 'editor',
                    'value' => old('content', $widget->getSetting('content')),
                    'label' => 'Content (HTML)',
                ])

                <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-pink-50 text-pink-600 flex items-center justify-center">
                            <i class="fa-solid fa-arrow-pointer text-xs"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800 text-sm">Button (optional)</h3>
                            <p class="text-xs text-gray-400">Appears below the content</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button text</label>
                            <input type="text" name="button_text" value="{{ old('button_text', $widget->getSetting('button_text')) }}" maxlength="50"
                                   placeholder="Learn More"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Button URL</label>
                            <input type="text" name="button_url" value="{{ old('button_url', $widget->getSetting('button_url')) }}" maxlength="255"
                                   placeholder="/about-us"
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
                        <p class="font-medium mb-1">HTML Guide</p>
                        <ul class="space-y-1 list-disc list-inside">
                            <li>Prefix class names with <code class="bg-amber-100 px-1 rounded font-mono">info-</code> to avoid conflicts</li>
                            <li>Use inline styles or scoped <code class="bg-amber-100 px-1 rounded font-mono">&lt;style&gt;</code> inside the content</li>
                            <li>Full HTML, images and SVG allowed</li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

    </form>
</div>

@endsection

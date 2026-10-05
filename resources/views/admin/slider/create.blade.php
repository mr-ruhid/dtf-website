@extends('admin.app')

@section('title', 'New Slider')

@section('content')

<div class="mb-6">
    <a href="{{ route('admin.sliders.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
        <i class="fa-solid fa-arrow-left text-xs mr-1"></i> Back to sliders
    </a>
    <h2 class="text-xl font-semibold text-gray-800 mt-2">New Slider</h2>
</div>

<div class="bg-white rounded-lg border border-gray-200 p-6 max-w-2xl">

    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.sliders.store') }}" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   placeholder="e.g. Home Hero Slider"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
            <input type="text" name="location" value="{{ old('location') }}" required
                   placeholder="e.g. home_hero"
                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm font-mono focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">Unique key used in code. Use lowercase and underscores.</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
            <select name="type" required
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="slider" {{ old('type') === 'slider' ? 'selected' : '' }}>Slider (multiple images with animation)</option>
                <option value="banner" {{ old('type') === 'banner' ? 'selected' : '' }}>Banner (single static image)</option>
            </select>
        </div>

        <div>
            <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                <input type="checkbox" name="status" value="1" {{ old('status', 1) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                Active
            </label>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                Create Slider
            </button>
            <a href="{{ route('admin.sliders.index') }}"
               class="text-sm text-gray-600 hover:text-gray-800 px-4 py-2.5">Cancel</a>
        </div>

    </form>

</div>

@endsection

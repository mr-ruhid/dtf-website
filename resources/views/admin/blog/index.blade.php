@extends('admin.app')

@section('title', 'Blog')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Blog</h2>
        <p class="text-sm text-gray-500 mt-1">Manage your blog posts</p>
    </div>
    <a href="{{ route('admin.blog.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
        <i class="fa-solid fa-plus text-xs"></i> New Post
    </a>
</div>

@if (session('status'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        {{ session('status') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Image</th>
                <th class="px-4 py-3 text-left font-medium">Title</th>
                <th class="px-4 py-3 text-left font-medium">Slug</th>
                <th class="px-4 py-3 text-left font-medium">Status</th>
                <th class="px-4 py-3 text-left font-medium">Date</th>
                <th class="px-4 py-3 text-right font-medium">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($posts as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if ($post->image)
                            <img src="{{ $post->image_url }}" class="w-14 h-10 rounded object-cover border border-gray-200">
                        @else
                            <div class="w-14 h-10 rounded bg-gray-100 flex items-center justify-center text-gray-400">
                                <i class="fa-solid fa-image text-xs"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-gray-800 truncate max-w-xs">{{ $post->title }}</p>
                        @if ($post->excerpt)
                            <p class="text-xs text-gray-400 truncate max-w-xs mt-0.5">{{ $post->excerpt }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $post->slug }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.blog.toggle', $post) }}">
                            @csrf
                            @method('PUT')
                            @if ($post->status)
                                <button type="submit" class="px-2 py-0.5 rounded text-xs bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">Active</button>
                            @else
                                <button type="submit" class="px-2 py-0.5 rounded text-xs bg-gray-200 text-gray-600 hover:bg-gray-300 transition">Draft</button>
                            @endif
                        </form>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        {{ $post->published_at ? $post->published_at->format('d M Y') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.blog.edit', $post) }}"
                               class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.blog.destroy', $post) }}"
                                  onsubmit="return confirm('Delete this post?')">
                                @csrf
                                @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                        No posts yet. Create your first one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if ($posts->hasPages())
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
@endif

@endsection

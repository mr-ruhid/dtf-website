@extends('admin.app')

@section('title', 'Pages')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Pages</h2>
        <p class="text-sm text-gray-500 mt-1">Manage static and custom pages</p>
    </div>
    <a href="{{ route('admin.pages.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
        <i class="fa-solid fa-plus text-xs"></i> New Page
    </a>
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

<div class="mb-8">
    <div class="flex items-center gap-2 mb-3">
        <i class="fa-solid fa-lock text-amber-500 text-sm"></i>
        <h3 class="font-semibold text-gray-800 text-sm">Static Pages</h3>
        <span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-medium">Locked</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach ($staticPages as $page)
            <a href="{{ route('admin.pages.edit', $page) }}"
               class="group bg-white rounded-xl border border-gray-200 hover:border-amber-300 hover:shadow-md transition p-4 block">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $page->title }}</p>
                        <p class="text-[10px] text-gray-400 font-mono truncate">{{ $page->slug }}</p>
                    </div>
                    @if (!$page->status)
                        <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-medium shrink-0">Off</span>
                    @endif
                </div>

                <div class="flex items-center gap-3 text-[10px] text-gray-500">
                    @if ($page->show_in_header)
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-heading"></i> Header
                        </span>
                    @endif
                    @if ($page->show_in_footer)
                        <span class="flex items-center gap-1">
                            <i class="fa-solid fa-shoe-prints"></i> Footer
                        </span>
                    @endif
                    <span class="ml-auto">
                        <i class="fa-solid fa-pen text-gray-400 group-hover:text-amber-600 transition"></i>
                    </span>
                </div>
            </a>
        @endforeach
    </div>
</div>

<div>
    <div class="flex items-center gap-2 mb-3">
        <i class="fa-solid fa-file-circle-plus text-indigo-500 text-sm"></i>
        <h3 class="font-semibold text-gray-800 text-sm">Custom Pages</h3>
        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full font-medium">Editable</span>
    </div>

    @if ($customPages->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach ($customPages as $page)
                <div class="group bg-white rounded-xl border border-gray-200 hover:border-indigo-300 hover:shadow-md transition p-4">
                    <div class="flex items-start gap-3 mb-3">
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-file"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 truncate">{{ $page->title }}</p>
                            <p class="text-[10px] text-gray-400 font-mono truncate">{{ $page->slug }}</p>
                        </div>
                        @if (!$page->status)
                            <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded font-medium shrink-0">Off</span>
                        @endif
                    </div>

                    @if ($page->excerpt)
                        <p class="text-xs text-gray-500 mb-3 line-clamp-2">{{ $page->excerpt }}</p>
                    @endif

                    <div class="flex items-center gap-1 pt-3 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.pages.toggle', $page) }}">
                            @csrf
                            @method('PUT')
                            <button class="w-7 h-7 flex items-center justify-center rounded {{ $page->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                <i class="fa-solid {{ $page->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-base"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.pages.edit', $page) }}"
                           class="flex-1 text-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-1.5 rounded transition">
                            <i class="fa-solid fa-pen text-[10px] mr-1"></i> Edit
                        </a>

                        <form method="POST" action="{{ route('admin.pages.destroy', $page) }}"
                              onsubmit="return confirm('Delete this page?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-7 h-7 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                <i class="fa-solid fa-trash text-[10px]"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl border border-gray-200 py-12 text-center">
            <i class="fa-solid fa-file-circle-plus text-3xl text-gray-300 mb-3"></i>
            <p class="text-gray-500 text-sm mb-1">No custom pages yet</p>
            <p class="text-gray-400 text-xs">Create your first custom page.</p>
        </div>
    @endif
</div>

<style>
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
</style>

@endsection

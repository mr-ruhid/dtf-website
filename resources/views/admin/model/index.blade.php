@extends('admin.app')

@section('title', 'Models')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Models</h2>
        <p class="text-sm text-gray-500 mt-1">Main product categories (DTF Transfers, UV Stickers, etc.)</p>
    </div>
    <a href="{{ route('admin.models.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
        <i class="fa-solid fa-plus text-xs"></i> New Model
    </a>
</div>

@if (session('status'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
    </div>
@endif

@if ($models->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach ($models as $model)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow group">
                <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 relative overflow-hidden">
                    @if ($model->image)
                        <img src="{{ $model->image_url }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-400">
                            <i class="fa-solid fa-cube text-4xl"></i>
                        </div>
                    @endif
                    @if (!$model->status)
                        <div class="absolute top-2 left-2 px-2 py-0.5 rounded text-[10px] bg-gray-800/80 text-white font-medium">Inactive</div>
                    @endif
                    <div class="absolute top-2 right-2 px-2 py-0.5 rounded text-[10px] bg-black/60 text-white font-mono">#{{ $model->sort_order }}</div>
                </div>

                <div class="p-4">
                    <h3 class="font-semibold text-gray-800 truncate">{{ $model->name }}</h3>
                    <p class="text-xs text-gray-400 font-mono truncate mt-0.5">{{ $model->slug }}</p>
                    @if ($model->description)
                        <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $model->description }}</p>
                    @endif

                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100">
                        <form method="POST" action="{{ route('admin.models.toggle', $model) }}" class="flex-shrink-0">
                            @csrf
                            @method('PUT')
                            <button class="w-8 h-8 flex items-center justify-center rounded {{ $model->status ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle status">
                                <i class="fa-solid {{ $model->status ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.models.edit', $model) }}"
                           class="flex-1 text-center text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 rounded-lg transition">
                            <i class="fa-solid fa-pen text-xs mr-1"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.models.destroy', $model) }}"
                              onsubmit="return confirm('Delete this model?')">
                            @csrf
                            @method('DELETE')
                            <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if ($models->hasPages())
        <div class="mt-6">
            {{ $models->links() }}
        </div>
    @endif
@else
    <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
        <i class="fa-solid fa-cube text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-400 text-sm">No models yet. Create your first one.</p>
    </div>
@endif

<style>
.line-clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
</style>

@endsection

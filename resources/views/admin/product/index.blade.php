@extends('admin.app')

@section('title', 'Products')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-semibold text-gray-800">Products</h2>
        <p class="text-sm text-gray-500 mt-1">Manage all products</p>
    </div>
    <a href="{{ route('admin.products.create') }}"
       class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition flex items-center gap-2">
        <i class="fa-solid fa-plus text-xs"></i> New Product
    </a>
</div>

@if (session('status'))
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-lg">
        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('status') }}
    </div>
@endif

<div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        <div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or SKU..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
            <select name="model_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Models</option>
                @foreach ($models as $m)
                    <option value="{{ $m->id }}" {{ request('model_id') == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="flex-1 bg-slate-700 hover:bg-slate-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                <i class="fa-solid fa-filter text-xs mr-1"></i> Filter
            </button>
            @if (request()->hasAny(['search', 'model_id', 'category_id', 'status']))
                <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </div>
    </form>
</div>

@if ($products->count())
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Image</th>
                        <th class="px-4 py-3 text-left font-medium">Product</th>
                        <th class="px-4 py-3 text-left font-medium">Model</th>
                        <th class="px-4 py-3 text-left font-medium">Category</th>
                        <th class="px-4 py-3 text-left font-medium">Price</th>
                        <th class="px-4 py-3 text-left font-medium">Stock</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                @if ($product->images->first())
                                    <img src="{{ $product->images->first()->url }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image text-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <p class="font-medium text-gray-800 truncate max-w-xs">{{ $product->name }}</p>
                                    @if ($product->is_featured)
                                        <i class="fa-solid fa-star text-amber-500 text-xs" title="Featured"></i>
                                    @endif
                                </div>
                                @if ($product->sku)
                                    <p class="text-xs text-gray-400 font-mono mt-0.5">{{ $product->sku }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $product->model->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">{{ $product->category->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($product->sale_price)
                                    <p class="text-xs line-through text-gray-400">${{ number_format($product->base_price, 2) }}</p>
                                    <p class="font-semibold text-emerald-600">${{ number_format($product->sale_price, 2) }}</p>
                                @else
                                    <p class="font-semibold text-gray-800">${{ number_format($product->base_price, 2) }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($product->stock > 0)
                                    <span class="text-xs text-emerald-600 font-medium">{{ $product->stock }}</span>
                                @else
                                    <span class="text-xs text-gray-400">0</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <form method="POST" action="{{ route('admin.products.toggle', $product) }}">
                                    @csrf
                                    @method('PUT')
                                    @if ($product->status)
                                        <button class="px-2 py-0.5 rounded text-xs bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition">Active</button>
                                    @else
                                        <button class="px-2 py-0.5 rounded text-xs bg-gray-200 text-gray-600 hover:bg-gray-300 transition">Inactive</button>
                                    @endif
                                </form>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <form method="POST" action="{{ route('admin.products.featured', $product) }}">
                                        @csrf
                                        @method('PUT')
                                        <button class="w-8 h-8 flex items-center justify-center rounded {{ $product->is_featured ? 'text-amber-500 hover:bg-amber-50' : 'text-gray-400 hover:bg-gray-100' }} transition" title="Toggle featured">
                                            <i class="fa-solid fa-star text-xs"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.products.duplicate', $product) }}">
                                        @csrf
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-emerald-50 text-emerald-600 transition" title="Duplicate">
                                            <i class="fa-solid fa-copy text-xs"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.products.edit', $product) }}"
                                       class="w-8 h-8 flex items-center justify-center rounded hover:bg-indigo-50 text-indigo-600 transition">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                          onsubmit="return confirm('Delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-red-600 transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
@else
    <div class="bg-white rounded-xl border border-gray-200 py-16 text-center">
        <i class="fa-solid fa-cube text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-400 text-sm">No products yet. Create your first one.</p>
    </div>
@endif

@endsection

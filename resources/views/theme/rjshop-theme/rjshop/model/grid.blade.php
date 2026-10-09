@extends('theme.rjshop-theme.layouts.app')

@section('meta_title', $model->meta_title ?: $model->name)
@section('meta_description', $model->meta_description ?: $model->description)
@section('meta_keywords', $model->meta_keywords)

@push('styles')
<link rel="stylesheet" href="{{ asset('theme/rjshop-theme/css/grid.css') }}">
@endpush

@section('content')

@php
    $baseUrl = url('model/' . $model->slug . ($activeCategory ? '/' . $activeCategory->slug : ''));
    $attrInput = request('attr', []);
    if (!is_array($attrInput)) $attrInput = [];
@endphp

<section class="rj-grid-hero">
    <div class="rj-grid-bg"></div>
    <div class="rj-grid-inner">
        <nav class="rj-grid-breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <a href="{{ url('model/' . $model->slug) }}">{{ $model->name }}</a>
            @if($activeCategory)
                <span>/</span>
                <span class="current">{{ $activeCategory->name }}</span>
            @endif
        </nav>

        <div class="rj-grid-head">
            <h1 class="rj-grid-title">{{ $activeCategory ? $activeCategory->name : $model->name }}</h1>
            @if($activeCategory && $activeCategory->description)
                <p class="rj-grid-sub">{{ $activeCategory->description }}</p>
            @elseif(!$activeCategory && $model->description)
                <p class="rj-grid-sub">{{ $model->description }}</p>
            @endif
        </div>
    </div>
</section>

<section class="rj-grid-body">
    <div class="rj-grid-inner rj-grid-layout">

        <aside class="rj-grid-side">

            <div class="rj-grid-side-block">
                <p class="rj-grid-side-title">// Categories</p>
                <ul class="rj-grid-cats">
                    <li>
                        <a href="{{ url('model/' . $model->slug) }}"
                           class="{{ !$activeCategory ? 'is-active' : '' }}">
                            <span>All</span>
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ url('model/' . $model->slug . '/' . $cat->slug) }}"
                               class="{{ $activeCategory && $activeCategory->id === $cat->id ? 'is-active' : '' }}">
                                <span>{{ $cat->name }}</span>
                            </a>
                            @if($cat->children->count())
                                <ul class="rj-grid-subcats">
                                    @foreach($cat->children as $child)
                                        <li>
                                            <a href="{{ url('model/' . $model->slug . '/' . $child->slug) }}"
                                               class="{{ $activeCategory && $activeCategory->id === $child->id ? 'is-active' : '' }}">
                                                <span>{{ $child->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <form method="GET" action="{{ $baseUrl }}" class="rj-grid-filters">

                @if(count($attributes))
                    @foreach($attributes as $attr)
                        @if($attr->activeValues->count())
                            <div class="rj-grid-side-block">
                                <p class="rj-grid-side-title">// {{ $attr->name }}</p>
                                <div class="rj-grid-attr-list">
                                    @foreach($attr->activeValues as $val)
                                        @php
                                            $checked = in_array((string) $val->id, array_map('strval', $attrInput[$attr->id] ?? []), true);
                                        @endphp
                                        <label class="rj-grid-attr">
                                            <input type="checkbox"
                                                   name="attr[{{ $attr->id }}][]"
                                                   value="{{ $val->id }}"
                                                   @checked($checked)>
                                            @if($attr->type === 'color' && $val->color_code)
                                                <span class="rj-grid-color" style="background: {{ $val->color_code }}"></span>
                                            @endif
                                            <span class="rj-grid-attr-label">{{ $val->value }}</span>
                                            @if((float) $val->price_adjustment > 0)
                                                <span class="rj-grid-attr-adj">+${{ number_format((float) $val->price_adjustment, 2) }}</span>
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <div class="rj-grid-side-block">
                    <p class="rj-grid-side-title">// Price</p>
                    <div class="rj-grid-price">
                        <input type="number" name="min_price" min="0" step="0.01"
                               placeholder="Min"
                               value="{{ request('min_price') }}">
                        <span>—</span>
                        <input type="number" name="max_price" min="0" step="0.01"
                               placeholder="Max"
                               value="{{ request('max_price') }}">
                    </div>
                </div>

                <input type="hidden" name="sort" value="{{ $sort }}">

                <div class="rj-grid-side-actions">
                    <button type="submit" class="rj-grid-apply">
                        <i class="fa-solid fa-filter"></i>
                        <span>Apply</span>
                    </button>
                    <a href="{{ $baseUrl }}" class="rj-grid-clear">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Clear</span>
                    </a>
                </div>
            </form>

        </aside>

        <div class="rj-grid-main">

            <div class="rj-grid-toolbar">
                <p class="rj-grid-count">
                    <span>{{ $products->total() }}</span>
                    <span>{{ $products->total() === 1 ? 'product' : 'products' }}</span>
                </p>

                <form method="GET" action="{{ $baseUrl }}" class="rj-grid-sort">
                    @foreach($attrInput as $aid => $vals)
                        @foreach((array) $vals as $v)
                            <input type="hidden" name="attr[{{ $aid }}][]" value="{{ $v }}">
                        @endforeach
                    @endforeach
                    @if(request('min_price'))
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                    @endif
                    @if(request('max_price'))
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                    @endif

                    <label>Sort</label>
                    <div class="rj-grid-select-wrap">
                        <select name="sort" onchange="this.form.submit()">
                            <option value="new" @selected($sort === 'new')>Newest</option>
                            <option value="price_asc" @selected($sort === 'price_asc')>Price ↑</option>
                            <option value="price_desc" @selected($sort === 'price_desc')>Price ↓</option>
                            <option value="name" @selected($sort === 'name')>Name A–Z</option>
                        </select>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                </form>
            </div>

            @if($products->count())
                <div class="rj-prod-grid">
                    @foreach($products as $product)
                        @include('theme.rjshop-theme.rjshop.partials.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="rj-grid-pagination">
                    {{ $products->links() }}
                </div>
            @else
                <div class="rj-grid-empty">
                    <i class="fa-solid fa-box-open"></i>
                    <p>No products found</p>
                    <a href="{{ $baseUrl }}">Clear filters</a>
                </div>
            @endif

        </div>

    </div>
</section>

@endsection

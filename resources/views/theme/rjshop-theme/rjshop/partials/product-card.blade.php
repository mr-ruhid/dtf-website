@php
    $img = $product->images->first();
    $imgUrl = $img ? $img->url : null;

    $basePrice = (float) ($product->sale_price ?: $product->base_price);

    $minVariant = null;
    if ($product->relationLoaded('variants') && $product->variants->count()) {
        $prices = $product->variants
            ->where('status', 1)
            ->pluck('price')
            ->filter(fn($v) => $v !== null && (float) $v > 0)
            ->map(fn($v) => (float) $v);

        if ($prices->count()) {
            $minVariant = (float) $prices->min();
        }
    }

    $minAttrAdj = 0.0;
    if ($product->relationLoaded('attributeValues')) {
        $adjs = $product->attributeValues
            ->pluck('attributeValue.price_adjustment')
            ->filter(fn($v) => $v !== null)
            ->map(fn($v) => (float) $v);

        if ($adjs->count()) {
            $minAttrAdj = (float) $adjs->min();
        }
    }

    $lowest = $minVariant !== null
        ? $minVariant
        : $basePrice + $minAttrAdj;

    $lowest = max(0, round($lowest, 2));

    $hasSale = $product->sale_price && $product->base_price > $product->sale_price;
    $productUrl = url('product/' . $product->slug);
@endphp

<a href="{{ $productUrl }}" class="rj-pcard">
    <div class="rj-pcard-img">
        @if($imgUrl)
            <img src="{{ $imgUrl }}" alt="{{ $product->name }}">
        @else
            <div class="rj-pcard-placeholder">
                <i class="fa-regular fa-image"></i>
            </div>
        @endif

        @if($hasSale)
            <span class="rj-pcard-badge">Sale</span>
        @endif

        @if($product->stock === 0 && $product->print_type !== 'custom_size')
            <span class="rj-pcard-badge rj-pcard-badge-out">Out of stock</span>
        @endif
    </div>

    <div class="rj-pcard-body">
        <h3 class="rj-pcard-title">{{ $product->name }}</h3>

        @if($product->short_description)
            <p class="rj-pcard-desc">{{ Str::limit($product->short_description, 60) }}</p>
        @endif

        <div class="rj-pcard-price">
            <span class="rj-pcard-price-current">
                @if($minVariant !== null)
                    From ${{ number_format($lowest, 2) }}
                @else
                    ${{ number_format($lowest, 2) }}
                @endif
            </span>
            @if($hasSale && $minVariant === null)
                <span class="rj-pcard-price-old">${{ number_format($product->base_price, 2) }}</span>
            @endif
        </div>
    </div>
</a>

@once
@push('styles')
<style>
    .rj-pcard {
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.015);
        border: 1px solid rgba(255, 255, 255, 0.07);
        border-radius: 14px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-pcard:hover {
        transform: translateY(-3px);
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 16px 32px -12px rgba(99, 102, 241, 0.3);
        background: rgba(255, 255, 255, 0.03);
    }
    .rj-pcard-img {
        position: relative;
        aspect-ratio: 1;
        overflow: hidden;
        background: #0a0715;
    }
    .rj-pcard-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .rj-pcard:hover .rj-pcard-img img {
        transform: scale(1.06);
    }
    .rj-pcard-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(255, 255, 255, 0.06);
        font-size: 2.5rem;
    }
    .rj-pcard-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        padding: 3px 10px;
        background: linear-gradient(135deg, #6366f1, #ec4899);
        border-radius: 9999px;
        font-family: ui-monospace, monospace;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #fff;
    }
    .rj-pcard-badge-out {
        top: auto;
        bottom: 10px;
        left: 10px;
        background: rgba(5, 3, 15, 0.85);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #9ca3af;
    }
    .rj-pcard-body {
        padding: 0.875rem 1rem 1rem;
        display: flex;
        flex-direction: column;
        gap: 0.375rem;
        flex: 1;
    }
    .rj-pcard-title {
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        line-height: 1.35;
        margin: 0;
        transition: color 0.3s;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .rj-pcard:hover .rj-pcard-title {
        color: #a5b4fc;
    }
    .rj-pcard-desc {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
        margin: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .rj-pcard-price {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        font-family: ui-monospace, monospace;
        margin-top: auto;
        padding-top: 0.5rem;
    }
    .rj-pcard-price-current {
        font-size: 15px;
        font-weight: 700;
        color: #fff;
    }
    .rj-pcard-price-old {
        font-size: 12px;
        color: #6b7280;
        text-decoration: line-through;
    }
</style>
@endpush
@endonce

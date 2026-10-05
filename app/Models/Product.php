<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'model_id',
        'category_id',
        'name',
        'slug',
        'sku',
        'short_description',
        'description',
        'base_price',
        'sale_price',
        'print_type',
        'has_variants',
        'is_featured',
        'stock',
        'sort_order',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'has_variants' => 'boolean',
        'is_featured' => 'boolean',
        'status' => 'boolean',
        'stock' => 'integer',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::generateSlug($product->name);
            }
        });

        static::updating(function (Product $product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = static::generateSlug($product->name);
            }
        });
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function model()
    {
        return $this->belongsTo(ProductModel::class, 'model_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function prices()
    {
        return $this->hasMany(ProductPrice::class)->orderBy('min_qty');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class)->orderBy('sort_order');
    }

    public function attributeValues()
    {
        return $this->hasMany(ProductAttributeValue::class);
    }

    public function printZones()
    {
        return $this->belongsToMany(PrintZone::class, 'product_print_zones');
    }

    public function getPrimaryImageAttribute(): ?ProductImage
    {
        return $this->images->first();
    }

    public function getPriceForQuantity(int $qty): float
    {
        $tier = $this->prices()
            ->where('min_qty', '<=', $qty)
            ->where(function ($q) use ($qty) {
                $q->whereNull('max_qty')->orWhere('max_qty', '>=', $qty);
            })
            ->orderByDesc('min_qty')
            ->first();

        if ($tier) {
            return (float) $tier->price;
        }

        return (float) ($this->sale_price ?: $this->base_price);
    }
}

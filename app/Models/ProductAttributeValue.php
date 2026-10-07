<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
        'product_image_id',
        'price_override',
        'image',
        'status',
    ];

    protected $casts = [
        'price_override' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValue::class);
    }

    public function productImage()
    {
        return $this->belongsTo(ProductImage::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->productImage && $this->productImage->url) {
            return $this->productImage->url;
        }

        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }
}

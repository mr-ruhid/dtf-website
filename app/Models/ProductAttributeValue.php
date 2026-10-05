<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeValue extends Model
{
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
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

    public function getEffectivePriceAttribute(): float
    {
        if ($this->price_override !== null) {
            return (float) $this->price_override;
        }
        return (float) ($this->attributeValue->price_adjustment ?? 0);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return '';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}

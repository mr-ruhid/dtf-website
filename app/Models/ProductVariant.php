<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'combination',
        'attribute_values',
        'price',
        'stock',
        'sku',
        'status',
    ];

    protected $casts = [
        'attribute_values' => 'array',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

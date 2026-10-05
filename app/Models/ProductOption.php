<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'type',
        'price_addon',
        'is_required',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price_addon' => 'decimal:2',
        'is_required' => 'boolean',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function values()
    {
        return $this->hasMany(ProductOptionValue::class)->orderBy('sort_order');
    }
}

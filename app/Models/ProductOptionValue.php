<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    protected $fillable = [
        'product_option_id',
        'value',
        'price_addon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price_addon' => 'decimal:2',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }
}

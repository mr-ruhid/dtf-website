<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrintZone extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'max_width_inch',
        'max_height_inch',
        'price_addon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'max_width_inch' => 'decimal:2',
        'max_height_inch' => 'decimal:2',
        'price_addon' => 'decimal:2',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_print_zones');
    }
}

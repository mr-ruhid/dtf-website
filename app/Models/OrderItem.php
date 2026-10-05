<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_image',
        'attributes',
        'print_type',
        'print_zone_id',
        'print_zone_name',
        'print_width',
        'print_height',
        'quantity',
        'unit_price',
        'total_price',
        'price_breakdown',
    ];

    protected $casts = [
        'attributes' => 'array',
        'price_breakdown' => 'array',
        'print_width' => 'decimal:2',
        'print_height' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function printZone()
    {
        return $this->belongsTo(PrintZone::class);
    }

    public function options()
    {
        return $this->hasMany(OrderItemOption::class);
    }

    public function designs()
    {
        return $this->hasMany(OrderDesign::class);
    }

    public function getAttributeLabelAttribute(): string
    {
        if (!$this->attributes) {
            return '';
        }

        return collect($this->attributes)
            ->map(fn($v, $k) => "{$k}: {$v}")
            ->implode(' · ');
    }

    public function getPrimaryDesignAttribute(): ?OrderDesign
    {
        return $this->designs->first();
    }
}

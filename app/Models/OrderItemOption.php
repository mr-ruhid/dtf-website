<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemOption extends Model
{
    protected $fillable = [
        'order_item_id',
        'option_name',
        'option_value',
        'price_addon',
    ];

    protected $casts = [
        'price_addon' => 'decimal:2',
    ];

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryRate extends Model
{
    protected $fillable = [
        'branch_id',
        'zone_id',
        'price',
        'min_days',
        'max_days',
        'free_enabled',
        'free_type',
        'free_min_price',
        'free_min_qty',
        'cod_enabled',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'free_min_price' => 'decimal:2',
        'free_enabled' => 'boolean',
        'cod_enabled' => 'boolean',
        'status' => 'boolean',
        'min_days' => 'integer',
        'max_days' => 'integer',
        'free_min_qty' => 'integer',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function zone()
    {
        return $this->belongsTo(DeliveryZone::class, 'zone_id');
    }

    public function isFreeFor(float $subtotal, int $quantity): bool
    {
        if (!$this->free_enabled) {
            return false;
        }

        if ($this->free_type === 'price' && $this->free_min_price !== null) {
            return $subtotal >= (float) $this->free_min_price;
        }

        if ($this->free_type === 'quantity' && $this->free_min_qty !== null) {
            return $quantity >= $this->free_min_qty;
        }

        return false;
    }

    public function calculateCost(float $subtotal, int $quantity): float
    {
        if ($this->isFreeFor($subtotal, $quantity)) {
            return 0.0;
        }

        return (float) $this->price;
    }

    public function getDeliveryTimeAttribute(): string
    {
        if ($this->min_days === $this->max_days) {
            return $this->min_days . ' days';
        }
        return $this->min_days . '-' . $this->max_days . ' days';
    }

    public function getFreeLabelAttribute(): ?string
    {
        if (!$this->free_enabled) {
            return null;
        }

        if ($this->free_type === 'price' && $this->free_min_price !== null) {
            return 'Free over $' . number_format($this->free_min_price, 2);
        }

        if ($this->free_type === 'quantity' && $this->free_min_qty !== null) {
            return 'Free for ' . $this->free_min_qty . '+ items';
        }

        return null;
    }
}

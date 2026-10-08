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
        'measurement_unit',
        'w_price_addon',
        'h_price_addon',
        'min_measurement_price',
        'is_required',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price_addon' => 'decimal:2',
        'w_price_addon' => 'decimal:2',
        'h_price_addon' => 'decimal:2',
        'min_measurement_price' => 'decimal:2',
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

    public function isMeasurement(): bool
    {
        return $this->type === 'measurement';
    }

    public function calculateMeasurementPrice(float $w, float $h): float
    {
        if (!$this->isMeasurement()) {
            return 0.0;
        }

        $w = max(0, $w);
        $h = max(0, $h);

        $price = ($w * (float) $this->w_price_addon) + ($h * (float) $this->h_price_addon);

        $min = (float) $this->min_measurement_price;

        if ($min > 0 && $price < $min) {
            $price = $min;
        }

        return round($price, 2);
    }

    public function getUnitLabelAttribute(): string
    {
        return match ($this->measurement_unit) {
            'feet' => 'ft',
            'cm' => 'cm',
            'inch' => 'in',
            default => $this->measurement_unit,
        };
    }
}

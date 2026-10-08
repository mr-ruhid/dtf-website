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

    public function measurements()
    {
        return $this->hasMany(ProductOptionMeasurement::class)
            ->orderBy('sort_order')
            ->orderBy('width_value')
            ->orderBy('height_value');
    }

    public function activeMeasurements()
    {
        return $this->hasMany(ProductOptionMeasurement::class)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->orderBy('width_value')
            ->orderBy('height_value');
    }

    public function isMeasurement(): bool
    {
        return $this->type === 'measurement';
    }

    public function findMeasurement(float $w, float $h): ?ProductOptionMeasurement
    {
        return $this->activeMeasurements()
            ->where('width_value', $w)
            ->where('height_value', $h)
            ->first();
    }

    public function calculateMeasurementPrice(float $w, float $h): float
    {
        if (!$this->isMeasurement()) {
            return 0.0;
        }

        $match = $this->findMeasurement($w, $h);

        if ($match) {
            return (float) $match->price;
        }

        return 0.0;
    }

    public function getDefaultMeasurementAttribute(): ?ProductOptionMeasurement
    {
        return $this->activeMeasurements()
            ->where('is_default', 1)
            ->first()
            ?? $this->activeMeasurements()->first();
    }

    public function getUnitLabelAttribute(): string
    {
        return match ($this->measurement_unit) {
            'feet' => 'ft',
            'cm' => 'cm',
            'inch' => 'in',
            default => (string) $this->measurement_unit,
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOptionMeasurement extends Model
{
    protected $fillable = [
        'product_option_id',
        'width_value',
        'height_value',
        'price',
        'is_default',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'width_value' => 'decimal:2',
        'height_value' => 'decimal:2',
        'price' => 'decimal:2',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
        'status' => 'boolean',
    ];

    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function getLabelAttribute(): string
    {
        $w = rtrim(rtrim(number_format((float) $this->width_value, 2, '.', ''), '0'), '.');
        $h = rtrim(rtrim(number_format((float) $this->height_value, 2, '.', ''), '0'), '.');

        return $w . ' × ' . $h;
    }

    public function getFullLabelAttribute(): string
    {
        $unit = $this->option?->measurement_unit ?? 'inch';
        $unitShort = match ($unit) {
            'feet' => 'ft',
            'cm' => 'cm',
            default => 'in',
        };

        return $this->label . ' ' . $unitShort;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('width_value')->orderBy('height_value');
    }
}

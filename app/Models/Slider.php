<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'name',
        'location',
        'type',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(SliderItem::class)->orderBy('sort_order');
    }

    public function activeItems()
    {
        return $this->hasMany(SliderItem::class)->where('status', 1)->orderBy('sort_order');
    }

    public static function findByLocation(string $location): ?self
    {
        return static::where('location', $location)
            ->where('status', 1)
            ->with('activeItems')
            ->first();
    }
}

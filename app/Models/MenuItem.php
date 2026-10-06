<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class MenuItem extends Model
{
    protected $fillable = [
        'location',
        'label',
        'url',
        'target',
        'icon',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 1)->orderBy('sort_order');
    }

    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    public static function forLocation(string $location)
    {
        return Cache::rememberForever('menu_items_' . $location, function () use ($location) {
            return static::where('location', $location)
                ->where('status', 1)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public static function clearCache(?string $location = null): void
    {
        if ($location) {
            Cache::forget('menu_items_' . $location);
        } else {
            foreach (static::pluck('location')->unique() as $loc) {
                Cache::forget('menu_items_' . $loc);
            }
        }
    }

    public function getFullUrlAttribute(): string
    {
        if (str_starts_with($this->url, 'http')) {
            return $this->url;
        }
        return url($this->url);
    }

    protected static function booted(): void
    {
        static::saved(function (MenuItem $item) {
            static::clearCache($item->location);
        });

        static::deleted(function (MenuItem $item) {
            static::clearCache($item->location);
        });
    }
}

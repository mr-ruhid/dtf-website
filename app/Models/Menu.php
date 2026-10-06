<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'location',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    public function rootItems()
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order');
    }

    public function activeItems()
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('status', 1)->first();
    }

    public static function bySlug(string $slug)
    {
        return Cache::rememberForever('menu_' . $slug, function () use ($slug) {
            return static::with(['activeItems.children' => function ($q) {
                $q->where('status', 1)->orderBy('sort_order');
            }])->where('slug', $slug)->where('status', 1)->first();
        });
    }

    public static function clearCache(?string $slug = null): void
    {
        if ($slug) {
            Cache::forget('menu_' . $slug);
        } else {
            foreach (static::pluck('slug') as $s) {
                Cache::forget('menu_' . $s);
            }
        }
    }

    protected static function booted(): void
    {
        static::saved(function (Menu $menu) {
            static::clearCache($menu->slug);
        });

        static::deleted(function (Menu $menu) {
            static::clearCache($menu->slug);
        });
    }
}

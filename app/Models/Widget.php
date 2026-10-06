<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Widget extends Model
{
    protected $fillable = [
        'key',
        'name',
        'settings',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->orderBy('sort_order');
    }

    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->first();
    }

    public static function activeWidgets()
    {
        return static::where('is_active', 1)->orderBy('sort_order')->get();
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('widgets_active');
        });

        static::deleted(function () {
            Cache::forget('widgets_active');
        });
    }
}

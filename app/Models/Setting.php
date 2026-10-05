<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public $timestamps = true;

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever('setting_' . $key, function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget('setting_' . $key);
    }

    public static function setMany(array $data): void
    {
        foreach ($data as $key => $value) {
            static::set($key, $value);
        }
    }

    public static function getMany(array $keys): array
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = static::get($key);
        }
        return $result;
    }

    public static function forget(string $key): void
    {
        Cache::forget('setting_' . $key);
    }

    public static function forgetAll(): void
    {
        foreach (static::pluck('key') as $key) {
            Cache::forget('setting_' . $key);
        }
    }
}

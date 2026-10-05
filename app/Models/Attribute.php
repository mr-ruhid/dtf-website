<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attribute extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'is_locked',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Attribute $attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = static::generateSlug($attribute->name);
            }
        });

        static::updating(function (Attribute $attribute) {
            if ($attribute->isDirty('name') && empty($attribute->slug)) {
                $attribute->slug = static::generateSlug($attribute->name);
            }
        });
    }

    public static function generateSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i;
            $i++;
        }

        return $slug;
    }

    public function values()
    {
        return $this->hasMany(AttributeValue::class)->orderBy('sort_order');
    }

    public function activeValues()
    {
        return $this->hasMany(AttributeValue::class)->where('status', 1)->orderBy('sort_order');
    }
}

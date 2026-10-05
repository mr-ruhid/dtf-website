<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductModel extends Model
{
    protected $table = 'models';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'icon',
        'sort_order',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (ProductModel $model) {
            if (empty($model->slug)) {
                $model->slug = static::generateSlug($model->name);
            }
        });

        static::updating(function (ProductModel $model) {
            if ($model->isDirty('name') && empty($model->slug)) {
                $model->slug = static::generateSlug($model->name);
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

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return '';
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
}

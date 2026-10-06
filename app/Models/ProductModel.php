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
        'show_in_header',
        'display_type',
        'single_product_id',
        'custom_view',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
        'show_in_header' => 'boolean',
        'sort_order' => 'integer',
        'single_product_id' => 'integer',
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

    public function categories()
    {
        return $this->hasMany(Category::class, 'model_id');
    }

    public function rootCategories()
    {
        return $this->hasMany(Category::class, 'model_id')
            ->whereNull('parent_id')
            ->where('status', 1)
            ->orderBy('sort_order');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'model_id');
    }

    public function singleProduct()
    {
        return $this->belongsTo(Product::class, 'single_product_id');
    }

    public function getIsGridAttribute(): bool
    {
        return $this->display_type === 'grid' || empty($this->display_type);
    }

    public function getIsSingleAttribute(): bool
    {
        return $this->display_type === 'single';
    }

    public function getIsCustomAttribute(): bool
    {
        return $this->display_type === 'custom';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInHeader($query)
    {
        return $query->where('status', 1)
            ->where('show_in_header', 1)
            ->orderBy('sort_order');
    }
}

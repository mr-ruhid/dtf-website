<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'show_in_header',
        'display_type',
        'single_product_id',
        'custom_view',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
        'show_in_header' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'model_id');
    }

    public function rootCategories()
    {
        return $this->hasMany(Category::class, 'model_id')->whereNull('parent_id')->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'model_id');
    }

    public function scopeInHeader($query)
    {
        return $query->where('show_in_header', 1)->where('status', 1)->orderBy('sort_order');
    }

    public function getIsSingleAttribute(): bool
    {
        return $this->display_type === 'single' && !empty($this->single_product_id);
    }

    public function getIsCustomAttribute(): bool
    {
        return $this->display_type === 'custom' && !empty($this->custom_view);
    }
}

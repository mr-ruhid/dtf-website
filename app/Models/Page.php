<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'key',
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'type',
        'is_locked',
        'show_in_footer',
        'show_in_header',
        'sort_order',
        'status',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_locked' => 'boolean',
        'show_in_footer' => 'boolean',
        'show_in_header' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public static array $staticPages = [
        ['key' => 'home', 'title' => 'Home', 'slug' => 'home'],
        ['key' => 'about', 'title' => 'About Us', 'slug' => 'about-us'],
        ['key' => 'contact', 'title' => 'Contact Us', 'slug' => 'contact-us'],
        ['key' => 'blog', 'title' => 'Blog', 'slug' => 'blog'],
        ['key' => 'faq', 'title' => 'FAQ', 'slug' => 'faq'],
        ['key' => 'terms', 'title' => 'Terms & Conditions', 'slug' => 'terms-conditions'],
        ['key' => 'privacy', 'title' => 'Privacy Policy', 'slug' => 'privacy-policy'],
        ['key' => 'shipping', 'title' => 'Shipping Policy', 'slug' => 'shipping-policy'],
        ['key' => 'return', 'title' => 'Return Policy', 'slug' => 'return-policy'],
    ];

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = static::generateSlug($page->title);
            }
        });

        static::updating(function (Page $page) {
            if ($page->isDirty('title') && !$page->is_locked && empty($page->slug)) {
                $page->slug = static::generateSlug($page->title);
            }
        });
    }

    public static function generateSlug(string $title): string
    {
        $base = Str::slug($title);
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

    public function getSeoTitleAttribute(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function getSeoDescriptionAttribute(): string
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }
        if ($this->excerpt) {
            return $this->excerpt;
        }
        return Str::limit(strip_tags($this->content ?? ''), 160);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeStatic($query)
    {
        return $query->where('type', 'static');
    }

    public function scopeCustom($query)
    {
        return $query->where('type', 'custom');
    }

    public function scopeFooter($query)
    {
        return $query->where('show_in_footer', 1)->where('status', 1)->orderBy('sort_order');
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('status', 1)->first();
    }

    public static function findByKey(string $key): ?self
    {
        return static::where('key', $key)->where('status', 1)->first();
    }
}

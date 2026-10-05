<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    protected $fillable = [
        'title',
        'type',
        'image',
        'video_url',
        'video_public_id',
        'video_thumbnail',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

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

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->type === 'video') {
            return $this->video_thumbnail ?? '';
        }
        return $this->image_url;
    }
}

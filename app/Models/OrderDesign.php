<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class OrderDesign extends Model
{
    protected $fillable = [
        'order_item_id',
        'file_path',
        'cloudinary_id',
        'original_name',
        'mime_type',
        'file_size',
        'width',
        'height',
        'expires_at',
    ];

    protected $casts = [
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'file_size' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function getFileUrlAttribute(): string
    {
        if (!$this->file_path) {
            return '';
        }

        if (str_starts_with($this->file_path, 'http')) {
            return $this->file_path;
        }

        return asset('storage/' . $this->file_path);
    }

    public function getExistsAttribute(): bool
    {
        if (!$this->file_path) {
            return false;
        }

        if (str_starts_with($this->file_path, 'http')) {
            return true;
        }

        return Storage::disk('public')->exists($this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) {
            return '';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')->where('expires_at', '<', now());
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }
}

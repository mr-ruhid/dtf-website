<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportTicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'is_screenshot',
    ];

    protected $casts = [
        'is_screenshot' => 'boolean',
        'file_size' => 'integer',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
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

    public function getIsImageAttribute(): bool
    {
        return $this->mime_type && str_starts_with($this->mime_type, 'image/');
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
}

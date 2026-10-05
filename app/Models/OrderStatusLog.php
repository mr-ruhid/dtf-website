<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'note',
        'changed_by',
        'changed_by_name',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    public function getFromStatusLabelAttribute(): ?string
    {
        if (!$this->from_status) {
            return null;
        }

        return Order::$statuses[$this->from_status]['label'] ?? $this->from_status;
    }

    public function getToStatusLabelAttribute(): string
    {
        return Order::$statuses[$this->to_status]['label'] ?? $this->to_status;
    }

    public function getToStatusColorAttribute(): string
    {
        return Order::$statuses[$this->to_status]['color'] ?? 'gray';
    }
}

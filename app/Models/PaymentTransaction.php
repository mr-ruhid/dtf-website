<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'gateway_id',
        'transaction_id',
        'reference_id',
        'status',
        'amount',
        'currency',
        'mode',
        'request_payload',
        'response_payload',
        'webhook_payload',
        'error_message',
        'refunded_amount',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'request_payload' => 'array',
        'response_payload' => 'array',
        'webhook_payload' => 'array',
        'paid_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public static array $statuses = [
        'pending' => ['label' => 'Pending', 'color' => 'amber'],
        'processing' => ['label' => 'Processing', 'color' => 'blue'],
        'completed' => ['label' => 'Completed', 'color' => 'emerald'],
        'failed' => ['label' => 'Failed', 'color' => 'rose'],
        'cancelled' => ['label' => 'Cancelled', 'color' => 'gray'],
        'refunded' => ['label' => 'Refunded', 'color' => 'purple'],
        'partially_refunded' => ['label' => 'Partially Refunded', 'color' => 'indigo'],
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return static::$statuses[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return static::$statuses[$this->status]['color'] ?? 'gray';
    }

    public function getRefundableAmountAttribute(): float
    {
        return round((float) $this->amount - (float) $this->refunded_amount, 2);
    }

    public function isRefundable(): bool
    {
        return in_array($this->status, ['completed', 'partially_refunded'])
            && $this->refundable_amount > 0;
    }
}

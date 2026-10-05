<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'company_name',
        'shipping_address',
        'shipping_address2',
        'shipping_city',
        'shipping_state',
        'shipping_zip',
        'shipping_country',
        'branch_id',
        'zone_id',
        'subtotal',
        'delivery_cost',
        'discount',
        'tax',
        'total',
        'status',
        'payment_status',
        'payment_method',
        'tracking_number',
        'customer_note',
        'admin_note',
        'source',
        'api_token',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public static array $statuses = [
        'pending' => ['label' => 'Pending', 'color' => 'amber'],
        'confirmed' => ['label' => 'Confirmed', 'color' => 'blue'],
        'processing' => ['label' => 'Processing', 'color' => 'indigo'],
        'shipped' => ['label' => 'Shipped', 'color' => 'purple'],
        'delivered' => ['label' => 'Delivered', 'color' => 'emerald'],
        'cancelled' => ['label' => 'Cancelled', 'color' => 'rose'],
        'refunded' => ['label' => 'Refunded', 'color' => 'gray'],
    ];

    public static array $paymentStatuses = [
        'unpaid' => ['label' => 'Unpaid', 'color' => 'amber'],
        'paid' => ['label' => 'Paid', 'color' => 'emerald'],
        'refunded' => ['label' => 'Refunded', 'color' => 'gray'],
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function zone()
    {
        return $this->belongsTo(DeliveryZone::class, 'zone_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class)->latest();
    }

    public function getStatusLabelAttribute(): string
    {
        return static::$statuses[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return static::$statuses[$this->status]['color'] ?? 'gray';
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return static::$paymentStatuses[$this->payment_status]['label'] ?? $this->payment_status;
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return static::$paymentStatuses[$this->payment_status]['color'] ?? 'gray';
    }

    public function getShippingAddressFullAttribute(): string
    {
        return collect([
            $this->shipping_address,
            $this->shipping_address2,
            "{$this->shipping_city}, {$this->shipping_state} {$this->shipping_zip}",
            $this->shipping_country,
        ])->filter()->implode(', ');
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'RJ-' . date('Y') . '-';

        $last = static::where('order_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('order_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function updateStatus(string $newStatus, ?string $note = null, ?int $changedBy = null, ?string $changedByName = null): void
    {
        if ($this->status === $newStatus) {
            return;
        }

        $oldStatus = $this->status;

        $data = ['status' => $newStatus];

        if ($newStatus === 'confirmed' && !$this->confirmed_at) {
            $data['confirmed_at'] = now();
        }
        if ($newStatus === 'shipped' && !$this->shipped_at) {
            $data['shipped_at'] = now();
        }
        if ($newStatus === 'delivered' && !$this->delivered_at) {
            $data['delivered_at'] = now();
        }
        if ($newStatus === 'cancelled' && !$this->cancelled_at) {
            $data['cancelled_at'] = now();
        }

        $this->update($data);

        $this->statusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'note' => $note,
            'changed_by' => $changedBy,
            'changed_by_name' => $changedByName,
        ]);
    }
}

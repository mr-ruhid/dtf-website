<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'ticket_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'category',
        'priority',
        'subject',
        'description',
        'order_id',
        'order_number',
        'product_id',
        'status',
        'assigned_to',
        'admin_note',
        'resolution_note',
        'source',
        'api_token',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public static array $categories = [
        'delivery' => ['label' => 'Delivery Issue', 'color' => 'blue', 'icon' => 'fa-truck-fast'],
        'payment' => ['label' => 'Payment Issue', 'color' => 'emerald', 'icon' => 'fa-credit-card'],
        'product' => ['label' => 'Product Issue', 'color' => 'indigo', 'icon' => 'fa-cube'],
        'print_quality' => ['label' => 'Print Quality', 'color' => 'purple', 'icon' => 'fa-print'],
        'order_issue' => ['label' => 'Order Issue', 'color' => 'amber', 'icon' => 'fa-receipt'],
        'account' => ['label' => 'Account Issue', 'color' => 'cyan', 'icon' => 'fa-user'],
        'other' => ['label' => 'Other', 'color' => 'gray', 'icon' => 'fa-circle-question'],
    ];

    public static array $priorities = [
        'low' => ['label' => 'Low', 'color' => 'gray'],
        'medium' => ['label' => 'Medium', 'color' => 'blue'],
        'high' => ['label' => 'High', 'color' => 'amber'],
        'urgent' => ['label' => 'Urgent', 'color' => 'rose'],
    ];

    public static array $statuses = [
        'open' => ['label' => 'Open', 'color' => 'rose'],
        'in_progress' => ['label' => 'In Progress', 'color' => 'amber'],
        'waiting_customer' => ['label' => 'Waiting Customer', 'color' => 'blue'],
        'resolved' => ['label' => 'Resolved', 'color' => 'emerald'],
        'closed' => ['label' => 'Closed', 'color' => 'gray'],
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments()
    {
        return $this->hasMany(SupportTicketAttachment::class, 'ticket_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportTicketMessage::class, 'ticket_id')->orderBy('created_at');
    }

    public function getCategoryLabelAttribute(): string
    {
        return static::$categories[$this->category]['label'] ?? $this->category;
    }

    public function getCategoryIconAttribute(): string
    {
        return static::$categories[$this->category]['icon'] ?? 'fa-circle-question';
    }

    public function getCategoryColorAttribute(): string
    {
        return static::$categories[$this->category]['color'] ?? 'gray';
    }

    public function getPriorityLabelAttribute(): string
    {
        return static::$priorities[$this->priority]['label'] ?? $this->priority;
    }

    public function getPriorityColorAttribute(): string
    {
        return static::$priorities[$this->priority]['color'] ?? 'gray';
    }

    public function getStatusLabelAttribute(): string
    {
        return static::$statuses[$this->status]['label'] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return static::$statuses[$this->status]['color'] ?? 'gray';
    }

    public static function generateTicketNumber(): string
    {
        $prefix = 'SUP-' . date('Y') . '-';

        $last = static::where('ticket_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->value('ticket_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function updateStatus(string $newStatus, ?string $note = null): void
    {
        if ($this->status === $newStatus) {
            return;
        }

        $data = ['status' => $newStatus];

        if ($newStatus === 'resolved' && !$this->resolved_at) {
            $data['resolved_at'] = now();
        }
        if ($newStatus === 'closed' && !$this->closed_at) {
            $data['closed_at'] = now();
        }

        if ($note && in_array($newStatus, ['resolved', 'closed'])) {
            $data['resolution_note'] = $note;
        }

        $this->update($data);
    }
}

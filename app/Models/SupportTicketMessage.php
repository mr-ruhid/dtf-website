<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    protected $fillable = [
        'ticket_id',
        'sender_type',
        'sender_name',
        'message',
        'user_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsFromAdminAttribute(): bool
    {
        return $this->sender_type === 'admin';
    }

    public function getIsFromCustomerAttribute(): bool
    {
        return $this->sender_type === 'customer';
    }
}

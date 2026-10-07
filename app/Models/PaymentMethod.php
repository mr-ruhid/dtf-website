<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'gateway_id',
        'name',
        'enabled',
        'mode',
        'settings',
        'sort_order',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'settings' => 'array',
        'sort_order' => 'integer',
    ];

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function getSetting(string $key, $default = null)
    {
        $settings = $this->settings ?? [];
        return $settings[$key] ?? $default;
    }
}

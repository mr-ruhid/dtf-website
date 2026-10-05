<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'code',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'is_default',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function rates()
    {
        return $this->hasMany(DeliveryRate::class);
    }

    public static function getDefault(): ?self
    {
        return static::where('is_default', 1)->where('status', 1)->first()
            ?? static::where('status', 1)->first();
    }
}

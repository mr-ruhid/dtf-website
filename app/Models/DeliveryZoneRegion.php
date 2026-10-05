<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZoneRegion extends Model
{
    protected $fillable = [
        'zone_id',
        'type',
        'value',
    ];

    public function zone()
    {
        return $this->belongsTo(DeliveryZone::class, 'zone_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'color',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function regions()
    {
        return $this->hasMany(DeliveryZoneRegion::class, 'zone_id');
    }

    public function rates()
    {
        return $this->hasMany(DeliveryRate::class, 'zone_id');
    }

    public static function findForLocation(?string $zip, ?string $city, ?string $state): ?self
    {
        if (!$zip && !$city && !$state) {
            return null;
        }

        $zoneIds = DeliveryZoneRegion::query()
            ->where(function ($q) use ($zip, $city, $state) {
                if ($zip) {
                    $q->orWhere(fn($q2) => $q2->where('type', 'zip')->where('value', $zip));
                }
                if ($city) {
                    $q->orWhere(fn($q2) => $q2->where('type', 'city')->where('value', $city));
                }
                if ($state) {
                    $q->orWhere(fn($q2) => $q2->where('type', 'state')->where('value', $state));
                }
            })
            ->pluck('zone_id')
            ->unique();

        if ($zoneIds->isEmpty()) {
            return null;
        }

        return static::whereIn('id', $zoneIds)->where('status', 1)->first();
    }
}

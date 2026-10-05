<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'type',
        'status',
    ];

    public static function isBlocked(string $email = null, string $ip = null): bool
    {
        $since = now()->subHours(6);

        if ($email) {
            $emailFails = static::where('email', $email)
                ->where('status', 'failed')
                ->where('created_at', '>=', $since)
                ->count();

            if ($emailFails >= 5) {
                return true;
            }
        }

        if ($ip) {
            $ipFails = static::where('ip_address', $ip)
                ->where('status', 'failed')
                ->where('created_at', '>=', $since)
                ->count();

            if ($ipFails >= 5) {
                return true;
            }
        }

        return false;
    }

    public static function log(string $type, string $status, ?string $email = null): void
    {
        static::create([
            'email' => $email,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'type' => $type,
            'status' => $status,
        ]);
    }
}

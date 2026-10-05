<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@rjshop.com'],
            [
                'name' => 'Admin',
                'phone' => null,
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status' => 1,
                'email_verified_at' => now(),
            ]
        );
    }
}

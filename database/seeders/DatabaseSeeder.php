<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            AttributeSeeder::class,
            PageSeeder::class,
            SliderSeeder::class,
            WidgetSeeder::class,
        ]);
    }
}

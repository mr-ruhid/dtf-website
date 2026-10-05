<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        $size = Attribute::updateOrCreate(
            ['slug' => 'size'],
            [
                'name' => 'Size',
                'type' => 'select',
                'is_locked' => true,
                'sort_order' => 1,
                'status' => true,
            ]
        );

        if ($size->values()->count() === 0) {
            $sizes = ['XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL'];

            foreach ($sizes as $i => $value) {
                $size->values()->create([
                    'value' => $value,
                    'sort_order' => $i + 1,
                    'status' => true,
                ]);
            }
        }

        $color = Attribute::updateOrCreate(
            ['slug' => 'color'],
            [
                'name' => 'Color',
                'type' => 'color',
                'is_locked' => true,
                'sort_order' => 2,
                'status' => true,
            ]
        );

        if ($color->values()->count() === 0) {
            $colors = [
                ['value' => 'White', 'color_code' => '#FFFFFF'],
                ['value' => 'Black', 'color_code' => '#000000'],
                ['value' => 'Red', 'color_code' => '#EF4444'],
                ['value' => 'Blue', 'color_code' => '#3B82F6'],
                ['value' => 'Green', 'color_code' => '#10B981'],
                ['value' => 'Yellow', 'color_code' => '#FACC15'],
                ['value' => 'Gray', 'color_code' => '#6B7280'],
                ['value' => 'Navy', 'color_code' => '#1E3A8A'],
            ];

            foreach ($colors as $i => $colorData) {
                $color->values()->create([
                    'value' => $colorData['value'],
                    'color_code' => $colorData['color_code'],
                    'sort_order' => $i + 1,
                    'status' => true,
                ]);
            }
        }
    }
}

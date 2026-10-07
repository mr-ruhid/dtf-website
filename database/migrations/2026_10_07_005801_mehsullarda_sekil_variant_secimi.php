<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->foreignId('product_image_id')
                ->nullable()
                ->after('attribute_value_id')
                ->constrained('product_images')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_attribute_values', function (Blueprint $table) {
            $table->dropForeign(['product_image_id']);
            $table->dropColumn('product_image_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_id')->constrained()->cascadeOnDelete();
            $table->decimal('width_value', 8, 2);
            $table->decimal('height_value', 8, 2);
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('is_default')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->unique(['product_option_id', 'width_value', 'height_value'], 'pom_option_wh_unique');
            $table->index(['product_option_id', 'status'], 'pom_option_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_option_measurements');
    }
};

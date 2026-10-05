<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 10)->nullable();
            $table->string('zip', 20)->nullable();
            $table->string('country', 5)->default('US');
            $table->boolean('is_default')->default(0);
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('color', 20)->default('#6366f1');
            $table->integer('sort_order')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('delivery_zone_regions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('delivery_zones')->cascadeOnDelete();
            $table->enum('type', ['state', 'city', 'zip'])->default('state');
            $table->string('value');
            $table->timestamps();

            $table->index(['type', 'value']);
            $table->index(['zone_id', 'type']);
        });

        Schema::create('delivery_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('zone_id')->constrained('delivery_zones')->cascadeOnDelete();
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('min_days')->default(1);
            $table->integer('max_days')->default(5);
            $table->boolean('free_enabled')->default(0);
            $table->enum('free_type', ['price', 'quantity'])->default('price');
            $table->decimal('free_min_price', 10, 2)->nullable();
            $table->integer('free_min_qty')->nullable();
            $table->boolean('cod_enabled')->default(1);
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->unique(['branch_id', 'zone_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_rates');
        Schema::dropIfExists('delivery_zone_regions');
        Schema::dropIfExists('delivery_zones');
        Schema::dropIfExists('branches');
    }
};

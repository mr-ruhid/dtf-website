<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('gateway_id')->unique();
            $table->string('name');
            $table->boolean('enabled')->default(0);
            $table->string('mode', 20)->default('test');
            $table->json('settings')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index('enabled');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

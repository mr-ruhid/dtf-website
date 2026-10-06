<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->string('display_type')->default('grid')->after('show_in_header');
            $table->unsignedBigInteger('single_product_id')->nullable()->after('display_type');
            $table->string('custom_view')->nullable()->after('single_product_id');
        });
    }

    public function down(): void
    {
        Schema::table('models', function (Blueprint $table) {
            $table->dropColumn(['display_type', 'single_product_id', 'custom_view']);
        });
    }
};

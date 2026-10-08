<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->string('measurement_unit', 20)->default('inch')->after('price_addon');
            $table->decimal('w_price_addon', 10, 2)->default(0)->after('measurement_unit');
            $table->decimal('h_price_addon', 10, 2)->default(0)->after('w_price_addon');
            $table->decimal('min_measurement_price', 10, 2)->default(0)->after('h_price_addon');
        });
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn([
                'measurement_unit',
                'w_price_addon',
                'h_price_addon',
                'min_measurement_price',
            ]);
        });
    }
};

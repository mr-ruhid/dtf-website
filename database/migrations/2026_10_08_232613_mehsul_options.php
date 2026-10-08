<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            if (Schema::hasColumn('product_options', 'w_price_addon')) {
                $table->dropColumn('w_price_addon');
            }
            if (Schema::hasColumn('product_options', 'h_price_addon')) {
                $table->dropColumn('h_price_addon');
            }
            if (Schema::hasColumn('product_options', 'min_measurement_price')) {
                $table->dropColumn('min_measurement_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->decimal('w_price_addon', 10, 2)->default(0)->after('measurement_unit');
            $table->decimal('h_price_addon', 10, 2)->default(0)->after('w_price_addon');
            $table->decimal('min_measurement_price', 10, 2)->default(0)->after('h_price_addon');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_token', 32)->nullable()->unique()->after('order_number');
            $table->string('payment_gateway_id')->nullable()->after('payment_method');

            $table->index('tracking_token');
        });

        Schema::table('order_status_logs', function (Blueprint $table) {
            $table->boolean('notify_customer')->default(0)->after('note');
            $table->boolean('is_public')->default(1)->after('notify_customer');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['tracking_token']);
            $table->dropColumn(['tracking_token', 'payment_gateway_id']);
        });

        Schema::table('order_status_logs', function (Blueprint $table) {
            $table->dropColumn(['notify_customer', 'is_public']);
        });
    }
};

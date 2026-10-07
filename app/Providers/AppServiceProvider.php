<?php

namespace App\Providers;

use App\Payment\Registry\PaymentGatewayRegistry;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayRegistry::class, function ($app) {
            return new PaymentGatewayRegistry();
        });
    }

    public function boot(): void
    {
        $registry = $this->app->make(PaymentGatewayRegistry::class);

        try {
            $registry->discover();
        } catch (\Throwable $e) {
            if (config('app.debug')) {
                logger()->error('Payment gateway discovery failed: ' . $e->getMessage());
            }
        }
    }
}

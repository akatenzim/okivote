<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Contracts\PaymentGatewayInterface;
use App\Services\Gateways\DummyPaymentGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, DummyPaymentGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

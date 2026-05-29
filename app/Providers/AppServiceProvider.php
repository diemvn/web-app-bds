<?php

namespace App\Providers;

use App\Models\Payment;
use App\Observers\PaymentObserver;
use App\Services\BrandingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);

        View::composer(['layouts.*', 'public.*', 'tenant.*', 'components.*', 'components.public.*'], function ($view) {
            $view->with('branding', app(BrandingService::class)->all());
        });
    }
}

<?php

namespace App\Providers;

use App\Services\StorefrontSettings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(StorefrontSettings::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('admin-login', function (Request $request): Limit {
            $email = strtolower(trim((string) $request->input('email')));

            return Limit::perMinute(5)->by($email . '|' . $request->ip());
        });

        View::composer([
            'welcome',
            'products.*',
            'cart.*',
            'customer.orders.*',
            'partials.store-header',
            'partials.store-footer',
        ], function (ViewContract $view): void {
            $view->with('storefront', app(StorefrontSettings::class)->data());
        });
    }
}

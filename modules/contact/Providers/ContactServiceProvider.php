<?php

namespace Modules\Contact\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ContactServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Feature Flag Check - Only load routes/views when enabled
        if (!config('features.contact', false)) {
            return;
        }

        // 1. Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // 2. Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // 3. Register Module Views (Appends to global view paths)
        // ThemeServiceProvider handles theme overrides via prependLocation()
        $this->app['view']->addLocation(__DIR__ . '/../resources/views');
    }
}

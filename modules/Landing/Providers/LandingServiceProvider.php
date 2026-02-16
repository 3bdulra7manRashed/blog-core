<?php

namespace Modules\Landing\Providers;

use Illuminate\Support\ServiceProvider;

class LandingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Note: Public web routes are loaded via bootstrap/app.php's 'then' callback
        // to enable proper route overriding when feature('landing') is enabled.
        // Admin routes are still loaded here since they don't need override priority.
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'landing');
    }
}

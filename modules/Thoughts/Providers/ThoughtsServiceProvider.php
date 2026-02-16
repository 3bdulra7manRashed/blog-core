<?php

namespace Modules\Thoughts\Providers;

use App\Support\Landing\LandingThoughtsManager;
use Illuminate\Support\ServiceProvider;
use Modules\Thoughts\Support\ThoughtLandingProvider;

class ThoughtsServiceProvider extends ServiceProvider
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

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'thoughts');

        // Register Thoughts as a landing content provider (contract-based)
        if (feature('thoughts')) {
            app(LandingThoughtsManager::class)
                ->register(ThoughtLandingProvider::class);
        }
    }
}

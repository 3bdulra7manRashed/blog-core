<?php

namespace Modules\Thoughts\Providers;

use Illuminate\Support\ServiceProvider;

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
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'thoughts');
    }
}

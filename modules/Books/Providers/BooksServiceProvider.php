<?php

namespace Modules\Books\Providers;

use Illuminate\Support\ServiceProvider;

class BooksServiceProvider extends ServiceProvider
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

        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'books');
    }
}

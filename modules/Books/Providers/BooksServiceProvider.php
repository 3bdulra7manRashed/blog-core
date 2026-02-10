<?php

namespace Modules\Books\Providers;

use App\Support\Landing\LandingReleasesManager;
use Illuminate\Support\ServiceProvider;
use Modules\Books\Support\BookLandingProvider;

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

        // Register releases provider for landing page (contract-based)
        if (feature('books')) {
            $manager = app(LandingReleasesManager::class);
            $manager->register(BookLandingProvider::class);
        }
    }
}


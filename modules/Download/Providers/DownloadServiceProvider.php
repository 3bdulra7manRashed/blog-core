<?php

namespace Modules\Download\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class DownloadServiceProvider extends ServiceProvider
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
        // Default to true if not set, or enforce strict config
        // User requested optional, so we respect config('features.download')
        if (!config('features.download', false)) {
            return;
        }

        // 1. Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // 2. Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // 3. Load Views
        // Register 'download' namespace for views (e.g. view('download::admin.downloads.index'))
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'download');

        // Add path to global view paths (optional, but good for overrides)
        // View::addLocation(__DIR__ . '/../Resources/views');
    }
}

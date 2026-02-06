<?php

namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class MediaServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Load Views (Restoring legacy views access)
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'media');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load Routes
        Route::middleware('web')
            ->group(__DIR__ . '/../Routes/web.php');

        Route::middleware('web')
            ->group(__DIR__ . '/../Routes/admin.php');
    }
}

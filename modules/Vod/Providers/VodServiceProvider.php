<?php

namespace Modules\Vod\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class VodServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Load routes/views/migrations only if any VOD sub-feature is enabled
        if (!vod_enabled()) {
            return;
        }

        // Load Routes
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware(['web', 'auth', 'role:admin|moderator'])
            ->prefix('admin')
            ->name('admin.')
            ->group(__DIR__ . '/../routes/admin.php');

        // Register Module Views path (Appends to global view paths)
        // ThemeServiceProvider handles theme overrides via prependLocation()
        $this->app['view']->addLocation(__DIR__ . '/../resources/views');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}

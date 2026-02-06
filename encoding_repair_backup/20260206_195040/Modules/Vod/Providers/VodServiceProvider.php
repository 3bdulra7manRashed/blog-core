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
        if (!config('features.vod.enabled')) {
            return;
        }

        // Load Routes
        Route::middleware('web')
            ->group(__DIR__ . '/../routes/web.php');

        Route::middleware(['web', 'auth', 'role:admin|moderator'])
            ->prefix('admin')
            ->name('admin.')
            ->group(__DIR__ . '/../routes/admin.php');

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'vod');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}

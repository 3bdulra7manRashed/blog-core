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

        // Load Views (Theme aware with fallback)
        $this->loadViewsFrom([
            resource_path('themes/' . config('theme.active', 'classic') . '/views'),
            __DIR__ . '/../resources/views',
        ], '');

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}

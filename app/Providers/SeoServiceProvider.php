<?php

namespace App\Providers;

use App\Support\SEO\SeoManager;
use Illuminate\Support\ServiceProvider;

/**
 * SEO Service Provider
 * 
 * Registers the SeoManager as a singleton for use throughout the application.
 * This ensures SEO data persists across the request lifecycle.
 */
class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register SeoManager as a singleton
        $this->app->singleton(SeoManager::class, function ($app) {
            return new SeoManager();
        });

        // Register alias for easier access
        $this->app->alias(SeoManager::class, 'seo');
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share SeoManager instance with all views
        view()->composer('*', function ($view) {
            $view->with('seoManager', app(SeoManager::class));
        });
    }
}

<?php

namespace App\Providers;

use App\Support\GlobalStats\GlobalStatsManager;
use Illuminate\Support\ServiceProvider;

/**
 * Global Stats Service Provider
 * 
 * Registers the GlobalStatsManager as a singleton.
 * Modules can register their stats providers during boot.
 * 
 * @see \App\Contracts\HasGlobalStats
 * @see \App\Support\GlobalStats\GlobalStatsManager
 */
class GlobalStatsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register GlobalStatsManager as singleton
        $this->app->singleton(GlobalStatsManager::class, function ($app) {
            return new GlobalStatsManager();
        });

        // Also bind to interface alias for convenience
        $this->app->alias(GlobalStatsManager::class, 'global-stats');
    }

    /**
     * Bootstrap services.
     * 
     * Modules can register their stats providers here or in their own service providers.
     * 
     * Example (in a module service provider):
     * 
     *     public function boot(): void
     *     {
     *         $manager = app(GlobalStatsManager::class);
     *         $manager->register(ContactStatsProvider::class, 'contact');
     *     }
     */
    public function boot(): void
    {
        // No providers registered here by default.
        // Modules should register their own providers in their ServiceProviders.
        // This maintains loose coupling.
    }
}

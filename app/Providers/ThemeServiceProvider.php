<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register theme helper functions
        require_once app_path('Helpers/theme.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Use segments to detect admin panel early in the lifecycle
        // This is more robust than request()->is() in some lifecycle stages
        $isAdmin = false;
        try {
            if (!app()->runningInConsole()) {
                $segment = request()->segment(1); 
                $isAdmin = ($segment === 'admin');
            }
        } catch (\Throwable $e) {
            // Fallback
        }

        $themeDirectory = config('theme.directory', 'themes');

        if ($isAdmin) {
            $theme = config('theme.admin_active', 'classic');
            $path = resource_path("{$themeDirectory}/admin/{$theme}/views");
        } else {
            $theme = config('theme.active', 'classic');
            $path = resource_path("{$themeDirectory}/{$theme}/views");
        }

        // CRITICAL: Force register the path
        if (is_dir($path)) {
            // Register namespace 'theme::'
            $this->loadViewsFrom($path, 'theme');
            
            // Add to global finder
            $this->app['view']->addLocation($path);
            $this->app['view']->prependLocation($path);
        } else {
             // Only throw explicitly if not in console (to avoid breaking artisan)
             if (!app()->runningInConsole()) {
                 // Stop execution if path is wrong, so we know immediately
                 throw new \InvalidArgumentException("Theme Path Not Found: " . $path);
             }
        }

        $this->registerThemeComponents();
    }

    /**
     * Register theme components with Blade.
     */
    protected function registerThemeComponents(): void
    {
        $activeTheme = config('theme.active', 'classic');
        $themeDirectory = config('theme.directory', 'themes');
        
        // Basic component registration - can be expanded if needed
        $componentsPath = resource_path("{$themeDirectory}/{$activeTheme}/views/components");
        
        if (is_dir($componentsPath)) {
            Blade::anonymousComponentPath($componentsPath);
        }
    }
}

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
        $this->registerThemeViewPaths();
        $this->registerThemeNamespace();
        $this->registerThemeComponents();
    }

    /**
     * Register the theme view paths.
     * 
     * This prepends the active theme's view directory to Laravel's view paths,
     * allowing theme views to take priority over default views.
     */
    protected function registerThemeViewPaths(): void
    {
        $activeTheme = config('theme.active', 'classic');
        $themeDirectory = config('theme.directory', 'themes');
        
        // Path to the active theme's views
        $themePath = resource_path("{$themeDirectory}/{$activeTheme}/views");
        
        if (is_dir($themePath)) {
            // Prepend theme views to take priority
            $this->app['view']->prependLocation($themePath);
        }

        // Register core views as fallback namespace
        $corePath = resource_path('views/core');
        if (is_dir($corePath)) {
            $this->app['view']->addNamespace('core', $corePath);
        }
    }

    /**
     * Register the theme namespace for explicit theme view references.
     */
    protected function registerThemeNamespace(): void
    {
        $activeTheme = config('theme.active', 'classic');
        $themeDirectory = config('theme.directory', 'themes');
        
        $themePath = resource_path("{$themeDirectory}/{$activeTheme}/views");
        
        if (is_dir($themePath)) {
            $this->app['view']->addNamespace('theme', $themePath);
        }
    }

    /**
     * Register theme components with Blade.
     * 
     * This allows components from the theme to be auto-discovered.
     */
    protected function registerThemeComponents(): void
    {
        $activeTheme = config('theme.active', 'classic');
        $themeDirectory = config('theme.directory', 'themes');
        
        $componentsPath = resource_path("{$themeDirectory}/{$activeTheme}/views/components");
        
        if (is_dir($componentsPath)) {
            // Register anonymous components from the theme
            Blade::anonymousComponentPath($componentsPath);
        }
    }
}

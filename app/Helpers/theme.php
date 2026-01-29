<?php

/**
 * Theme Helper Functions
 * 
 * These helpers provide theme-aware view and asset resolution.
 * They are loaded by the ThemeServiceProvider.
 */

if (!function_exists('theme_path')) {
    /**
     * Get the path to the active theme directory.
     *
     * @param string $path Optional path to append
     * @return string
     */
    function theme_path(string $path = ''): string
    {
        $activeTheme = config('theme.active', 'classic');
        $themeDirectory = config('theme.directory', 'themes');
        
        $basePath = resource_path("{$themeDirectory}/{$activeTheme}");
        
        return $path ? "{$basePath}/{$path}" : $basePath;
    }
}

if (!function_exists('theme_view_path')) {
    /**
     * Get the path to a theme view.
     *
     * @param string $view View name (dot notation)
     * @return string
     */
    function theme_view_path(string $view = ''): string
    {
        $viewPath = str_replace('.', '/', $view);
        return theme_path("views/{$viewPath}.blade.php");
    }
}

if (!function_exists('theme_asset')) {
    /**
     * Get the URL to a theme asset.
     * 
     * Theme assets are published to public/themes/{theme_name}/
     * or served from resources/themes/{theme_name}/assets/
     *
     * @param string $path Asset path relative to theme assets directory
     * @return string
     */
    function theme_asset(string $path): string
    {
        $activeTheme = config('theme.active', 'classic');
        
        // Check if published theme assets exist
        $publishedPath = "themes/{$activeTheme}/{$path}";
        if (file_exists(public_path($publishedPath))) {
            return asset($publishedPath);
        }
        
        // Fallback to default assets
        return asset($path);
    }
}

if (!function_exists('theme_name')) {
    /**
     * Get the name of the currently active theme.
     *
     * @return string
     */
    function theme_name(): string
    {
        return config('theme.active', 'classic');
    }
}

if (!function_exists('theme_view_exists')) {
    /**
     * Check if a view exists in the current theme.
     *
     * @param string $view View name (dot notation)
     * @return bool
     */
    function theme_view_exists(string $view): bool
    {
        return file_exists(theme_view_path($view));
    }
}

<?php

/**
 * Theme Configuration
 * 
 * This file configures the active theme for the application.
 * Themes are located in resources/themes/{theme_name}/
 * 
 * Each theme contains:
 * - views/       Blade templates
 * - assets/      CSS, JS, images specific to the theme
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Active Theme
    |--------------------------------------------------------------------------
    |
    | The currently active theme. This determines which theme directory
    | is used for view resolution and asset loading.
    |
    | Themes are located in: resources/themes/{active}/
    |
    */
    'active' => env('ACTIVE_THEME', 'classic'),
    'admin_active' => env('ACTIVE_THEME_ADMIN', 'classic'),

    /*
    |--------------------------------------------------------------------------
    | Theme Directory
    |--------------------------------------------------------------------------
    |
    | The base directory where themes are stored, relative to resources/
    |
    */
    'directory' => 'themes',

    /*
    |--------------------------------------------------------------------------
    | Fallback to Core Views
    |--------------------------------------------------------------------------
    |
    | When enabled, if a view is not found in the active theme,
    | the system will attempt to load it from the core views directory.
    |
    */
    'fallback_to_core' => true,

    /*
    |--------------------------------------------------------------------------
    | Core Views Directory
    |--------------------------------------------------------------------------
    |
    | The directory containing fallback core views.
    | These are used when a theme doesn't provide a specific view.
    |
    */
    'core_directory' => 'views/core',

];

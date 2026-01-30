<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

/**
 * ModulesServiceProvider - Master Module Loader
 * 
 * Handles dynamic loading of optional modules from the Modules/ directory.
 * Implements the "Ghost Pattern" for disabled modules to prevent Blade crashes.
 * 
 * When a module is ENABLED:
 *   - Its ServiceProvider is loaded normally (routes, views, migrations, components)
 * 
 * When a module is DISABLED:
 *   - "Ghost Components" are registered that render empty strings
 *   - This prevents "Unable to locate component" errors when Blade compiles views
 */
class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Known module components that need fallback registration when disabled.
     * 
     * Format: 'ModuleName' => ['component-name', 'another-component']
     * Add entries here when creating new modules with Blade components.
     */
    protected array $moduleComponents = [
        'Newsletter' => ['newsletter-form'],
        'Contact' => ['contact-form'],
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadModules();
    }

    /**
     * Scan and load all modules from the Modules directory.
     */
    protected function loadModules(): void
    {
        $modulesPath = base_path('Modules');

        if (!File::isDirectory($modulesPath)) {
            return;
        }

        // Get all module directories and sort alphabetically for consistent loading order
        $modules = collect(File::directories($modulesPath))
            ->map(fn($path) => basename($path))
            ->sort()
            ->values();

        foreach ($modules as $moduleName) {
            try {
                $this->processModule($moduleName);
            } catch (\Throwable $e) {
                // Log the error but don't crash the entire application
                Log::error("Failed to load module [{$moduleName}]: " . $e->getMessage(), [
                    'exception' => $e,
                    'module' => $moduleName,
                ]);
            }
        }
    }

    /**
     * Process a single module - either load it fully or register fallbacks.
     */
    protected function processModule(string $moduleName): void
    {
        $featureKey = strtolower($moduleName);
        $isEnabled = config("features.{$featureKey}", false);

        if ($isEnabled) {
            // Module is enabled - it should load itself via its own ServiceProvider
            // (registered in config/app.php or auto-discovered)
            // We don't need to do anything here as the module's provider handles everything
            return;
        }

        // Module is DISABLED - register ghost components to prevent Blade crashes
        $this->registerFallbacks($moduleName);
    }

    /**
     * Register "Ghost Components" for a disabled module.
     * 
     * These are empty components that render nothing, preventing Blade from
     * crashing when it encounters component tags for disabled modules.
     */
    protected function registerFallbacks(string $moduleName): void
    {
        $components = $this->moduleComponents[$moduleName] ?? [];

        foreach ($components as $componentName) {
            // Register an anonymous component that renders an empty string
            // Using Blade::component with a class reference
            Blade::component($componentName, GhostComponent::class);
        }
    }
}

/**
 * Ghost Component - Renders absolutely nothing.
 * Used as a fallback for disabled module components.
 */
class GhostComponent extends \Illuminate\View\Component
{
    public function render(): string
    {
        return '';
    }
}

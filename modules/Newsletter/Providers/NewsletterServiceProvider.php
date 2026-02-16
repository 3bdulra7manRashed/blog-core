<?php

namespace Modules\Newsletter\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class NewsletterServiceProvider extends ServiceProvider
{
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
        // ============================================================
        // CRITICAL: Always register the Blade component path
        // ============================================================
        // Blade resolves components at COMPILE TIME, before runtime conditionals.
        // If we don't register the component path, <x-newsletter-form /> will crash
        // even when wrapped in @if(feature('newsletter')).
        // 
        // The component itself handles the feature check and renders nothing when disabled.
        // ============================================================
        Blade::anonymousComponentPath(__DIR__ . '/../resources/views/components');

        // ============================================================
        // Conditional: Only load routes/views/migrations when enabled
        // ============================================================
        if (!config('features.newsletter', false)) {
            return;
        }

        // 1. Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // 2. Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // 3. Load Views
        // Register 'newsletter' namespace for views (e.g. view('newsletter::emails.campaign'))
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'newsletter');
        
        // Add path to global view paths so view('newsletter.unsubscribed') works
        // and view('admin.campaigns.index') works (since we moved admin views locally)
        View::addLocation(__DIR__ . '/../resources/views');
    }
}

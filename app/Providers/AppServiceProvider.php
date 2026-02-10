<?php

namespace App\Providers;

use App\Models\Post;
use App\Support\Landing\LandingThoughtsManager;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load Core Helpers
        require_once app_path('Helpers/feature.php');

        // Register Landing Thoughts Manager (contract-based provider system)
        $this->app->singleton(LandingThoughtsManager::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share sidebar data with both sidebar partial and blog layout (for mobile menu)
        View::composer(['partials.sidebar', 'layouts.blog'], function ($view) {
            // Guard against running queries if the database is not set up
            if (! \Illuminate\Support\Facades\Schema::hasTable('posts')) {
                $view->with([
                    'mostLikedPosts' => collect(),
                    'mostReadPosts' => collect(),
                ]);
                return;
            }

            $view->with([
                'mostLikedPosts' => Post::published()
                    ->orderByDesc('likes_count')
                    ->take(5)
                    ->get(),
                'mostReadPosts' => Post::published()
                    ->orderByDesc('views')
                    ->take(5)
                    ->get(),
            ]);
        });

        // @module('name') directive - uses the same feature() helper for consistency
        // Usage: @module('newsletter') ... @endmodule
        Blade::if('module', function (string $name) {
            return feature(strtolower($name));
        });
    }
}

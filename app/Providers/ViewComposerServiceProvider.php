<?php

namespace App\Providers;

use App\Models\Post;
use App\View\Composers\AdminLayoutComposer;
use App\View\Composers\OwnerBioComposer;
use App\View\Composers\PublicLayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // =====================================================
        // Theme Boundary Enforcement: Inject computed state into layouts
        // Views must NEVER execute database queries directly.
        // =====================================================

        // Public layout composers (blog.blade.php)
        View::composer([
            'theme::layouts.blog',
            'layouts.blog',
        ], PublicLayoutComposer::class);

        // Admin layout composers (admin.blade.php)
        View::composer([
            'theme::layouts.admin',
            'layouts.admin',
        ], AdminLayoutComposer::class);

        // Owner bio partial composer
        View::composer([
            'partials.owner-bio',
            'theme::partials.owner-bio',
        ], OwnerBioComposer::class);

        // =====================================================
        // Share trending posts data with all views
        // =====================================================
        View::composer('*', function ($view) {
            // Guard against running queries if the database is not set up (e.g. during fresh install)
            if (! \Illuminate\Support\Facades\Schema::hasTable('posts')) {
                $view->with([
                    'trendingRecentPosts' => collect(),
                    'trendingMostLikedPosts' => collect(),
                    'trendingMostReadPosts' => collect(),
                ]);
                return;
            }

            $recentPosts = Post::published()
                ->latest('published_at')
                ->limit(5)
                ->get();

            $mostLikedPosts = Post::published()
                ->orderBy('likes_count', 'desc')
                ->limit(5)
                ->get();

            $mostReadPosts = Post::published()
                ->orderBy('views', 'desc')
                ->limit(5)
                ->get();

            $view->with([
                'trendingRecentPosts' => $recentPosts,
                'trendingMostLikedPosts' => $mostLikedPosts,
                'trendingMostReadPosts' => $mostReadPosts,
            ]);
        });
    }
}

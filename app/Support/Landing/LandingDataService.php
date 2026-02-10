<?php

namespace App\Support\Landing;

use App\Models\Category;
use App\Models\LandingSetting;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Service responsible for resolving all data sections for the Landing page.
 *
 * Extracts business logic from LandingController to maintain clean architecture.
 * All data resolution is defensive — invalid IDs or disabled features return safe defaults.
 * Each DB-backed section is cached for 5 minutes to reduce query load.
 */
class LandingDataService
{
    /** Cache TTL in seconds (5 minutes) */
    private const CACHE_TTL = 300;

    public function __construct(
        protected LandingThoughtsManager $thoughtsManager,
        protected LandingReleasesManager $releasesManager,
    ) {}

    /**
     * Build hero section data with fallbacks.
     */
    public function getHeroData(LandingSetting $settings): array
    {
        return [
            'title' => $settings->hero_title ?: config('app.name'),
            'subtitle' => $settings->hero_subtitle ?: config('branding.site_description', 'مرحباً بكم في موقعنا'),
            'image' => $settings->hero_image,
        ];
    }

    /**
     * Build CTA data.
     */
    public function getCtaData(LandingSetting $settings): array
    {
        return [
            'text' => $settings->cta_text,
            'link' => $settings->cta_link,
        ];
    }

    /**
     * Resolve thoughts section data.
     * Guarded by feature('thoughts') AND settings toggle.
     */
    public function getThoughts(LandingSetting $settings): Collection
    {
        if (!feature('thoughts') || !$settings->show_thoughts) {
            return collect();
        }

        try {
            return $this->thoughtsManager->resolve();
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Load a category section with its posts.
     *
     * @return array{category: Category|null, posts: Collection}
     */
    public function getCategorySection(bool $show, ?int $categoryId): array
    {
        $empty = ['category' => null, 'posts' => collect()];

        if (!$show || !$categoryId) {
            return $empty;
        }

        try {
            $category = Category::find($categoryId);

            if (!$category) {
                return $empty;
            }

            $posts = Cache::remember(
                "landing.category.{$categoryId}",
                self::CACHE_TTL,
                fn () => Post::with(['author', 'categories'])
                    ->posts()
                    ->published()
                    ->whereHas('categories', fn ($q) => $q->where('categories.id', $categoryId))
                    ->latest('published_at')
                    ->take(3)
                    ->get()
            );

            return [
                'category' => $category,
                'posts' => $posts instanceof Collection ? $posts : collect($posts),
            ];
        } catch (\Throwable $e) {
            return $empty;
        }
    }

    /**
     * Resolve khutab section data.
     * Guarded by feature('khutab') AND settings toggle.
     */
    public function getKhutab(LandingSetting $settings): array
    {
        $empty = ['category' => null, 'posts' => collect()];

        if (!feature('khutab') || !$settings->show_khutab) {
            return $empty;
        }

        try {
            if (!$settings->khutab_category_id) {
                // No specific category — return latest khutab
                $posts = Cache::remember(
                    'landing.khutab.latest',
                    self::CACHE_TTL,
                    fn () => Post::with(['author', 'categories'])
                        ->khutab()
                        ->published()
                        ->latest('published_at')
                        ->take(3)
                        ->get()
                );

                return ['category' => null, 'posts' => $posts instanceof Collection ? $posts : collect($posts)];
            }

            $category = Category::find($settings->khutab_category_id);

            if (!$category) {
                // Invalid category ID — fallback to latest 3 khutab safely
                $posts = Cache::remember(
                    'landing.khutab.latest',
                    self::CACHE_TTL,
                    fn () => Post::with(['author', 'categories'])
                        ->khutab()
                        ->published()
                        ->latest('published_at')
                        ->take(3)
                        ->get()
                );

                return ['category' => null, 'posts' => $posts instanceof Collection ? $posts : collect($posts)];
            }

            $posts = Cache::remember(
                "landing.khutab.category.{$settings->khutab_category_id}",
                self::CACHE_TTL,
                fn () => Post::with(['author', 'categories'])
                    ->khutab()
                    ->published()
                    ->whereHas('categories', fn ($q) => $q->where('categories.id', $settings->khutab_category_id))
                    ->latest('published_at')
                    ->take(3)
                    ->get()
            );

            return [
                'category' => $category,
                'posts' => $posts instanceof Collection ? $posts : collect($posts),
            ];
        } catch (\Throwable $e) {
            return $empty;
        }
    }

    /**
     * Resolve releases section data.
     * Guarded by settings toggle AND feature('books').
     */
    public function getReleases(LandingSetting $settings): Collection
    {
        if (!$settings->show_releases || !feature('books')) {
            return collect();
        }

        try {
            $releases = Cache::remember(
                'landing.releases',
                self::CACHE_TTL,
                fn () => $this->releasesManager->resolve(3)
            );

            return $releases instanceof Collection ? $releases : collect($releases);
        } catch (\Throwable $e) {
            return collect();
        }
    }

    /**
     * Get latest posts for the quotes/articles section.
     */
    public function getLatestPosts(LandingSetting $settings, int $limit = 6): Collection
    {
        if (!$settings->show_quotes_section) {
            return collect();
        }

        try {
            $posts = Cache::remember(
                'landing.latest_posts',
                self::CACHE_TTL,
                fn () => Post::with(['author', 'categories'])
                    ->posts()
                    ->published()
                    ->latest('published_at')
                    ->limit($limit)
                    ->get()
            );

            return $posts instanceof Collection ? $posts : collect($posts);
        } catch (\Throwable $e) {
            return collect();
        }
    }
}

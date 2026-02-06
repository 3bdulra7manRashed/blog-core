<?php

use App\Support\SEO\SeoManager;

if (!function_exists('seo')) {
    /**
     * Get the SeoManager instance.
     *
     * Usage:
     *   seo()->forModel($post);
     *   seo()->forPage(['title' => 'My Page']);
     *   seo()->render();
     *
     * @return SeoManager
     */
    function seo(): SeoManager
    {
        return app(SeoManager::class);
    }
}

if (!function_exists('seo_for')) {
    /**
     * Quick helper to set SEO data for a Seoable model.
     *
     * Usage in controller:
     *   seo_for($post);
     *
     * @param \App\Contracts\Seoable $model
     * @return SeoManager
     */
    function seo_for(\App\Contracts\Seoable $model): SeoManager
    {
        return app(SeoManager::class)->forModel($model);
    }
}

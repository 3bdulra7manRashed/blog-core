<?php

namespace Modules\AdvancedSeo\Providers;

use Illuminate\Support\ServiceProvider;

class AdvancedSeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // enforce feature flag
        if (! config('features.advanced_seo', false)) {
            return;
        }

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'advanced-seo');
    }
}

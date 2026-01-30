<?php

namespace Modules\Seo\Providers;

use Illuminate\Support\ServiceProvider;

class SeoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // enforce feature flag
        if (! config('features.seo', true)) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
    }
}

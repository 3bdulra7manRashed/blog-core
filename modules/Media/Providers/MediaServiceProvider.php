<?php

namespace Modules\Media\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Media\Models\Media;
use Modules\Media\Policies\MediaPolicy;

class MediaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Load Routes
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        // 2. Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        // 3. Load Views
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'media');
        
        // Add path to global view paths (optional, for direct access if needed)
        View::addLocation(__DIR__ . '/../Resources/views');

        // 4. Register Policy
        Gate::policy(Media::class, MediaPolicy::class);
    }
}

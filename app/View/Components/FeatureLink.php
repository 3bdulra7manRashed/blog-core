<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Route;

class FeatureLink extends Component
{
    public string $feature;
    public string $route;

    /**
     * Create a new component instance.
     *
     * @param string $feature The feature key from config/features.php
     * @param string $route The named route to check and link to
     */
    public function __construct(string $feature, string $route)
    {
        $this->feature = $feature;
        $this->route = $route;
    }

    /**
     * Determine if the component should be rendered.
     */
    public function shouldRender(): bool
    {
        // 1. Check Feature Flag
        // Uses the global helper 'feature()' which checks config
        if (! feature($this->feature)) {
            return false;
        }

        // 2. Check Route Existence (Safe Guard)
        // This prevents RouteNotFoundException if the feature route 
        // is conditionally disabled in routes/web.php
        if (! Route::has($this->route)) {
            return false;
        }

        return true;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.feature-link');
    }
}

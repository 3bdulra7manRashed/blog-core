<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the application's root index.
     * Dynamically switches between the Landing Page and the Blog Index
     * based on the 'landing' feature flag.
     * This avoids using 'if(feature())' in route files, which breaks 'route:cache'.
     */
    public function index()
    {
        if (config('features.landing')) {
            // Using the Landing Module's Controller via app()->call() for dependency injection
            return app()->call([\Modules\Landing\Http\Controllers\LandingController::class, 'index']);
        }
        
        // Default to the Blog's PostController index
        return app()->call([\App\Http\Controllers\PostController::class, 'index']);
    }
}

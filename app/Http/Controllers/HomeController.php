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
            $landingController = app(\Modules\Landing\Http\Controllers\LandingController::class);
            return app()->call([$landingController, 'index']);
        }

        $postController = app(\App\Http\Controllers\PostController::class);
        return app()->call([$postController, 'index']);
    }
}

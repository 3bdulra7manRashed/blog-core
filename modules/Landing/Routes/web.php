<?php

use Illuminate\Support\Facades\Route;
use Modules\Landing\Http\Controllers\LandingController;

/*
|--------------------------------------------------------------------------
| Landing Public Routes
|--------------------------------------------------------------------------
|
| When the 'landing' feature is enabled, this module overrides the root
| route "/" to display the custom landing page. The original homepage
| is moved to "/posts" to maintain access to the posts index.
|
| Route Priority: These routes are loaded via bootstrap/app.php's 'then'
| callback AFTER core routes, giving them override capability.
|
*/

if (feature('landing')) {
    // Override homepage with landing page
    Route::get('/', [LandingController::class, 'index'])
        ->name('landing.home');

    // Move original homepage to /posts
    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'index'])
        ->name('home');
}

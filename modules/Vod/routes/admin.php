<?php

use Illuminate\Support\Facades\Route;
use Modules\Vod\Http\Controllers\Admin\VodContentController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Loaded by VodServiceProvider within group:
| middleware => ['web', 'auth', 'role:admin|moderator']
| prefix => 'admin'
| name => 'admin.'
*/

Route::prefix('vod')->name('vod.')->group(function () {

    // Manage Vod Contents (Video/Audio)
    // Manage Vod Contents (Video/Audio) - Require at least one enabled
    Route::resource('contents', VodContentController::class)
        ->middleware('vod.feature:video,audio');

    // Playlists (Protected by feature flag)
    Route::resource('playlists', \Modules\Vod\Http\Controllers\Admin\VodPlaylistController::class)
        ->middleware('vod.feature:playlists');

});

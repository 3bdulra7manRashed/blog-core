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
    Route::resource('contents', VodContentController::class);

    // Playlists
    Route::resource('playlists', \Modules\Vod\Http\Controllers\Admin\VodPlaylistController::class);

});

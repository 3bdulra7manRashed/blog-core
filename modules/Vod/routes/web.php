<?php

use Illuminate\Support\Facades\Route;
use Modules\Vod\Http\Controllers\VodController;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
| Loaded by VodServiceProvider within group:
| middleware => ['web']
*/

Route::prefix('videos')->name('videos.')->group(function () {
    Route::get('/', [VodController::class, 'indexVideos'])->name('index');
    Route::get('/playlist/{slug}', [VodController::class, 'showPlaylist'])->name('playlists.show');
    Route::get('/{slug}', [VodController::class, 'show'])->name('show');
});

Route::prefix('audios')->name('audios.')->group(function () {
    Route::get('/', [VodController::class, 'indexAudios'])->name('index');
    Route::get('/{slug}', [VodController::class, 'show'])->name('show');
});

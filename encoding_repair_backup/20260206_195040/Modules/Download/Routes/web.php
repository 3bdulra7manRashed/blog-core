<?php

use Illuminate\Support\Facades\Route;
use Modules\Download\Http\Controllers\Admin\DownloadController;
use Modules\Download\Http\Controllers\PublicDownloadController;

/*
|--------------------------------------------------------------------------
| Download Module Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::middleware(['web'])->group(function () {
    Route::get('d/{slug}', [PublicDownloadController::class, 'download'])->name('downloads.public');
});

// Admin Routes
Route::prefix('admin')
    ->middleware(['web', 'auth', 'role:admin|moderator']) // Add web middleware to ensure session works
    ->name('admin.')
    ->group(function () {
        Route::resource('downloads', DownloadController::class)->only(['index', 'store', 'destroy']);
        Route::patch('downloads/{download}/toggle', [DownloadController::class, 'toggle'])->name('downloads.toggle');
    });

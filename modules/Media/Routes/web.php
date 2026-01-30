<?php

use Illuminate\Support\Facades\Route;
use Modules\Media\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Media Module Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'role:admin|moderator'])->name('admin.')->group(function () {
    Route::resource('media', MediaController::class)->except(['show', 'edit', 'update']);
    Route::post('media/upload', [MediaController::class, 'store'])->name('media.upload');
    Route::post('upload-image', [MediaController::class, 'upload'])->name('upload.image');
});

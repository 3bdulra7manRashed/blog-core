<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin|moderator', 'feature:media'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // --------------------------------------------------
        // Legacy Media Library (Images/Files)
        // --------------------------------------------------
        Route::prefix('media/library')->name('media.library.')->group(function () {
            Route::get('/', [\Modules\Media\Http\Controllers\MediaController::class, 'index'])->name('index');
            Route::post('/', [\Modules\Media\Http\Controllers\MediaController::class, 'store'])->name('store');
            Route::delete('/{id}', [\Modules\Media\Http\Controllers\MediaController::class, 'destroy'])->name('destroy');
            Route::post('/upload', [\Modules\Media\Http\Controllers\MediaController::class, 'upload'])->name('upload'); // For CKEditor/AJAX
        });

    });

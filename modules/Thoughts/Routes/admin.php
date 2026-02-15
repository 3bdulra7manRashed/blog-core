<?php

use Illuminate\Support\Facades\Route;
use Modules\Thoughts\Http\Controllers\Admin\ThoughtController;

/*
|--------------------------------------------------------------------------
| Thoughts Admin Routes
|--------------------------------------------------------------------------
|
| Admin routes for managing thoughts (CRUD).
| Protected by feature flag, auth and admin middleware.
|
*/

if (feature('thoughts')) {
    Route::prefix('admin/thoughts')
        ->name('admin.thoughts.')
        ->middleware(['web', 'auth', 'admin', 'feature:thoughts'])
        ->group(function () {
            Route::get('/', [ThoughtController::class, 'index'])->name('index');
            Route::get('/create', [ThoughtController::class, 'create'])->name('create');
            Route::post('/', [ThoughtController::class, 'store'])->name('store');
            Route::get('/{thought}/edit', [ThoughtController::class, 'edit'])->name('edit');
            Route::put('/{thought}', [ThoughtController::class, 'update'])->name('update');
            Route::delete('/{thought}', [ThoughtController::class, 'destroy'])->name('destroy');
        });
}

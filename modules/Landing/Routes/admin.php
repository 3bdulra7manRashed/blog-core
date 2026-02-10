<?php

use Illuminate\Support\Facades\Route;
use Modules\Landing\Http\Controllers\Admin\LandingSettingsController;

/*
|--------------------------------------------------------------------------
| Landing Admin Routes
|--------------------------------------------------------------------------
|
| Admin routes for configuring the landing page settings.
| Protected by auth and admin middleware.
|
*/

if (feature('landing')) {
    Route::prefix('admin/landing')
        ->name('admin.landing.')
        ->middleware(['web', 'auth', 'admin'])
        ->group(function () {
            Route::get('/settings', [LandingSettingsController::class, 'edit'])->name('settings');
            Route::put('/settings', [LandingSettingsController::class, 'update'])->name('settings.update');
        });
}

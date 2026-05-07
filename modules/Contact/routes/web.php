<?php

use Illuminate\Support\Facades\Route;
use Modules\Contact\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Contact Module Routes
|--------------------------------------------------------------------------
|
| These routes are loaded by the ContactServiceProvider when the contact
| feature is enabled. They handle the public contact page and form submission.
|
*/

Route::middleware(['web', 'feature:contact'])->group(function () {
    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::post('/contact', [ContactController::class, 'send'])->name('contact.send')
        ->middleware('throttle:3,10');
});

/*
|--------------------------------------------------------------------------
| Admin Contact Messages Routes
|--------------------------------------------------------------------------
*/

use Modules\Contact\Http\Controllers\Admin\MessageController;

Route::prefix('admin')->middleware(['web', 'auth', 'role:admin|moderator', 'feature:contact'])->name('admin.')->group(function () {
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{message}', [MessageController::class, 'show'])->name('messages.show');
    Route::delete('messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::patch('messages/{message}/toggle-read', [MessageController::class, 'toggleRead'])->name('messages.toggle-read');
    Route::post('messages/mark-all-read', [MessageController::class, 'markAllRead'])->name('messages.mark-all-read');
    Route::delete('messages/delete-read', [MessageController::class, 'deleteRead'])->name('messages.delete-read');
});


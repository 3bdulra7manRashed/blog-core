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

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

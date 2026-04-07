<?php

use Illuminate\Support\Facades\Route;

Route::prefix('books')->middleware('feature:books')->group(function () {
    Route::get('/', [\Modules\Books\Http\Controllers\BookController::class, 'index'])->name('books.index');
    Route::get('/{slug}', [\Modules\Books\Http\Controllers\BookController::class, 'show'])->name('books.show');
});

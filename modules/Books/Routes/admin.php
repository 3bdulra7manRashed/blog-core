<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin/books')
    ->name('admin.books.')
    ->middleware(['web', 'auth', 'admin', 'feature:books'])
    ->group(function () {
        Route::get('/', [\Modules\Books\Http\Controllers\Admin\BookController::class, 'index'])->name('index');
        Route::get('/create', [\Modules\Books\Http\Controllers\Admin\BookController::class, 'create'])->name('create');
        Route::post('/', [\Modules\Books\Http\Controllers\Admin\BookController::class, 'store'])->name('store');
        Route::get('/{book}/edit', [\Modules\Books\Http\Controllers\Admin\BookController::class, 'edit'])->name('edit');
        Route::put('/{book}', [\Modules\Books\Http\Controllers\Admin\BookController::class, 'update'])->name('update');
        Route::delete('/{book}', [\Modules\Books\Http\Controllers\Admin\BookController::class, 'destroy'])->name('destroy');
    });

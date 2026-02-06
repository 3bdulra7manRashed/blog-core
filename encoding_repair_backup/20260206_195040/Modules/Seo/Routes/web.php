<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\SitemapController;

Route::middleware(['web'])->group(function () {
    Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
});

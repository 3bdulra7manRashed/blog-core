<?php

use Illuminate\Support\Facades\Route;
use Modules\Seo\Http\Controllers\SitemapController;

Route::middleware(['web', 'feature:seo'])->group(function () {
    Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
});

<?php

use Illuminate\Support\Facades\Route;

Route::get('/see-file', function() {
    $path = resource_path('themes/admin/classic/views/layouts/admin.blade.php');
    if (!file_exists($path)) {
        return "File not found at: " . $path;
    }
    return response(file_get_contents($path))
            ->header('Content-Type', 'text/plain; charset=utf-8');
});

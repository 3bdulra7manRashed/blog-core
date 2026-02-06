<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/debug-admin-theme', function () {
    $themeDirectory = config('theme.directory', 'themes');
    $activeTheme = config('theme.admin_active', 'classic'); // Explicitly test admin config
    
    // Construct path manually to verify
    $themePath = resource_path("{$themeDirectory}/admin/{$activeTheme}/views");
    
    // Simulate what ThemeServiceProvider Does
    $finder = view()->getFinder();
    $hints = $finder->getHints();
    $paths = $finder->getPaths();

    return [
        'Config: admin_active' => $activeTheme,
        'Calculated Path' => $themePath,
        'Is Directory?' => is_dir($themePath) ? 'YES' : 'NO',
        'Layout File Exists?' => file_exists($themePath . '/layouts/admin.blade.php') ? 'YES' : 'NO',
        'Registered "theme" Namespace' => $hints['theme'] ?? 'NOT FOUND',
        'All View Paths' => $paths,
    ];
});

<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

Route::get('/debug-fail', function () {
    // 1. File System Reality
    $themeDir = 'resources/themes/admin/classic/views';
    $layoutFile = $themeDir . '/layouts/admin.blade.php';
    $absLayoutPath = base_path($layoutFile);
    
    // 2. Config Reality
    $adminThemeConfig = config('theme.admin_active');
    
    // 3 & 4. ViewFinder State
    $finder = view()->getFinder();
    $globalPaths = $finder->getPaths();
    $hints = $finder->getHints();
    
    // 5. Resolution Attempt
    $existsGlobal = View::exists('layouts.admin');
    $existsNamespaced = View::exists('theme::layouts.admin');
    
    return [
        '1_FileSystem' => [
            'Target Path' => $absLayoutPath,
            'Exists?' => file_exists($absLayoutPath) ? 'PASS' : 'FAIL',
        ],
        '2_Config' => [
            'theme.admin_active' => $adminThemeConfig,
        ],
        '3_ViewFinder_GlobalPaths' => $globalPaths,
        '4_ViewFinder_Namespaces' => [
            'theme' => $hints['theme'] ?? 'MISSING',
        ],
        '5_Resolution' => [
            'layouts.admin (Global)' => $existsGlobal ? 'FOUND' : 'NOT FOUND',
            'theme::layouts.admin (Namespaced)' => $existsNamespaced ? 'FOUND' : 'NOT FOUND',
        ],
    ];
});

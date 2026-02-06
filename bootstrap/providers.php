<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\GlobalStatsServiceProvider::class,  // Must be before ViewComposerServiceProvider
    App\Providers\ViewComposerServiceProvider::class,
    App\Providers\ThemeServiceProvider::class,
    App\Providers\SeoServiceProvider::class,
    App\Providers\CKEditorServiceProvider::class,
    Modules\Media\Providers\MediaServiceProvider::class,
    Modules\Books\Providers\BooksServiceProvider::class,
];

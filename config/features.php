<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Flags
    |--------------------------------------------------------------------------
    |
    | This configuration file determines which experimental or optional
    | features are enabled for the application.
    |
    */

    'newsletter' => false,
    'contact' => false,
    'download' => false,
    'manage_admins' => true,
    'seo' => true, //Basic SEO (Core)
    'media_pages' => true,
    'media' => true, // Core File Library
    'vod' => [
        'enabled' => true,
        'video' => true,
        'audio' => true,
        'playlists' => true,
    ],
    'advanced_seo' => false,
    'books' => true,
];

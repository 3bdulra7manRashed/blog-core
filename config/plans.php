<?php

/*
|--------------------------------------------------------------------------
| Plan Definitions
|--------------------------------------------------------------------------
|
| Each plan defines which features are enabled by default.
| These values are used when USE_PLAN_SYSTEM=true in the environment.
|
| Plans are referenced by the CLIENT_PLAN env variable.
| Individual features can still be overridden via FEATURE_XXX env vars.
|
*/

return [

    'basic' => [
        'contact' => false,
        'newsletter' => false,
        'download' => false,
        'manage_admins' => false,
        'advanced_seo' => false,
        'media_pages' => false,
        'thoughts' => false,
        'books' => false,
        'khutab' => false,
        'landing' => false,
        'vod.video' => false,
        'vod.audio' => false,
        'vod.playlists' => false,
    ],

    'pro' => [
        'contact' => true,
        'newsletter' => true,
        'download' => true,
        'manage_admins' => true,
        'advanced_seo' => true,
        'media_pages' => true,
        'thoughts' => false,
        'books' => false,
        'khutab' => true,
        'landing' => false,
        'vod.video' => true,
        'vod.audio' => true,
        'vod.playlists' => false,
    ],

    'business' => [
        'contact' => true,
        'newsletter' => true,
        'download' => true,
        'manage_admins' => true,
        'advanced_seo' => true,
        'media_pages' => true,
        'thoughts' => true,
        'books' => true,
        'khutab' => true,
        'landing' => true,
        'vod.video' => true,
        'vod.audio' => true,
        'vod.playlists' => true,
    ],

];

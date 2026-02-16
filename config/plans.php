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
| IMPORTANT: This file is loaded by config/features.php at boot time.
| It must NOT contain closures, env() calls, or dynamic require()s.
| All values here are static booleans — the resolution happens in
| config/features.php which is the only place env() is called.
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
        'vod_video' => false,
        'vod_audio' => false,
        'vod_playlists' => false,
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
        'vod_video' => true,
        'vod_audio' => true,
        'vod_playlists' => false,
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
        'vod_video' => true,
        'vod_audio' => true,
        'vod_playlists' => true,
    ],

];

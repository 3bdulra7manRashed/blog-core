<?php

/*
|--------------------------------------------------------------------------
| Feature Flags
|--------------------------------------------------------------------------
|
| This configuration file determines which optional features are enabled.
|
| RESOLUTION PRECEDENCE:
|
| 1) If USE_PLAN_SYSTEM=true (default):
|    - Base values come from config/plans.php for the active CLIENT_PLAN
|    - Any explicitly set FEATURE_XXX env var overrides the plan value
|
| 2) If USE_PLAN_SYSTEM=false:
|    - Only FEATURE_XXX env vars are used (pure feature flags mode)
|    - All features default to false unless explicitly enabled
|
| CACHE SAFETY:
|    - No closures (config:cache requires serializable arrays)
|    - All env() calls are in THIS file only (the Laravel convention)
|    - No dynamic require() — plans are loaded once at the top
|    - After `php artisan config:cache`, env() is never called again;
|      the cached array is returned directly.
|
| Access patterns:
|    config('features.newsletter')
|    config('features.vod.video')
|    feature('newsletter')
|
*/

// ── Read control variables ──────────────────────────────────────────────────
$usePlanSystem = (bool) env('USE_PLAN_SYSTEM', true);
$planName = env('CLIENT_PLAN', 'basic');

// ── Load plan defaults (static array, no closures) ──────────────────────────
$plans = require __DIR__ . '/plans.php';
$planData = $plans[$planName] ?? $plans['basic'];

// ── Resolver: plan value → env override ─────────────────────────────────────
// For each feature we check: if a FEATURE_XXX env var is set it wins,
// otherwise the plan default is used.  When the plan system is off,
// the fallback is always `false`.

$resolve = static function (string $key, bool $default = false) use ($usePlanSystem, $planData): bool {
    // Build the env var name: 'vod_video' → 'FEATURE_VOD_VIDEO'
    $envKey = 'FEATURE_' . strtoupper($key);

    if (!$usePlanSystem) {
        return (bool) env($envKey, $default);
    }

    $planValue = $planData[$key] ?? $default;

    return (bool) env($envKey, $planValue);
};

// ── Build the final config array (returned & cached by Laravel) ─────────────

return [

    // Plan metadata
    'use_plan_system' => $usePlanSystem,
    'plan' => $planName,

    // Core (always-on, but still overridable via env)
    'seo' => (bool) env('FEATURE_SEO', true),
    'media' => (bool) env('FEATURE_MEDIA', true),

    // Plan-controlled features
    'newsletter' => $resolve('newsletter'),
    'contact' => $resolve('contact'),
    'download' => $resolve('download'),
    'manage_admins' => $resolve('manage_admins'),
    'advanced_seo' => $resolve('advanced_seo'),
    'media_pages' => $resolve('media_pages'),
    'thoughts' => $resolve('thoughts'),
    'books' => $resolve('books'),
    'khutab' => $resolve('khutab'),
    'landing' => $resolve('landing'),
    'about' => $resolve('about'),
    'general_settings' => $resolve('general_settings'),

    // VOD sub-features
    'vod' => [
        'video' => $resolve('vod_video'),
        'audio' => $resolve('vod_audio'),
        'playlists' => $resolve('vod_playlists'),
    ],

];

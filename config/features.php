<?php

/*
|--------------------------------------------------------------------------
| Feature Flags
|--------------------------------------------------------------------------
|
| This configuration file determines which experimental or optional
| features are enabled for the application.
|
| RESOLUTION PRECEDENCE:
|
| 1) If USE_PLAN_SYSTEM=true (default):
|    - Base values come from config/plans.php for the active CLIENT_PLAN
|    - Any explicitly set FEATURE_XXX env var overrides the plan value
|
| 2) If USE_PLAN_SYSTEM=false:
|    - Only FEATURE_XXX env vars are used (pure feature flags mode)
|    - The $default fallback applies if FEATURE_XXX is not set
|
| Access patterns remain unchanged:
|    config('features.newsletter')
|    config('features.vod.video')
|    feature('newsletter')
|
*/

$usePlanSystem = env('USE_PLAN_SYSTEM', true);
$plan = env('CLIENT_PLAN', 'basic');

/**
 * Resolve a feature flag value.
 *
 * When the plan system is active, the plan value is loaded first,
 * then any explicit FEATURE_XXX env var takes priority.
 *
 * When the plan system is disabled, only FEATURE_XXX env vars are used.
 *
 * @param  bool   $usePlanSystem  Whether the plan system is active
 * @param  string $plan           The active plan name
 * @param  string $key            Dot-notation feature key (e.g. 'vod.video')
 * @param  bool   $default        Fallback if neither plan nor env is set
 * @return bool
 */
$resolve = function (bool $usePlanSystem, string $plan, string $key, bool $default = false): bool {
    $envKey = 'FEATURE_' . strtoupper(str_replace('.', '_', $key));

    if (!$usePlanSystem) {
        return (bool) env($envKey, $default);
    }

    // Load the plan value from config/plans.php (using flat dot-key lookup)
    $plans = require __DIR__ . '/plans.php';
    $planData = $plans[$plan] ?? [];
    $planValue = $planData[$key] ?? $default;

    return (bool) env($envKey, $planValue);
};

return [

    'plan' => $plan,

    'newsletter' => $resolve($usePlanSystem, $plan, 'newsletter'),
    'contact' => $resolve($usePlanSystem, $plan, 'contact'),
    'download' => $resolve($usePlanSystem, $plan, 'download'),
    'manage_admins' => $resolve($usePlanSystem, $plan, 'manage_admins'),
    'seo' => env('FEATURE_SEO', true), // Basic SEO (Core — always available)
    'media_pages' => $resolve($usePlanSystem, $plan, 'media_pages'),
    'media' => env('FEATURE_MEDIA', true), // Core File Library — always available
    'vod' => [
        'video' => $resolve($usePlanSystem, $plan, 'vod.video'),
        'audio' => $resolve($usePlanSystem, $plan, 'vod.audio'),
        'playlists' => $resolve($usePlanSystem, $plan, 'vod.playlists'),
    ],
    'advanced_seo' => $resolve($usePlanSystem, $plan, 'advanced_seo'),
    'books' => $resolve($usePlanSystem, $plan, 'books'),
    'khutab' => $resolve($usePlanSystem, $plan, 'khutab'),
    'landing' => $resolve($usePlanSystem, $plan, 'landing'),
    'thoughts' => $resolve($usePlanSystem, $plan, 'thoughts'),

];

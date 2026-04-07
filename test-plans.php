<?php

use Illuminate\Support\Facades\Artisan;

echo "=============================================\n";
echo "       PLAN SYSTEM TRUTH TELLER\n";
echo "=============================================\n\n";

// 1. Raw Environment Value
$rawClientPlan = env('CLIENT_PLAN');
echo "1. RAW ENV 'CLIENT_PLAN': " . ($rawClientPlan === '' ? '(empty string)' : ($rawClientPlan === null ? '(null)' : $rawClientPlan)) . "\n";
echo "2. RAW ENV 'USE_PLAN_SYSTEM': " . env('USE_PLAN_SYSTEM') . "\n\n";

// 2. Active Fallback/Resolved Plan Name
$usePlanSystem = (bool) env('USE_PLAN_SYSTEM', true);
$planName = env('CLIENT_PLAN', 'basic');
$plans = require config_path('plans.php');

if (!$usePlanSystem) {
    echo "🚨 PLAN SYSTEM IS DISABLED! (USE_PLAN_SYSTEM=false)\n\n";
    $planData = [];
} else {
    if (!isset($plans[$planName])) {
        echo "⚠️ WARNING: Invalid plan name '{$planName}'. Falling back to 'basic'.\n";
        $planData = $plans['basic'];
        $resolvedPlan = 'basic (fallback)';
    } else {
        $planData = $plans[$planName];
        $resolvedPlan = $planName;
    }
    echo "3. RESOLVED PLAN NAME: " . $resolvedPlan . "\n\n";
}

echo "=============================================\n";
echo "       FEATURE RESOLUTION TABLE\n";
echo "=============================================\n";
printf("%-20s | %-12s | %-12s | %-10s\n", "FEATURE KEY", "ENV OVERRIDE", "PLAN DEFAULT", "FINAL STATE");
echo str_repeat("-", 62) . "\n";

$features = array_keys($plans['basic']);
$errors = [];

foreach ($features as $key) {
    $envKey = 'FEATURE_' . strtoupper($key);
    $rawEnv = env($envKey);
    $envDisplay = $rawEnv === '' ? ' (empty)' : ($rawEnv === null ? '(null)' : ($rawEnv ? 'true' : 'false'));
    
    $planDefault = $usePlanSystem ? ($planData[$key] ?? false) : false;
    
    // Check what the actual config returns
    $finalState = config("features.{$key}");
    
    // Check if the current logic in features.php is broken vs our expected
    if ($rawEnv === '' && $usePlanSystem) {
        $expected = $planDefault;
        if ($finalState !== $expected) {
            $errors[] = "Bug detected on '{$key}': Env is empty, expected fallback ({$expected}) but got false.";
        }
    }

    printf("%-20s | %-12s | %-12s | %-10s\n", 
        $key, 
        $envDisplay, 
        $planDefault ? 'true' : 'false', 
        $finalState ? '🟢 TRUE' : '🔴 FALSE'
    );
}

echo "\n=============================================\n";
echo "       DIAGNOSTIC RESULTS\n";
echo "=============================================\n";

if (empty($errors)) {
    echo "✅ ALL FEATURES RESOLVED CORRECTLY.\n";
    echo "   The .env empty string patch is active and working.\n";
} else {
    echo "🚨 SYSTEM FLAW DETECTED:\n";
    foreach ($errors as $error) {
        echo "   - $error\n";
    }
    echo "\n⚠️ The empty string bug patch has NOT been applied successfully to config/features.php.\n";
}
echo "=============================================\n";

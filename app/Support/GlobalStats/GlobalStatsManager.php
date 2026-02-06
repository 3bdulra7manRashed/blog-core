<?php

namespace App\Support\GlobalStats;

use App\Contracts\HasGlobalStats;
use Illuminate\Support\Facades\Log;

/**
 * Global Stats Manager
 * 
 * Collects statistics from all registered providers implementing HasGlobalStats.
 * Merges them into a single associative array for use in View Composers.
 * 
 * This is a safe collector that:
 * - Never throws if a module is disabled
 * - Ignores non-existent classes
 * - Guards with feature() checks
 * - Returns empty array if nothing registered
 */
class GlobalStatsManager
{
    /**
     * Registered stats provider configurations.
     * 
     * Each entry: ['class' => FQCN, 'feature' => 'feature.name' or null]
     *
     * @var array<int, array{class: class-string<HasGlobalStats>, feature: string|null}>
     */
    protected array $providers = [];

    /**
     * Register a stats provider.
     *
     * @param class-string<HasGlobalStats> $class Fully qualified class name implementing HasGlobalStats
     * @param string|null $feature Optional feature flag to check before resolving
     * @return self
     */
    public function register(string $class, ?string $feature = null): self
    {
        $this->providers[] = [
            'class' => $class,
            'feature' => $feature,
        ];

        return $this;
    }

    /**
     * Resolve and merge all stats from registered providers.
     * 
     * Returns an empty array if:
     * - No providers registered
     * - All providers are feature-disabled
     * - All provider classes don't exist
     *
     * @return array<string, int|bool>
     */
    public function resolve(): array
    {
        $stats = [];

        foreach ($this->providers as $config) {
            $resolved = $this->resolveProvider($config);
            
            if (!empty($resolved)) {
                $stats = array_merge($stats, $resolved);
            }
        }

        return $stats;
    }

    /**
     * Resolve a single provider safely.
     *
     * @param array{class: class-string<HasGlobalStats>, feature: string|null} $config
     * @return array<string, int|bool>
     */
    protected function resolveProvider(array $config): array
    {
        $class = $config['class'];
        $feature = $config['feature'];

        // Guard: Feature flag check
        if ($feature !== null) {
            if (!function_exists('feature') || !feature($feature)) {
                return [];
            }
        }

        // Guard: Class existence check
        if (!class_exists($class)) {
            return [];
        }

        try {
            // Resolve from container
            $provider = app($class);

            // Verify it implements the contract
            if (!$provider instanceof HasGlobalStats) {
                Log::warning("GlobalStatsManager: {$class} does not implement HasGlobalStats");
                return [];
            }

            return $provider->getGlobalStats();

        } catch (\Throwable $e) {
            // Log but never throw - this is infrastructure that must be resilient
            Log::warning("GlobalStatsManager: Failed to resolve {$class}", [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Get list of registered provider classes.
     * Useful for debugging/testing.
     *
     * @return array<int, string>
     */
    public function getRegisteredProviders(): array
    {
        return array_column($this->providers, 'class');
    }

    /**
     * Check if any providers are registered.
     *
     * @return bool
     */
    public function hasProviders(): bool
    {
        return !empty($this->providers);
    }
}

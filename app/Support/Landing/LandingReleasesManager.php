<?php

namespace App\Support\Landing;

use App\Contracts\ProvidesLandingReleases;
use Illuminate\Support\Collection;

class LandingReleasesManager
{
    protected array $providers = [];

    public function register(string $provider): void
    {
        if (is_subclass_of($provider, ProvidesLandingReleases::class)) {
            $this->providers[] = $provider;
        }
    }

    public function resolve(int $limit = 3): Collection
    {
        $collection = collect();

        foreach ($this->providers as $provider) {
            try {
                $instance = app($provider);
                $result = $instance->getLandingReleases($limit);

                // Normalize: ensure result is always a Collection
                $collection = $collection->merge(
                    $result instanceof Collection ? $result : collect($result)
                );
            } catch (\Throwable $e) {
                // Graceful degradation — continue if provider fails
                continue;
            }
        }

        return $collection;
    }
}

<?php

namespace App\Support\Landing;

use App\Contracts\ProvidesLandingThoughts;
use Illuminate\Support\Collection;

class LandingThoughtsManager
{
    protected array $providers = [];

    public function register(string $provider): void
    {
        if (is_subclass_of($provider, ProvidesLandingThoughts::class)) {
            $this->providers[] = $provider;
        }
    }

    public function resolve(): Collection
    {
        $collection = collect();

        foreach ($this->providers as $provider) {
            $instance = app($provider);
            $collection = $collection->merge($instance->getLandingThoughts());
        }

        return $collection;
    }
}

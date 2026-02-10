<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

/**
 * Contract for modules that provide releases (books/publications)
 * for the landing page.
 *
 * Implementations must return a Collection of release items.
 */
interface ProvidesLandingReleases
{
    /**
     * Get latest releases for the landing page.
     *
     * @param int $limit
     * @return Collection
     */
    public function getLandingReleases(int $limit = 3): Collection;
}

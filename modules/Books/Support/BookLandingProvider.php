<?php

namespace Modules\Books\Support;

use App\Contracts\ProvidesLandingReleases;
use Illuminate\Support\Collection;
use Modules\Books\Models\Book;

class BookLandingProvider implements ProvidesLandingReleases
{
    /**
     * Get latest published books for the landing page.
     */
    public function getLandingReleases(int $limit = 3): Collection
    {
        return Book::published()
            ->latest('published_at')
            ->take($limit)
            ->get();
    }
}

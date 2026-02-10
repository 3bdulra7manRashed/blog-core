<?php

namespace Modules\Thoughts\Support;

use App\Contracts\ProvidesLandingThoughts;
use Illuminate\Support\Collection;
use Modules\Thoughts\Models\Thought;

class ThoughtLandingProvider implements ProvidesLandingThoughts
{
    public function getLandingThoughts(): Collection
    {
        return Thought::published()
            ->ordered()
            ->take(10)
            ->get();
    }
}

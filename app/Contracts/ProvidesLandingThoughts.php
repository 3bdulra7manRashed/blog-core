<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface ProvidesLandingThoughts
{
    public function getLandingThoughts(): Collection;
}

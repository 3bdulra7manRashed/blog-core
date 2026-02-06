<?php

namespace App\View\Composers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * View Composer for owner bio partial
 * 
 * Injects the site owner's biography into the view.
 * This removes direct model queries from Blade templates.
 */
class OwnerBioComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $biography = $this->getOwnerBiography();
        
        $view->with([
            'biography' => $biography,
        ]);
    }

    /**
     * Get site owner's biography.
     * Cached for 1 hour.
     */
    protected function getOwnerBiography(): ?string
    {
        return Cache::remember('site_owner_bio', 3600, function () {
            $owner = User::where('is_super_admin', true)
                ->orWhere('id', 1)
                ->first();
                
            return $owner?->biography;
        });
    }
}

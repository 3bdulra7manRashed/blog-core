<?php

namespace App\Support;

use App\Models\User;
use Exception;

class SiteOwnerResolver
{
    /**
     * Resolve the Site Owner User model.
     *
     * @return User
     * @throws Exception
     */
    public static function get(): User
    {
        // Use global setting() helper to fetch ID
        $userId = setting('site_owner_user_id');

        if (! $userId) {
            // Logic for when setting is missing - could fallback to 1 or fail
            // Requirement says "Throw a clear exception"
            throw new Exception("Site Owner ID not configured in settings table.");
        }

        $user = User::find($userId);

        if (! $user) {
            throw new Exception("Site Owner (ID: {$userId}) not found in database.");
        }

        return $user;
    }
}

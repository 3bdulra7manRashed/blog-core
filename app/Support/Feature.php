<?php

namespace App\Support;

class Feature
{
    /**
     * Check if VOD module is enabled (any sub-feature active)
     */
    public static function vodEnabled(): bool
    {
        return config('features.vod.video', false)
            || config('features.vod.audio', false)
            || config('features.vod.playlists', false);
    }
}

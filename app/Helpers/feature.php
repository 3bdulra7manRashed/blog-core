<?php

if (!function_exists('feature')) {
    /**
     * Check if a feature is enabled.
     *
     * @param  string  $feature
     * @param  bool  $default
     * @return bool
     */
    function feature(string $feature, bool $default = false): bool
    {
        // Special modular handling for VOD
        if ($feature === 'vod') {
            return vod_enabled();
        }

        $value = config("features.{$feature}", $default);

        if (is_array($value)) {
            return isset($value['enabled']) && (bool) $value['enabled'];
        }

        return (bool) $value;
    }
}

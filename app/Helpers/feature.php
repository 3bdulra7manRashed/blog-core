<?php

if (! function_exists('feature')) {
    /**
     * Check if a feature is enabled.
     *
     * @param  string  $feature
     * @param  bool  $default
     * @return bool
     */
    function feature(string $feature, bool $default = false): bool
    {
        return config("features.{$feature}", $default);
    }
}

<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

if (! function_exists('setting')) {
    /**
     * Get a setting value from the database.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function setting(string $key, mixed $default = null)
    {
        // Cache settings to prevent DB queries on every call
        return Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $value = DB::table('settings')->where('key', $key)->value('value');
            return $value ?? $default;
        });
    }
}

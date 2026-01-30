<?php

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
        return \Illuminate\Support\Facades\Cache::remember("setting.{$key}", 3600, function () use ($key, $default) {
            $value = \Illuminate\Support\Facades\DB::table('settings')->where('key', $key)->value('value');
            return $value ?? $default;
        });
    }
}

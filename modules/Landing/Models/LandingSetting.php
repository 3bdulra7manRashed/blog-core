<?php

namespace Modules\Landing\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'landing_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_mobile_image',
        'show_quotes_section',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'show_quotes_section' => 'boolean',
    ];

    /**
     * Get or create the singleton settings instance.
     *
     * @return static
     */
    public static function getInstance(): static
    {
        return static::firstOrCreate([], [
            'hero_title' => null,
            'hero_subtitle' => null,
            'hero_image' => null,
            'hero_mobile_image' => null,
            'show_quotes_section' => true,
        ]);
    }
}

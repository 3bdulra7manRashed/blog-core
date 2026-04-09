<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingSetting extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'landing_settings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'hero_mobile_image',
        'cta_text',
        'cta_link',
        'show_quotes_section',
        'show_thoughts',
        'show_category_one',
        'category_one_id',
        'show_category_two',
        'category_two_id',
        'show_khutab',
        'khutab_category_id',
        'show_releases',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'show_quotes_section' => 'boolean',
        'show_thoughts' => 'boolean',
        'show_category_one' => 'boolean',
        'show_category_two' => 'boolean',
        'show_khutab' => 'boolean',
        'show_releases' => 'boolean',
        'category_one_id' => 'integer',
        'category_two_id' => 'integer',
        'khutab_category_id' => 'integer',
    ];

    /**
     * Get or create the singleton settings instance.
     *
     * @return static
     */
    public static function current(): static
    {
        return static::firstOrCreate([], [
            'hero_title' => null,
            'hero_subtitle' => null,
            'hero_image' => null,
            'cta_text' => null,
            'cta_link' => null,
            'show_quotes_section' => true,
            'show_thoughts' => true,
            'show_category_one' => false,
            'show_category_two' => false,
            'show_khutab' => false,
            'show_releases' => false,
        ]);
    }

    /**
     * Backward-compatible alias for current().
     *
     * @return static
     */
    public static function getInstance(): static
    {
        return static::current();
    }
}

<?php

namespace App\Models;

use App\Contracts\Seoable;
use App\Support\SEO\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;

class Page extends Model implements Seoable
{
    use HasSeo;
    protected $fillable = [
        'slug',
        'title',
        'content',
        'featured_image',
        'layout',
    ];

    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }
        
        // If it's already a full URL
        if (str_starts_with($this->featured_image, 'http')) {
            return str_replace('http://', 'https://', $this->featured_image);
        }
        
        $baseUrl = config('app.url');
        
        // Check if path already has 'storage/' prefix
        if (str_starts_with($this->featured_image, 'storage/')) {
            return $baseUrl . '/' . $this->featured_image;
        }
        
        return $baseUrl . '/storage/' . $this->featured_image;
    }

    // =========================================================================
    // SEO Interface Implementation
    // =========================================================================

    /**
     * Get canonical URL for this page
     */
    public function getSeoCanonicalUrl(): string
    {
        return url($this->slug);
    }

    /**
     * Get SEO type - pages are website type
     */
    public function getSeoType(): string
    {
        return 'website';
    }
}

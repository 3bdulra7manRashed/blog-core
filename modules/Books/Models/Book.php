<?php

namespace Modules\Books\Models;

use App\Contracts\Seoable;
use App\Support\SEO\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model implements Seoable
{
    use HasFactory, SoftDeletes, HasSeo;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'cover_image',
        'external_url',
        'status',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Scope: Published books only
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    // =========================================================================
    // SEO Interface Implementation
    // =========================================================================

    /**
     * Get canonical URL for this book
     */
    public function getSeoCanonicalUrl(): string
    {
        return route('books.show', $this->slug);
    }

    /**
     * Get SEO type - books are products
     */
    public function getSeoType(): string
    {
        return 'product';
    }

    /**
     * Get SEO image URL - uses cover image
     */
    public function getSeoImage(): ?string
    {
        $siteDomain = config('branding.site_domain');

        if (!empty($this->cover_image)) {
            return $this->normalizeImageUrl($this->cover_image, $siteDomain);
        }

        return $siteDomain . '/' . config('branding.default_og_image');
    }

    /**
     * Get resolved thumbnail URL with fallback chain:
     * 1. Cover image (uploaded file or external URL stored in cover_image)
     * 2. Default fallback image
     *
     * Note: Books store both file uploads and external URLs in `cover_image`.
     */
    public function getThumbnailResolvedAttribute(): string
    {
        if (!empty($this->cover_image)) {
            // Already a full URL (external or stored with /storage/ prefix)
            if (str_starts_with($this->cover_image, 'http') || str_starts_with($this->cover_image, '/storage/')) {
                return $this->cover_image;
            }
            return asset('storage/' . $this->cover_image);
        }

        // Default fallback
        return asset(config('branding.default_og_image', 'images/og-default.jpg'));
    }
}

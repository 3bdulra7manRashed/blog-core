<?php

namespace App\Models;

use App\Contracts\Seoable;
use App\Support\SEO\Traits\HasSeo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Modules\Newsletter\Models\Campaign;

class Post extends Model implements Seoable
{
    use HasFactory, HasSeo;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image_path',
        'featured_image_alt',
        'thumbnail_url',
        'is_draft',
        'published_at',
        'likes_count',
        'views',
        'meta',
        'type',
    ];

    protected $casts = [
        'meta' => 'array',
        'published_at' => 'datetime',
        'is_draft' => 'boolean',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the public-facing author identity (Virtual or Real based on config).
     */
    public function getPublishingIdentityAttribute()
    {
        return \App\Support\PublishingIdentityResolver::forPost($this);
    }


    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Get the newsletter campaigns that include this post.
     */
    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class)->withTimestamps();
    }

    public function scopePublished($query)
    {
        return $query->where('is_draft', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope: Only posts (type = 'post')
     */
    public function scopePosts($query)
    {
        return $query->where('type', 'post');
    }

    /**
     * Scope: Only khutab (type = 'khutbah')
     */
    public function scopeKhutab($query)
    {
        return $query->where('type', 'khutbah');
    }

    public function isPublished(): bool
    {
        return !$this->is_draft && !is_null($this->published_at) && $this->published_at->isPast();
    }

    public function getFeaturedImageUrlAttribute(): ?string
    {
        // Priority 1: Uploaded file
        if ($this->featured_image_path) {
            // If it's already a full URL, force HTTPS and return
            if (str_starts_with($this->featured_image_path, 'http')) {
                // Force HTTPS for social sharing compatibility (WhatsApp, Facebook, etc.)
                return str_replace('http://', 'https://', $this->featured_image_path);
            }

            // Get base URL from config (ensures HTTPS in production)
            $baseUrl = config('app.url');

            // Check if path already has 'storage/' prefix to avoid double storage/storage
            if (str_starts_with($this->featured_image_path, 'storage/')) {
                return $baseUrl . '/' . $this->featured_image_path;
            }

            // Otherwise, prepend storage path
            return $baseUrl . '/storage/' . $this->featured_image_path;
        }

        // Priority 2: External thumbnail URL
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        return null;
    }

    /**
     * Generate excerpt automatically from content
     * Returns first paragraph (if exists) or first 30 words
     */
    public function getExcerptAttribute(): string
    {
        // If excerpt is already stored in DB and not empty, use it (backward compatibility)
        if (!empty($this->attributes['excerpt'])) {
            return $this->attributes['excerpt'];
        }

        // If no content, return empty string
        if (empty($this->attributes['content'])) {
            return '';
        }

        $content = $this->attributes['content'];

        // Try to extract first paragraph
        if (preg_match('/<p[^>]*>(.*?)<\/p>/is', $content, $matches)) {
            $firstParagraph = $matches[1];

            // Strip ALL HTML tags to get plain text
            $cleaned = strip_tags($firstParagraph);

            // Remove extra whitespace and decode HTML entities
            $cleaned = html_entity_decode($cleaned, ENT_QUOTES, 'UTF-8');
            $cleaned = preg_replace('/\s+/', ' ', trim($cleaned));

            // Limit to 250 characters
            return Str::limit($cleaned, 250, '...');
        }

        // Fallback: return first 30 words with all tags stripped
        $plainText = strip_tags($content);
        $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        return Str::words($plainText, 30, '...');
    }

    // =========================================================================
    // SEO Interface Implementation
    // =========================================================================

    /**
     * Get canonical URL for this post
     */
    public function getSeoCanonicalUrl(): string
    {
        return route('post.show', $this->slug);
    }

    /**
     * Get SEO type - posts are articles
     */
    public function getSeoType(): string
    {
        return 'article';
    }
}

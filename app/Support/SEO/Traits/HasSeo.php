<?php

namespace App\Support\SEO\Traits;

use App\Support\SEO\SeoData;
use Illuminate\Support\Str;

/**
 * Trait for models implementing Seoable interface
 * 
 * Provides default implementations for common SEO methods.
 * Override these methods in your model for custom behavior.
 */
trait HasSeo
{
    /**
     * Get SEO title with fallback logic:
     * 1. seo_title (if model has it)
     * 2. title
     * 3. name (if exists)
     */
    public function getSeoTitle(): string
    {
        // Check for custom seo_title in meta array
        if (isset($this->meta['seo_title']) && !empty($this->meta['seo_title'])) {
            return $this->meta['seo_title'];
        }

        // Check for seo_title attribute
        if (isset($this->seo_title) && !empty($this->seo_title)) {
            return $this->seo_title;
        }

        // Fallback to title or name
        return $this->title ?? $this->name ?? config('branding.site_name');
    }

    /**
     * Get SEO description with fallback logic:
     * 1. seo_description (if model has it)
     * 2. excerpt
     * 3. Trimmed content (first 160 chars)
     */
    public function getSeoDescription(): string
    {
        // Check for custom seo_description in meta array
        if (isset($this->meta['seo_description']) && !empty($this->meta['seo_description'])) {
            return Str::limit($this->meta['seo_description'], 160);
        }

        // Check for seo_description attribute
        if (isset($this->seo_description) && !empty($this->seo_description)) {
            return Str::limit($this->seo_description, 160);
        }

        // Check for excerpt
        if (isset($this->excerpt) && !empty($this->excerpt)) {
            return Str::limit(strip_tags($this->excerpt), 160);
        }

        // Check for description
        if (isset($this->description) && !empty($this->description)) {
            return Str::limit(strip_tags($this->description), 160);
        }

        // Fallback to content
        if (isset($this->content) && !empty($this->content)) {
            return Str::limit(strip_tags($this->content), 160);
        }

        return config('branding.site_description');
    }

    /**
     * Get canonical URL for this model
     */
    public function getSeoCanonicalUrl(): string
    {
        // Check for custom canonical in meta
        if (isset($this->meta['canonical_url']) && !empty($this->meta['canonical_url'])) {
            return $this->meta['canonical_url'];
        }

        // Use route attribute if exists
        if (isset($this->route)) {
            return $this->route;
        }

        // Default to current URL
        return url()->current();
    }

    /**
     * Get SEO image URL
     */
    public function getSeoImage(): ?string
    {
        $siteDomain = config('branding.site_domain');
        
        // Check for custom seo_image in meta
        if (isset($this->meta['seo_image']) && !empty($this->meta['seo_image'])) {
            return $this->normalizeImageUrl($this->meta['seo_image'], $siteDomain);
        }

        // Check for featured_image_path (Posts)
        if (isset($this->featured_image_path) && !empty($this->featured_image_path)) {
            return $this->normalizeImageUrl($this->featured_image_path, $siteDomain);
        }

        // Check for featured_image (Pages)
        if (isset($this->featured_image) && !empty($this->featured_image)) {
            return $this->normalizeImageUrl($this->featured_image, $siteDomain);
        }

        // Check for thumbnail_path (Media/VOD)
        if (isset($this->thumbnail_path) && !empty($this->thumbnail_path)) {
            return $this->normalizeImageUrl($this->thumbnail_path, $siteDomain);
        }

        // Return default OG image
        return $siteDomain . '/' . config('branding.default_og_image');
    }

    /**
     * Get content type for OpenGraph/Schema
     */
    public function getSeoType(): string
    {
        return 'article';
    }

    /**
     * Get author name for SEO
     */
    public function getSeoAuthor(): ?string
    {
        if (method_exists($this, 'author') && $this->author) {
            return $this->author->name ?? null;
        }

        return config('branding.author.name');
    }

    /**
     * Get published date in W3C format
     */
    public function getSeoPublishedAt(): ?string
    {
        if (isset($this->published_at) && $this->published_at) {
            return $this->published_at->toW3cString();
        }

        if (isset($this->created_at) && $this->created_at) {
            return $this->created_at->toW3cString();
        }

        return null;
    }

    /**
     * Get modified date in W3C format
     */
    public function getSeoModifiedAt(): ?string
    {
        if (isset($this->updated_at) && $this->updated_at) {
            return $this->updated_at->toW3cString();
        }

        return null;
    }

    /**
     * Get keywords array from tags or meta
     */
    public function getSeoKeywords(): ?array
    {
        // Check for custom keywords in meta
        if (isset($this->meta['keywords']) && !empty($this->meta['keywords'])) {
            return is_array($this->meta['keywords']) 
                ? $this->meta['keywords'] 
                : explode(',', $this->meta['keywords']);
        }

        // Try to get from tags relationship
        if (method_exists($this, 'tags') && $this->relationLoaded('tags')) {
            return $this->tags->pluck('name')->toArray();
        }

        return null;
    }

    /**
     * Get robots meta directive
     */
    public function getSeoRobots(): string
    {
        if (isset($this->meta['robots']) && !empty($this->meta['robots'])) {
            return $this->meta['robots'];
        }

        // Default: allow indexing for published content
        if (isset($this->is_draft) && $this->is_draft) {
            return 'noindex, nofollow';
        }

        if (isset($this->status) && $this->status !== 'published') {
            return 'noindex, nofollow';
        }

        return 'index, follow';
    }

    /**
     * Convert to SeoData object
     */
    public function toSeoData(): SeoData
    {
        return new SeoData(
            title: $this->getSeoTitle(),
            description: $this->getSeoDescription(),
            canonicalUrl: $this->getSeoCanonicalUrl(),
            image: $this->getSeoImage(),
            type: $this->getSeoType(),
            author: $this->getSeoAuthor(),
            publishedAt: $this->getSeoPublishedAt(),
            modifiedAt: $this->getSeoModifiedAt(),
            keywords: $this->getSeoKeywords(),
            robots: $this->getSeoRobots(),
        );
    }

    /**
     * Normalize image URL to absolute path with HTTPS
     */
    protected function normalizeImageUrl(string $path, string $siteDomain): string
    {
        // Already absolute URL
        if (str_starts_with($path, 'http')) {
            return str_replace('http://', 'https://', $path);
        }

        // Clean the path
        $cleanPath = ltrim($path, '/');
        
        // Add storage prefix if not present
        if (!str_starts_with($cleanPath, 'storage/')) {
            $cleanPath = 'storage/' . $cleanPath;
        }

        return rtrim($siteDomain, '/') . '/' . $cleanPath;
    }
}

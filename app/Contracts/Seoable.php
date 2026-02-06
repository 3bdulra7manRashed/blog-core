<?php

namespace App\Contracts;

use App\Support\SEO\SeoData;

/**
 * Interface for models that support SEO metadata
 * 
 * Implement this interface on any model that should
 * provide SEO data (posts, pages, media, etc.)
 */
interface Seoable
{
    /**
     * Get the SEO title for this model
     */
    public function getSeoTitle(): string;

    /**
     * Get the SEO description for this model
     */
    public function getSeoDescription(): string;

    /**
     * Get the canonical URL for this model
     */
    public function getSeoCanonicalUrl(): string;

    /**
     * Get the SEO image URL for this model
     */
    public function getSeoImage(): ?string;

    /**
     * Get the content type for OpenGraph/Schema
     * Examples: 'article', 'video', 'audio', 'website'
     */
    public function getSeoType(): string;

    /**
     * Get full SEO data object
     */
    public function toSeoData(): SeoData;
}

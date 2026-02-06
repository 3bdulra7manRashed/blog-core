<?php

namespace App\Support\SEO;

/**
 * SEO Data Transfer Object
 * 
 * Holds all SEO-related data for a page/model.
 * Immutable after construction for safety.
 */
class SeoData
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $canonicalUrl,
        public readonly ?string $image = null,
        public readonly string $type = 'website',
        public readonly ?string $author = null,
        public readonly ?string $publishedAt = null,
        public readonly ?string $modifiedAt = null,
        public readonly ?array $keywords = null,
        public readonly string $robots = 'index, follow',
        public readonly ?string $duration = null,
        public readonly ?string $thumbnailUrl = null,
    ) {}

    /**
     * Create from array data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'] ?? config('branding.site_name'),
            description: $data['description'] ?? config('branding.site_description'),
            canonicalUrl: $data['canonicalUrl'] ?? url()->current(),
            image: $data['image'] ?? null,
            type: $data['type'] ?? 'website',
            author: $data['author'] ?? null,
            publishedAt: $data['publishedAt'] ?? null,
            modifiedAt: $data['modifiedAt'] ?? null,
            keywords: $data['keywords'] ?? null,
            robots: $data['robots'] ?? 'index, follow',
            duration: $data['duration'] ?? null,
            thumbnailUrl: $data['thumbnailUrl'] ?? null,
        );
    }

    /**
     * Convert to array for easy manipulation
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'canonicalUrl' => $this->canonicalUrl,
            'image' => $this->image,
            'type' => $this->type,
            'author' => $this->author,
            'publishedAt' => $this->publishedAt,
            'modifiedAt' => $this->modifiedAt,
            'keywords' => $this->keywords,
            'robots' => $this->robots,
            'duration' => $this->duration,
            'thumbnailUrl' => $this->thumbnailUrl,
        ];
    }
}

<?php

namespace App\Support\SEO;

use App\Contracts\Seoable;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

/**
 * Centralized SEO Manager
 * 
 * Single source of truth for all SEO operations.
 * Respects feature flags for basic vs advanced SEO.
 * Reusable across Posts, Media, Pages, and static content.
 * 
 * Usage:
 *   Blade: {!! app(SeoManager::class)->render() !!}
 *   Controller: app(SeoManager::class)->forModel($post)
 */
class SeoManager
{
    private ?SeoData $data = null;
    private ?Seoable $model = null;

    /**
     * Set SEO data from a Seoable model
     */
    public function forModel(Seoable $model): self
    {
        $this->model = $model;
        $this->data = $model->toSeoData();
        return $this;
    }

    /**
     * Set SEO data from array (for non-model pages)
     */
    public function forPage(array $data): self
    {
        $this->model = null;
        $this->data = SeoData::fromArray($data);
        return $this;
    }

    /**
     * Set raw SeoData object
     */
    public function setData(SeoData $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get current SEO data
     */
    public function getData(): ?SeoData
    {
        return $this->data;
    }

    /**
     * Get current model
     */
    public function getModel(): ?Seoable
    {
        return $this->model;
    }

    /**
     * Check if basic SEO is enabled
     */
    public function isBasicSeoEnabled(): bool
    {
        return feature('seo', true);
    }

    /**
     * Check if advanced SEO is enabled
     */
    public function isAdvancedSeoEnabled(): bool
    {
        return feature('advanced_seo', false);
    }

    /**
     * Generate full page title with site name suffix
     */
    public function getTitle(): string
    {
        if (!$this->data) {
            return config('branding.site_name') . ' | ' . config('branding.tagline');
        }

        return $this->data->title . ' | ' . config('branding.site_name');
    }

    /**
     * Generate meta description
     */
    public function getDescription(): string
    {
        return $this->data?->description ?? config('branding.site_description');
    }

    /**
     * Get canonical URL
     */
    public function getCanonicalUrl(): string
    {
        return $this->data?->canonicalUrl ?? url()->current();
    }

    /**
     * Get image URL for social sharing
     */
    public function getImage(): string
    {
        if ($this->data?->image) {
            return $this->data->image;
        }

        return config('branding.site_domain') . '/' . config('branding.default_og_image');
    }

    /**
     * Get image MIME type
     */
    public function getImageMimeType(): string
    {
        $image = $this->getImage();
        $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        return match ($ext) {
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
            default => 'image/jpeg',
        };
    }

    /**
     * Get content type for OpenGraph
     */
    public function getType(): string
    {
        return $this->data?->type ?? 'website';
    }

    /**
     * Get robots meta directive
     */
    public function getRobots(): string
    {
        return $this->data?->robots ?? 'index, follow';
    }

    /**
     * Get keywords as comma-separated string
     */
    public function getKeywords(): string
    {
        if ($this->data?->keywords) {
            return implode(', ', $this->data->keywords);
        }

        return config('branding.default_keywords', '');
    }

    /**
     * Render all SEO meta tags
     * Automatically includes advanced SEO if feature is enabled
     */
    public function render(): HtmlString
    {
        if (!$this->isBasicSeoEnabled()) {
            return new HtmlString('');
        }

        $html = $this->renderBasicSeo();

        if ($this->isAdvancedSeoEnabled()) {
            $html .= $this->renderAdvancedSeo();
        }

        return new HtmlString($html);
    }

    /**
     * Render basic SEO tags only
     */
    public function renderBasicSeo(): string
    {
        $title = e($this->getTitle());
        $description = e($this->getDescription());
        $canonicalUrl = e($this->getCanonicalUrl());
        $keywords = e($this->getKeywords());
        $author = e(config('branding.author.name'));

        return <<<HTML
    <!-- Basic SEO -->
    <meta name="title" content="{$title}">
    <meta name="description" content="{$description}">
    <meta name="keywords" content="{$keywords}">
    <meta name="author" content="{$author}">
    <link rel="canonical" href="{$canonicalUrl}">

HTML;
    }

    /**
     * Render advanced SEO tags (OpenGraph, Twitter, etc.)
     * Only called when advanced_seo feature is enabled
     */
    public function renderAdvancedSeo(): string
    {
        $siteDomain = config('branding.site_domain');
        $currentUrl = e($this->getCanonicalUrl());
        $title = e($this->data?->title ?? config('branding.site_name'));
        $fullTitle = e($this->getTitle());
        $description = e($this->getDescription());
        $image = e($this->getImage());
        $imageMimeType = e($this->getImageMimeType());
        $ogType = e($this->getOgType());
        $siteName = e(config('branding.site_name'));
        $twitterHandle = e(config('branding.social.twitter_handle'));
        $robots = e($this->getRobots());

        $html = <<<HTML
    <!-- Advanced SEO: OpenGraph -->
    <meta property="og:type" content="{$ogType}">
    <meta property="og:url" content="{$currentUrl}">
    <meta property="og:title" content="{$title}">
    <meta property="og:description" content="{$description}">
    <meta property="og:image" content="{$image}">
    <meta property="og:image:secure_url" content="{$image}">
    <meta property="og:image:type" content="{$imageMimeType}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{$title}">
    <meta property="og:site_name" content="{$siteName}">
    <meta property="og:locale" content="ar_SA">

    <!-- Advanced SEO: Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="{$twitterHandle}">
    <meta name="twitter:creator" content="{$twitterHandle}">
    <meta name="twitter:url" content="{$currentUrl}">
    <meta name="twitter:title" content="{$fullTitle}">
    <meta name="twitter:description" content="{$description}">
    <meta name="twitter:image" content="{$image}">
    <meta name="twitter:image:alt" content="{$title}">

    <!-- Advanced SEO: Robots -->
    <meta name="robots" content="{$robots}">

HTML;

        // Add article-specific meta if type is article
        if ($this->getType() === 'article' && $this->data) {
            $html .= $this->renderArticleMeta();
        }

        // Add structured data
        $html .= $this->renderStructuredData();

        return $html;
    }

    /**
     * Get OpenGraph type string
     */
    protected function getOgType(): string
    {
        return match ($this->getType()) {
            'video' => 'video.other',
            'audio' => 'music.song',
            'article' => 'article',
            default => 'website',
        };
    }

    /**
     * Render article-specific OpenGraph meta
     */
    protected function renderArticleMeta(): string
    {
        $html = '';
        
        if ($this->data?->publishedAt) {
            $published = e($this->data->publishedAt);
            $html .= "    <meta property=\"article:published_time\" content=\"{$published}\">\n";
        }

        if ($this->data?->modifiedAt) {
            $modified = e($this->data->modifiedAt);
            $html .= "    <meta property=\"article:modified_time\" content=\"{$modified}\">\n";
        }

        if ($this->data?->author) {
            $author = e($this->data->author);
            $html .= "    <meta property=\"article:author\" content=\"{$author}\">\n";
        }

        return $html;
    }

    /**
     * Render JSON-LD structured data
     */
    public function renderStructuredData(): string
    {
        if (!$this->isAdvancedSeoEnabled()) {
            return '';
        }

        $type = $this->getType();

        $schema = match ($type) {
            'article' => $this->buildArticleSchema(),
            'video' => $this->buildVideoSchema(),
            'audio' => $this->buildAudioSchema(),
            default => null,
        };

        if (!$schema) {
            return '';
        }

        $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<HTML
    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
{$json}
    </script>

HTML;
    }

    /**
     * Build Article schema for posts
     */
    protected function buildArticleSchema(): array
    {
        $siteDomain = config('branding.site_domain');
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $this->data?->title ?? '',
            'description' => $this->data?->description ?? '',
            'image' => $this->getImage(),
            'url' => $this->getCanonicalUrl(),
            'datePublished' => $this->data?->publishedAt,
            'dateModified' => $this->data?->modifiedAt ?? $this->data?->publishedAt,
            'author' => [
                '@type' => 'Person',
                '@id' => $siteDomain . '/#person',
                'name' => $this->data?->author ?? config('branding.author.name'),
            ],
            'publisher' => [
                '@type' => 'Person',
                '@id' => $siteDomain . '/#person',
                'name' => config('branding.author.name'),
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $this->getCanonicalUrl(),
            ],
            'inLanguage' => 'ar',
        ];
    }

    /**
     * Build VideoObject schema for videos
     */
    protected function buildVideoSchema(): array
    {
        $siteDomain = config('branding.site_domain');
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'VideoObject',
            'name' => $this->data?->title ?? '',
            'description' => $this->data?->description ?? '',
            'thumbnailUrl' => $this->data?->thumbnailUrl ?? $this->getImage(),
            'uploadDate' => $this->data?->publishedAt,
            'url' => $this->getCanonicalUrl(),
            'author' => [
                '@type' => 'Person',
                '@id' => $siteDomain . '/#person',
                'name' => $this->data?->author ?? config('branding.author.name'),
            ],
            'inLanguage' => 'ar',
        ];

        // Add duration if available (ISO 8601 format: PT1H30M)
        if ($this->data?->duration) {
            $schema['duration'] = $this->data->duration;
        }

        return $schema;
    }

    /**
     * Build AudioObject schema for audio
     */
    protected function buildAudioSchema(): array
    {
        $siteDomain = config('branding.site_domain');
        
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'AudioObject',
            'name' => $this->data?->title ?? '',
            'description' => $this->data?->description ?? '',
            'thumbnailUrl' => $this->data?->thumbnailUrl ?? $this->getImage(),
            'uploadDate' => $this->data?->publishedAt,
            'url' => $this->getCanonicalUrl(),
            'author' => [
                '@type' => 'Person',
                '@id' => $siteDomain . '/#person',
                'name' => $this->data?->author ?? config('branding.author.name'),
            ],
            'inLanguage' => 'ar',
        ];

        // Add duration if available
        if ($this->data?->duration) {
            $schema['duration'] = $this->data->duration;
        }

        return $schema;
    }

    /**
     * Reset the manager state
     */
    public function reset(): self
    {
        $this->data = null;
        $this->model = null;
        return $this;
    }
}

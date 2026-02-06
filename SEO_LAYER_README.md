# SEO Layer Documentation

## Overview

This document describes the professional SEO layer implementation for the Blog Core system. The SEO layer is modular, feature-flag aware, and integrates seamlessly with Posts, Media (VOD), and Pages.

## Architecture

```
app/
├── Contracts/
│   └── Seoable.php                 # Interface for SEO-enabled models
├── Providers/
│   └── SeoServiceProvider.php      # Registers SeoManager singleton
├── Support/
│   └── SEO/
│       ├── SeoManager.php          # Central SEO orchestrator
│       ├── SeoData.php             # Immutable SEO data container
│       └── Traits/
│           └── HasSeo.php          # Default Seoable implementation
├── Helpers/
│   └── seo.php                     # Global helper functions
resources/
├── views/
│   └── components/
│       └── seo.blade.php           # Blade component for SEO
```

## Feature Flags

The SEO system respects the following feature flags in `config/features.php`:

```php
'seo' => true,           // Basic SEO (always enabled core)
'advanced_seo' => false, // Advanced SEO (OpenGraph, Twitter, JSON-LD)
```

### Basic SEO (Always Active)
- Dynamic `<title>` generation
- Meta description
- Canonical URL
- Meta keywords
- Author meta

### Advanced SEO (Feature Controlled)
When `advanced_seo` is enabled:
- OpenGraph tags (Facebook, LinkedIn, WhatsApp)
- Twitter Card tags
- JSON-LD Structured Data
  - Article schema for posts
  - VideoObject schema for videos
  - AudioObject schema for audio
- Meta robots control (index/noindex)
- Article-specific meta (published_time, modified_time)

## Usage

### In Controllers

```php
use App\Support\SEO\SeoManager;

class PostController extends Controller
{
    public function show(string $slug, SeoManager $seoManager)
    {
        $post = Post::with(['author', 'tags'])->where('slug', $slug)->firstOrFail();
        
        // Set SEO data - this generates all meta tags automatically
        $seoManager->forModel($post);
        
        return view('posts.show', compact('post'));
    }
}
```

### Using Helper Functions

```php
// Get SeoManager instance
$seo = seo();

// Quick helper for models
seo_for($post);
```

### In Blade Views

The `layouts.blog` layout automatically renders SEO tags when data is set:

```blade
{{-- SEO tags are automatically rendered in the layout --}}
@if($seoManager->getData())
    {!! $seoManager->render() !!}
@endif
```

### For Static Pages

```php
// In controller
$seoManager->forPage([
    'title' => 'About Us',
    'description' => 'Learn more about our company',
    'image' => asset('images/about-og.jpg'),
    'type' => 'website',
]);
```

## Making Models SEO-Ready

### 1. Implement Interface and Use Trait

```php
use App\Contracts\Seoable;
use App\Support\SEO\Traits\HasSeo;

class Post extends Model implements Seoable
{
    use HasSeo;
    
    // Override for custom behavior
    public function getSeoCanonicalUrl(): string
    {
        return route('post.show', $this->slug);
    }
    
    public function getSeoType(): string
    {
        return 'article';
    }
}
```

### 2. Available Methods to Override

| Method | Description | Default |
|--------|-------------|---------|
| `getSeoTitle()` | Page title | `$model->title` or `$model->name` |
| `getSeoDescription()` | Meta description | `$model->excerpt` or trimmed `$model->content` |
| `getSeoCanonicalUrl()` | Canonical URL | `url()->current()` |
| `getSeoImage()` | OG image URL | `$model->featured_image_path` |
| `getSeoType()` | Content type | `'article'` |
| `getSeoAuthor()` | Author name | `$model->author->name` |
| `getSeoPublishedAt()` | Published date | `$model->published_at` |
| `getSeoModifiedAt()` | Modified date | `$model->updated_at` |
| `getSeoKeywords()` | Keywords array | `$model->tags->pluck('name')` |
| `getSeoRobots()` | Robots directive | `'index, follow'` |

### 3. Custom SEO Fields in Meta

Models with a `meta` JSON column can store custom SEO fields:

```php
$post->meta = [
    'seo_title' => 'Custom SEO Title',
    'seo_description' => 'Custom meta description',
    'seo_image' => 'path/to/custom-og.jpg',
    'canonical_url' => 'https://example.com/custom-url',
    'keywords' => 'keyword1, keyword2',
    'robots' => 'noindex, nofollow', // For drafts or private pages
];
```

## Structured Data (JSON-LD)

When `advanced_seo` is enabled, the following schemas are automatically generated:

### Article Schema (Posts)
```json
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "Post Title",
    "description": "Post excerpt...",
    "image": "https://example.com/storage/image.jpg",
    "datePublished": "2026-02-06T12:00:00+00:00",
    "author": {
        "@type": "Person",
        "name": "Author Name"
    }
}
```

### VideoObject Schema (Videos)
```json
{
    "@context": "https://schema.org",
    "@type": "VideoObject",
    "name": "Video Title",
    "description": "Video description...",
    "thumbnailUrl": "https://example.com/thumbnail.jpg",
    "uploadDate": "2026-02-06T12:00:00+00:00",
    "duration": "PT1H30M"
}
```

### AudioObject Schema (Audio)
```json
{
    "@context": "https://schema.org",
    "@type": "AudioObject",
    "name": "Audio Title",
    "description": "Audio description...",
    "thumbnailUrl": "https://example.com/thumbnail.jpg",
    "uploadDate": "2026-02-06T12:00:00+00:00"
}
```

## Sitemap Integration

When `advanced_seo` is enabled, the sitemap automatically includes:
- All published videos
- All published audio files
- All playlists (video and audio)
- Video library index page
- Audio library index page

## Testing

The SEO layer is fully testable:

```php
public function test_post_generates_correct_seo_data()
{
    $post = Post::factory()->create([
        'title' => 'Test Post',
        'excerpt' => 'Test excerpt',
    ]);
    
    $seoData = $post->toSeoData();
    
    $this->assertEquals('Test Post', $seoData->title);
    $this->assertEquals('Test excerpt', $seoData->description);
    $this->assertEquals('article', $seoData->type);
}
```

## Troubleshooting

### SEO tags not appearing
1. Ensure `config('features.seo')` returns `true`
2. Check that `SeoServiceProvider` is registered in `bootstrap/providers.php`
3. Verify the layout includes `{!! $seoManager->render() !!}`

### Advanced SEO not working
1. Enable `advanced_seo` in `config/features.php`
2. Clear config cache: `php artisan config:clear`

### Images not showing in social previews
1. Ensure images use absolute HTTPS URLs
2. Check image dimensions (recommended: 1200x630 for OG images)
3. Verify image paths are accessible publicly

## Migration Path

The SEO layer is backward compatible with the existing `advanced-seo::head` include. When `SeoManager` has data set, it takes precedence. Otherwise, the legacy include is used.

## Files Changed

- `app/Support/SEO/SeoManager.php` - Created
- `app/Support/SEO/SeoData.php` - Created
- `app/Support/SEO/Traits/HasSeo.php` - Created
- `app/Contracts/Seoable.php` - Created
- `app/Providers/SeoServiceProvider.php` - Created
- `app/Helpers/seo.php` - Created
- `app/Models/Post.php` - Updated (implements Seoable)
- `app/Models/Page.php` - Updated (implements Seoable)
- `Modules/Vod/Models/VodContent.php` - Updated (implements Seoable)
- `Modules/Seo/Http/Controllers/SitemapController.php` - Extended for VOD
- `resources/themes/classic/views/layouts/blog.blade.php` - Updated
- `resources/views/components/seo.blade.php` - Created
- `bootstrap/providers.php` - Updated
- `composer.json` - Updated autoload

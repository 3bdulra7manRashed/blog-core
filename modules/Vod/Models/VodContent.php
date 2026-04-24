<?php

namespace Modules\Vod\Models;

use App\Contracts\Seoable;
use App\Support\SEO\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class VodContent extends Model implements Seoable
{
    use HasFactory, SoftDeletes, HasSeo;

    protected $table = 'vod_contents';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'embed_code',
        'description',
        'thumbnail_path',
        'thumbnail_url',
        'status',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    // -- Relationships --

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function playlists()
    {
        return $this->belongsToMany(VodPlaylist::class, 'vod_playlist_items', 'content_id', 'playlist_id')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    // -- Scopes --

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where('published_at', '<=', now());
    }

    public function scopeVideos($query)
    {
        return $query->where('type', 'video');
    }

    public function scopeAudios($query)
    {
        return $query->where('type', 'audio');
    }

    public function getRouteAttribute()
    {
        return route($this->type === 'video' ? 'videos.show' : 'audios.show', $this->slug);
    }

    // -- Accessors --

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'published' => 'منشور',
            'draft' => 'مسودة',
            'archived' => 'مؤرشف',
            default => $this->status,
        };
    }

    public function getEmbedHtmlAttribute()
    {
        $code = trim($this->embed_code ?? '');
        
        if (empty($code)) {
            return '';
        }

        if (\Illuminate\Support\Str::startsWith($code, '<iframe')) {
            return $code;
        }

        $url = parse_url($code);
        if (!$url || !isset($url['host'])) {
            return $code;
        }

        $host = strtolower($url['host']);

        // YouTube
        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtu.be')) {
            $videoId = '';
            if (str_contains($host, 'youtu.be')) {
                $videoId = trim($url['path'] ?? '', '/');
            } elseif (isset($url['query'])) {
                parse_str($url['query'], $query);
                $videoId = $query['v'] ?? '';
            } elseif (isset($url['path']) && str_starts_with($url['path'], '/embed/')) {
                $videoId = str_replace('/embed/', '', $url['path']);
            }
            
            if ($videoId) {
                return '<iframe src="https://www.youtube.com/embed/'.$videoId.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            }
        }

        // Vimeo
        if (str_contains($host, 'vimeo.com')) {
            $videoId = trim($url['path'] ?? '', '/');
            if (is_numeric($videoId)) {
                return '<iframe src="https://player.vimeo.com/video/'.$videoId.'" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';
            }
        }

        return $code;
    }

    // =========================================================================
    // SEO Interface Implementation
    // =========================================================================

    /**
     * Get canonical URL - routes dynamically based on type (video/audio)
     */
    public function getSeoCanonicalUrl(): string
    {
        return route($this->type === 'video' ? 'videos.show' : 'audios.show', $this->slug);
    }

    /**
     * Get SEO type - video or audio based on content type
     */
    public function getSeoType(): string
    {
        return $this->type === 'video' ? 'video' : 'audio';
    }

    /**
     * Get SEO image URL - uses thumbnail for media
     */
    public function getSeoImage(): ?string
    {
        $siteDomain = config('branding.site_domain');

        if (!empty($this->thumbnail_path)) {
            return $this->normalizeImageUrl($this->thumbnail_path, $siteDomain);
        }

        return $siteDomain . '/' . config('branding.default_og_image');
    }

    /**
     * Get thumbnail URL for structured data
     */
    public function getSeoThumbnailUrl(): ?string
    {
        return $this->getSeoImage();
    }

    /**
     * Get resolved thumbnail URL with fallback chain:
     * 1. Uploaded thumbnail (if exists)
     * 2. External thumbnail URL
     * 3. Default fallback image
     */
    public function getThumbnailResolvedAttribute(): string
    {
        // Priority 1: Uploaded file
        if (!empty($this->thumbnail_path)) {
            if (str_starts_with($this->thumbnail_path, 'http')) {
                return $this->thumbnail_path;
            }
            return asset('storage/' . $this->thumbnail_path);
        }

        // Priority 2: External URL
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        // Priority 3: Default fallback
        return asset(config('branding.default_og_image', 'images/og-default.jpg'));
    }
}

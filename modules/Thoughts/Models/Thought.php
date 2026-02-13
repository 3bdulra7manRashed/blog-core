<?php

namespace Modules\Thoughts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Thought extends Model
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'thoughts';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'image',
        'thumbnail_url',
        'sort_order',
        'is_published',
        'published_at',
        'is_featured',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Scope for published thoughts.
     * Returns thoughts where is_published = true AND published_at <= now()
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for ordered thoughts.
     * Orders by sort_order ASC, then by published_at DESC
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')
            ->orderBy('published_at', 'desc');
    }

    /**
     * Check if the thought is currently published.
     */
    public function isPublished(): bool
    {
        return $this->is_published
            && $this->published_at
            && $this->published_at->isPast();
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Get resolved thumbnail URL with fallback chain:
     * 1. Uploaded image (if exists)
     * 2. External thumbnail URL
     * 3. Default fallback image
     */
    public function getThumbnailResolvedAttribute(): string
    {
        // Priority 1: Uploaded file
        if (!empty($this->image)) {
            return $this->image_url;
        }

        // Priority 2: External URL
        if (!empty($this->thumbnail_url)) {
            return $this->thumbnail_url;
        }

        // Priority 3: Default fallback
        return asset(config('branding.default_og_image', 'images/og-default.jpg'));
    }
}

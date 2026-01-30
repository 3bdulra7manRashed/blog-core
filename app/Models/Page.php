<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
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
}

<?php

namespace Modules\Vod\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class VodContent extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vod_contents';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'embed_code',
        'description',
        'thumbnail_path',
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
        return match($this->status) {
            'published' => 'منشور',
            'draft' => 'مسودة',
            'archived' => 'مؤرشف',
            default => $this->status,
        };
    }
}

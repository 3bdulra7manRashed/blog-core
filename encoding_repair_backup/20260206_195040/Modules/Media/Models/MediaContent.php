<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaContent extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function playlists()
    {
        return $this->belongsToMany(MediaPlaylist::class, 'media_playlist_items')
            ->using(MediaPlaylistItem::class)
            ->withPivot('position')
            ->orderByPivot('position');
    }
}

<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaPlaylist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function contents()
    {
        return $this->belongsToMany(MediaContent::class, 'media_playlist_items')
            ->using(MediaPlaylistItem::class)
            ->withPivot('position')
            ->orderByPivot('position');
    }
}

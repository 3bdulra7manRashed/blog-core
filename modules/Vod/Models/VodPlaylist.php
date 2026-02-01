<?php

namespace Modules\Vod\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class VodPlaylist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vod_playlists';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'slug',
        'description',
    ];

    // -- Relationships --

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->belongsToMany(VodContent::class, 'vod_playlist_items', 'playlist_id', 'content_id')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }
}

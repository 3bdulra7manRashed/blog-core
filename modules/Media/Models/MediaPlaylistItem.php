<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MediaPlaylistItem extends Pivot
{
    protected $table = 'media_playlist_items';
}

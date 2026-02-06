<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vod_playlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('playlist_id')->constrained('vod_playlists')->cascadeOnDelete();
            $table->foreignId('content_id')->constrained('vod_contents')->cascadeOnDelete();
            
            $table->integer('sort_order')->default(0);
            
            $table->unique(['playlist_id', 'content_id']); // Prevent duplicate items in same playlist
        });
    }

    public function down()
    {
        Schema::dropIfExists('vod_playlist_items');
    }
};

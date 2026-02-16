<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('vod_playlists')) {
            return;
        }

        Schema::table('vod_playlists', function (Blueprint $table) {
            if (!Schema::hasColumn('vod_playlists', 'type')) {
                $table->enum('type', ['video', 'audio'])->default('video')->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('vod_playlists')) {
            return;
        }

        Schema::table('vod_playlists', function (Blueprint $table) {
            if (Schema::hasColumn('vod_playlists', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};

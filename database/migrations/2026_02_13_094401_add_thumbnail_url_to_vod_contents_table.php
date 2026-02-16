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
        if (!Schema::hasTable('vod_contents')) {
            return;
        }

        Schema::table('vod_contents', function (Blueprint $table) {
            if (!Schema::hasColumn('vod_contents', 'thumbnail_url')) {
                $table->string('thumbnail_url')->nullable()->after('thumbnail_path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('vod_contents')) {
            return;
        }

        Schema::table('vod_contents', function (Blueprint $table) {
            if (Schema::hasColumn('vod_contents', 'thumbnail_url')) {
                $table->dropColumn('thumbnail_url');
            }
        });
    }
};

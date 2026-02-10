<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('landing_settings', function (Blueprint $table) {
            $table->string('cta_text')->nullable()->after('hero_image');
            $table->string('cta_link')->nullable()->after('cta_text');
            $table->boolean('show_thoughts')->default(true)->after('show_quotes_section');
            $table->boolean('show_category_one')->default(false)->after('show_thoughts');
            $table->unsignedBigInteger('category_one_id')->nullable()->after('show_category_one');
            $table->boolean('show_category_two')->default(false)->after('category_one_id');
            $table->unsignedBigInteger('category_two_id')->nullable()->after('show_category_two');
            $table->boolean('show_khutab')->default(false)->after('category_two_id');
            $table->unsignedBigInteger('khutab_category_id')->nullable()->after('show_khutab');
            $table->boolean('show_releases')->default(false)->after('khutab_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('landing_settings', function (Blueprint $table) {
            $table->dropColumn([
                'cta_text',
                'cta_link',
                'show_thoughts',
                'show_category_one',
                'category_one_id',
                'show_category_two',
                'category_two_id',
                'show_khutab',
                'khutab_category_id',
                'show_releases',
            ]);
        });
    }
};

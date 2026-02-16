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
        if (!Schema::hasTable('landing_settings')) {
            return;
        }

        Schema::table('landing_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('landing_settings', 'cta_text')) {
                $table->string('cta_text')->nullable()->after('hero_image');
            }
            if (!Schema::hasColumn('landing_settings', 'cta_link')) {
                $table->string('cta_link')->nullable()->after('cta_text');
            }
            if (!Schema::hasColumn('landing_settings', 'show_thoughts')) {
                $table->boolean('show_thoughts')->default(true)->after('show_quotes_section');
            }
            if (!Schema::hasColumn('landing_settings', 'show_category_one')) {
                $table->boolean('show_category_one')->default(false)->after('show_thoughts');
            }
            if (!Schema::hasColumn('landing_settings', 'category_one_id')) {
                $table->unsignedBigInteger('category_one_id')->nullable()->after('show_category_one');
            }
            if (!Schema::hasColumn('landing_settings', 'show_category_two')) {
                $table->boolean('show_category_two')->default(false)->after('category_one_id');
            }
            if (!Schema::hasColumn('landing_settings', 'category_two_id')) {
                $table->unsignedBigInteger('category_two_id')->nullable()->after('show_category_two');
            }
            if (!Schema::hasColumn('landing_settings', 'show_khutab')) {
                $table->boolean('show_khutab')->default(false)->after('category_two_id');
            }
            if (!Schema::hasColumn('landing_settings', 'khutab_category_id')) {
                $table->unsignedBigInteger('khutab_category_id')->nullable()->after('show_khutab');
            }
            if (!Schema::hasColumn('landing_settings', 'show_releases')) {
                $table->boolean('show_releases')->default(false)->after('khutab_category_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('landing_settings')) {
            return;
        }

        $columns = [
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
        ];

        Schema::table('landing_settings', function (Blueprint $table) use ($columns) {
            $existing = array_filter($columns, fn($col) => Schema::hasColumn('landing_settings', $col));
            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};

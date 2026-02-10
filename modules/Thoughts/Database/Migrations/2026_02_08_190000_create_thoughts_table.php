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
        Schema::create('thoughts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->string('image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();

            // Indexes for query optimization
            $table->index('is_published');
            $table->index('published_at');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thoughts');
    }
};

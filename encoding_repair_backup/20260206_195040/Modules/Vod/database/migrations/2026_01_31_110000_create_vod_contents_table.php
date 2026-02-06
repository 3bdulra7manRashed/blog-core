<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vod_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->enum('type', ['video', 'audio']);
            $table->string('title')->index();
            $table->string('slug')->unique();
            
            $table->text('embed_code')->nullable(); // Raw iframe or URL
            $table->longText('description')->nullable(); // CKEditor content
            $table->string('thumbnail_path')->nullable();
            
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vod_contents');
    }
};

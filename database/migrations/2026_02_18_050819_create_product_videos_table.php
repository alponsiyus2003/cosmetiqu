<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // creator
            $table->string('video_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('duration')->default(0); // seconds
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'created_at']);
        });

        // Video likes table
        Schema::create('product_video_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_video_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['product_video_id', 'user_id']);
        });

        // Video comments table
        Schema::create('product_video_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_video_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('comment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_video_comments');
        Schema::dropIfExists('product_video_likes');
        Schema::dropIfExists('product_videos');
    }
};

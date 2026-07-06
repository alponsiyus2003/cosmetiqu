<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_video_comment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_video_comment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('reply');
            $table->string('role')->default('pembeli');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_video_comment_replies');
    }
};

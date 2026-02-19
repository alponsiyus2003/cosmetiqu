<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed']); // Percentage atau Fixed amount
            $table->decimal('value', 10, 2); // Nilai diskon
            $table->decimal('min_purchase', 10, 2)->default(0); // Minimal pembelian
            $table->decimal('max_discount', 10, 2)->nullable(); // Maksimal diskon (untuk percentage)
            $table->integer('usage_limit')->nullable(); // Batas penggunaan total
            $table->integer('usage_per_user')->default(1); // Batas penggunaan per user
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

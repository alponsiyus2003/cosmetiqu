<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('name');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('position')->nullable(); // Jabatan/Posisi
            $table->string('department')->nullable(); // Bagian/Departemen
            $table->text('education')->nullable(); // Pendidikan
            $table->text('bio')->nullable(); // Biografi
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('linkedin')->nullable();
            $table->boolean('show_in_about')->default(false); // Tampilkan di halaman About
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name', 'birth_date', 'gender', 'position', 'department',
                'education', 'bio', 'facebook', 'instagram', 'twitter', 'linkedin',
                'show_in_about'
            ]);
        });
    }
};

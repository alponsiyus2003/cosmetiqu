<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Cosmetiqu',
            'email' => 'admin@cosmetiqu.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin No. 1, Jakarta',
            'email_verified_at' => now(),
        ]);

        // Penjual 1
        User::create([
            'name' => 'Toko Kecantikan A',
            'email' => 'penjual1@cosmetiqu.com',
            'password' => Hash::make('password'),
            'role' => 'penjual',
            'phone' => '081234567891',
            'address' => 'Jl. Penjual No. 1, Bandung',
            'email_verified_at' => now(),
        ]);

        // Penjual 2
        User::create([
            'name' => 'Beauty Store B',
            'email' => 'penjual2@cosmetiqu.com',
            'password' => Hash::make('password'),
            'role' => 'penjual',
            'phone' => '081234567892',
            'address' => 'Jl. Penjual No. 2, Surabaya',
            'email_verified_at' => now(),
        ]);

        // Pengguna/Pembeli 1
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'pembeli1@example.com',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'phone' => '081234567893',
            'address' => 'Jl. Pembeli No. 1, Jakarta',
            'email_verified_at' => now(),
        ]);

        // Pengguna/Pembeli 2
        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'pembeli2@example.com',
            'password' => Hash::make('password'),
            'role' => 'pengguna',
            'phone' => '081234567894',
            'address' => 'Jl. Pembeli No. 2, Yogyakarta',
            'email_verified_at' => now(),
        ]);
    }
}

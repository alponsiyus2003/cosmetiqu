<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Cosmetiqu', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'Toko Kosmetik Online Terpercaya', 'type' => 'text'],
            ['key' => 'about_title', 'value' => 'Tentang Kami', 'type' => 'text'],
            ['key' => 'about_description', 'value' => 'Cosmetiqu adalah platform e-commerce yang menyediakan berbagai produk kosmetik berkualitas tinggi dengan harga terjangkau. Kami berkomitmen untuk memberikan pengalaman belanja terbaik bagi semua pelanggan kami.', 'type' => 'textarea'],
            ['key' => 'contact_email', 'value' => 'info@cosmetiqu.com', 'type' => 'text'],
            ['key' => 'contact_phone', 'value' => '081234567890', 'type' => 'text'],
            ['key' => 'contact_address', 'value' => 'Jl. Contoh No. 123, Jakarta, Indonesia', 'type' => 'textarea'],
            ['key' => 'facebook', 'value' => 'https://facebook.com/cosmetiqu', 'type' => 'text'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/cosmetiqu', 'type' => 'text'],
            ['key' => 'twitter', 'value' => 'https://twitter.com/cosmetiqu', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}

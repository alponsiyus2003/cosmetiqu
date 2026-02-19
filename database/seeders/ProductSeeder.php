<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $penjual1 = User::where('email', 'penjual1@cosmetiqu.com')->first();
        $penjual2 = User::where('email', 'penjual2@cosmetiqu.com')->first();

        $products = [
            // Skincare
            [
                'user_id' => $penjual1->id,
                'category_id' => 1,
                'name' => 'Serum Vitamin C Premium',
                'description' => 'Serum wajah dengan kandungan vitamin C 20% untuk mencerahkan kulit',
                'price' => 250000,
                'stock' => 50,
                'brand' => 'Cosmetiqu Beauty',
            ],
            [
                'user_id' => $penjual1->id,
                'category_id' => 1,
                'name' => 'Moisturizer Hyaluronic Acid',
                'description' => 'Pelembab wajah dengan hyaluronic acid untuk kulit lembab seharian',
                'price' => 180000,
                'stock' => 75,
                'brand' => 'Cosmetiqu Beauty',
            ],
            [
                'user_id' => $penjual2->id,
                'category_id' => 1,
                'name' => 'Sunscreen SPF 50 PA++++',
                'description' => 'Tabir surya dengan perlindungan maksimal dari sinar UV',
                'price' => 150000,
                'stock' => 100,
                'brand' => 'Beauty Store',
            ],

            // Makeup
            [
                'user_id' => $penjual2->id,
                'category_id' => 2,
                'name' => 'Cushion Foundation Natural Glow',
                'description' => 'Foundation cushion dengan hasil natural dan tahan lama',
                'price' => 320000,
                'stock' => 40,
                'brand' => 'Beauty Store',
            ],
            [
                'user_id' => $penjual1->id,
                'category_id' => 2,
                'name' => 'Lipstick Matte Long Lasting',
                'description' => 'Lipstik matte dengan 12 pilihan warna cantik',
                'price' => 95000,
                'stock' => 150,
                'brand' => 'Cosmetiqu Beauty',
            ],
            [
                'user_id' => $penjual2->id,
                'category_id' => 2,
                'name' => 'Eyeshadow Palette Nude',
                'description' => 'Palet eyeshadow dengan 16 warna nude untuk tampilan natural',
                'price' => 280000,
                'stock' => 35,
                'brand' => 'Beauty Store',
            ],

            // Haircare
            [
                'user_id' => $penjual1->id,
                'category_id' => 3,
                'name' => 'Shampoo Anti Dandruff',
                'description' => 'Shampo anti ketombe dengan tea tree oil',
                'price' => 85000,
                'stock' => 120,
                'brand' => 'Cosmetiqu Hair',
            ],
            [
                'user_id' => $penjual2->id,
                'category_id' => 3,
                'name' => 'Hair Mask Repair & Shine',
                'description' => 'Masker rambut untuk memperbaiki rambut rusak',
                'price' => 135000,
                'stock' => 60,
                'brand' => 'Beauty Store',
            ],

            // Bodycare
            [
                'user_id' => $penjual1->id,
                'category_id' => 4,
                'name' => 'Body Lotion Whitening',
                'description' => 'Lotion tubuh dengan efek mencerahkan',
                'price' => 95000,
                'stock' => 90,
                'brand' => 'Cosmetiqu Body',
            ],
            [
                'user_id' => $penjual2->id,
                'category_id' => 4,
                'name' => 'Body Scrub Coffee',
                'description' => 'Scrub tubuh dengan ekstrak kopi untuk kulit halus',
                'price' => 75000,
                'stock' => 80,
                'brand' => 'Beauty Store',
            ],

            // Fragrance
            [
                'user_id' => $penjual1->id,
                'category_id' => 5,
                'name' => 'Eau de Parfum Floral',
                'description' => 'Parfum dengan aroma bunga segar, tahan 8-10 jam',
                'price' => 450000,
                'stock' => 25,
                'brand' => 'Cosmetiqu Fragrance',
            ],

            // Tools
            [
                'user_id' => $penjual2->id,
                'category_id' => 6,
                'name' => 'Beauty Blender Set',
                'description' => 'Set spons makeup untuk aplikasi foundation sempurna',
                'price' => 120000,
                'stock' => 70,
                'brand' => 'Beauty Tools',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'user_id' => $product['user_id'],
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'brand' => $product['brand'],
                'is_active' => true,
            ]);
        }
    }
}

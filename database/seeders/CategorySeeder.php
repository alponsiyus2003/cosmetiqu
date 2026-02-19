<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Skincare',
                'description' => 'Produk perawatan kulit wajah dan tubuh',
            ],
            [
                'name' => 'Makeup',
                'description' => 'Produk makeup dan kosmetik warna',
            ],
            [
                'name' => 'Haircare',
                'description' => 'Produk perawatan rambut',
            ],
            [
                'name' => 'Bodycare',
                'description' => 'Produk perawatan tubuh',
            ],
            [
                'name' => 'Fragrance',
                'description' => 'Parfum dan pewangi',
            ],
            [
                'name' => 'Tools & Accessories',
                'description' => 'Alat kecantikan dan aksesoris',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
            ]);
        }
    }
}

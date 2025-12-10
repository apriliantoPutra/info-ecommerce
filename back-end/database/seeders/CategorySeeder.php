<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            [
                'name' => 'Elektronik',
                'description' => 'Kategori untuk semua produk elektronik seperti smartphone, laptop, dan perangkat rumah tangga.'
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Kategori untuk produk-produk olahraga seperti sepatu, pakaian, dan peralatan olahraga.'
            ],
            [
                'name' => 'Kosmetik',
                'description' => 'Kategori untuk produk kecantikan dan perawatan diri seperti makeup, skincare, dan parfum.'
            ],
        ]);
    }
}

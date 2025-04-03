<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Food & Beverages', 'description' => 'All types of food and drinks'],
            ['name' => 'Household Essentials', 'description' => 'Daily use household items'],
            ['name' => 'Personal Care & Hygiene', 'description' => 'Products for personal hygiene'],
            ['name' => 'Health & Wellness', 'description' => 'Medicines, supplements, and wellness items'],
            ['name' => 'Electronics & Accessories', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Clothing & Apparel', 'description' => 'Fashion and apparel for all'],
            ['name' => 'Stationery & Office Supplies', 'description' => 'School and office stationery'],
            ['name' => 'Pet Supplies', 'description' => 'Food and accessories for pets'],
            ['name' => 'Automotive & Hardware', 'description' => 'Car accessories and hardware tools'],
            ['name' => 'Seasonal & Special Items', 'description' => 'Festive and special occasion items'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                // 'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

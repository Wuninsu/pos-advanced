<?php

namespace Database\Seeders;

use App\Models\ProductsModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Shito (Hot Pepper Sauce)', 'category_id' => 1, 'price' => 25.00, 'stock' => 300, 'description' => 'Authentic Ghanaian spicy black pepper sauce.', 'sku' => 'SHT001'],
            ['name' => 'Kente Cloth', 'category_id' => 2, 'price' => 200.00, 'stock' => 10, 'description' => 'Handwoven traditional Ghanaian fabric.', 'sku' => 'KNT002'],
            ['name' => 'Fufu Powder', 'category_id' => 1, 'price' => 30.00, 'stock' => 30, 'description' => 'Cassava and plantain flour for fufu preparation.', 'sku' => 'FUF003'],
            ['name' => 'Gari (Cassava Flakes)', 'category_id' => 1, 'price' => 15.00, 'stock' => 140, 'description' => 'Crunchy and nutritious cassava flakes.', 'sku' => 'GRI004'],
            ['name' => 'Shea Butter', 'category_id' => 3, 'price' => 50.00, 'stock' => 40, 'description' => '100% pure organic shea butter from Northern Ghana.', 'sku' => 'SHE005'],
            ['name' => 'Kelewele Spice Mix', 'category_id' => 1, 'price' => 20.00, 'stock' => 200, 'description' => 'Special seasoning mix for Ghanaian spicy fried plantains.', 'sku' => 'KEL006'],
            ['name' => 'Banku Mix', 'category_id' => 1, 'price' => 35.00, 'stock' => 25, 'description' => 'Pre-mixed maize and cassava dough for Banku.', 'sku' => 'BNK007'],
            ['name' => 'Tilapia Fish', 'category_id' => 1, 'price' => 60.00, 'stock' => 20, 'description' => 'Fresh Ghanaian grilled tilapia.', 'sku' => 'TLP008'],
            ['name' => 'Ghanaian Smock (Batakari)', 'category_id' => 2, 'price' => 180.00, 'stock' => 12, 'description' => 'Handwoven Northern Ghanaian traditional attire.', 'sku' => 'SMK009'],
            ['name' => 'Bofrot (Puff Puff)', 'category_id' => 1, 'price' => 10.00, 'stock' => 50, 'description' => 'Soft and delicious Ghanaian fried dough balls.', 'sku' => 'BOF010'],
            ['name' => 'Cocoa Powder', 'category_id' => 1, 'price' => 45.00, 'stock' => 60, 'description' => 'Pure Ghanaian cocoa powder for hot chocolate.', 'sku' => 'COC011'],
            ['name' => 'Ghanaian Waist Beads', 'category_id' => 2, 'price' => 25.00, 'stock' => 70, 'description' => 'Traditional handcrafted waist beads.', 'sku' => 'WBD012'],
            ['name' => 'Dawadawa Spice', 'category_id' => 1, 'price' => 15.00, 'stock' => 100, 'description' => 'Natural seasoning made from locust beans.', 'sku' => 'DAW013'],
            ['name' => 'Coconut Oil', 'category_id' => 3, 'price' => 55.00, 'stock' => 30, 'description' => 'Cold-pressed organic coconut oil.', 'sku' => 'CNO014'],
            ['name' => 'Chalewote (Ghanaian Sandals)', 'category_id' => 2, 'price' => 70.00, 'stock' => 20, 'description' => 'Locally made stylish sandals.', 'sku' => 'CHA015'],
            ['name' => 'Ghanaian Beaded Necklace', 'category_id' => 2, 'price' => 35.00, 'stock' => 150, 'description' => 'Handmade Ghanaian bead jewelry.', 'sku' => 'NCK016'],
            ['name' => 'Ghanaian Wooden Mask', 'category_id' => 4, 'price' => 120.00, 'stock' => 10, 'description' => 'Traditional wooden carved mask.', 'sku' => 'MSK017'],
            ['name' => 'Groundnut Paste', 'category_id' => 1, 'price' => 25.00, 'stock' => 50, 'description' => 'Natural peanut butter from Ghana.', 'sku' => 'GNP018'],
            ['name' => 'Sobolo (Hibiscus Drink)', 'category_id' => 1, 'price' => 12.00, 'stock' => 0, 'description' => 'Refreshing Ghanaian hibiscus juice.', 'sku' => 'SOB019'],
            ['name' => 'Kenkey', 'category_id' => 1, 'price' => 8.00, 'stock' => 140, 'description' => 'Traditional Ghanaian cornmeal dumplings.', 'sku' => 'KEN020'],
        ];

        foreach ($products as &$product) {
            $product['uuid'] = Str::uuid()->toString();
            $product['user_id'] = rand(1, 3);
            $product['supplier_id'] = rand(1, 3);
            $product['barcode'] = Str::random(12);
            $product['status'] = 1;
            $product['img'] = null;
            $product['created_at'] = now();
            $product['updated_at'] = now();
        }

        DB::table('products')->insert($products);
    }
}

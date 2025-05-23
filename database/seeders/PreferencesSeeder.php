<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $preferences = [
            // ['key' => 'enable_notifications', 'value' => true],
            // ['key' => 'auto_print_receipts', 'value' => false],
            // ['key' => 'allow_discounts', 'value' => true],
            // ['key' => 'show_profit_on_sales', 'value' => false],
            // ['key' => 'enable_dark_mode', 'value' => false],
            ['key' => 'allow_rep_delete_data', 'value' => false],
            ['key' => 'allow_rep_delete_categories', 'value' => false],
            ['key' => 'allow_rep_delete_customers', 'value' => false],
            ['key' => 'allow_rep_delete_products', 'value' => false],
            ['key' => 'allow_rep_delete_orders', 'value' => false],
            ['key' => 'allow_rep_delete_invoices', 'value' => false],
            ['key' => 'allow_rep_delete_units', 'value' => false],
            ['key' => 'allow_rep_delete_services', 'value' => false],
            ['key' => 'enable_cart_sound', 'value' => true],
        ];

        foreach ($preferences as $preference) {
            DB::table('preferences')->updateOrInsert(
                ['key' => $preference['key']],
                ['value' => $preference['value']]
            );
        }
    }
}

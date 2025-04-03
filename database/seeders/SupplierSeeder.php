<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('suppliers')->insert([
            [
                'company_name' => 'Fast Delivery Ghana',
                'email' => 'info@fastdeliverygh.com',
                'contact_person' => 'Mr. Dasoli',
                'phone' => '0200011425',
                'status' => 1,
                'address' => 'Address 1, Tamale Ghana',
                'user_id' => 1,
                'website' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Dasumu Group',
                'email' => 'sales@dasuma.com',
                'contact_person' => 'Sale Manager',
                'phone' => '0200011423',
                'status' => 1,
                'address' => 'Address 2, Yendi Ghana',
                'user_id' => 1,
                'website' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'company_name' => 'Arkasel Ghana',
                'email' => 'contact@arkaselgh.com',
                'contact_person' => 'Senior  Developer',
                'phone' => '0200011420',
                'status' => 1,
                'address' => 'Address 1, Accra Ghana',
                'user_id' => 1,
                'website' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

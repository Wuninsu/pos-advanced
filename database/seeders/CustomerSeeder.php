<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            [
                'name' => 'Issah Abdul-Fatawu',
                'email' => 'issah@gmail.com',
                'phone' => '0200041225',
                'address' => 'Sakasaka',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alhassan Kuburatu',
                'email' => 'kuburatu@gmail.com',
                'phone' => '0554234700',
                'address' => 'Nyankpala',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adam Fatima',
                'email' => 'Fatima@gmail.com',
                'phone' => '0599678749',
                'address' => 'Kpalsi Nayili',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Abdul-Hafiz',
                'email' => 'hafiz@gmail.com',
                'phone' => '0554234792',
                'address' => 'Tuunayli',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adam Mohammed',
                'email' => 'adma@gmail.com',
                'phone' => '0599678749',
                'address' => 'Tolon',
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

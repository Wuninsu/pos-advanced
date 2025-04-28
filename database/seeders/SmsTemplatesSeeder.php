<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SmsTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sms_templates')->insert([
            [
                'name' => 'payment_reminder',
                'template' => 'Dear {customer_name}, you have an outstanding balance of {balance} on order {order_number}. Please pay by {due_date}. Thank you!',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'thank_you_note',
                'template' => 'Thank you, {customer_name}, for your recent purchase! We appreciate your business and look forward to serving you again.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'promotions',
                'template' => 'Hey {customer_name}! Enjoy our latest offer: {promotion_details}. Valid till {end_date}. Shop now and save big!',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}

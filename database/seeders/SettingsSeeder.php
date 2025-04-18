<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'logo', 'value' => null],
            ['key' => 'favicon', 'value' => null],
            ['key' => 'business_name', 'value' => 'Echo POS'],
            ['key' => 'url', 'value' => 'https://www.myapplication.com'],
            ['key' => 'email', 'value' => 'info@myapplication.com'],
            ['key' => 'address', 'value' => '123 Main Street, Tamale, Ghana'],
            ['key' => 'phone', 'value' => '1234567890'],
            ['key' => 'phone2', 'value' => '0987654321'],
            ['key' => 'motto', 'value' => 'We Help your Business Strived'],
            ['key' => 'note', 'value' => 'This system is designed to streamline and automate key administrative and academic processes within a school'],
            ['key' => 'paystack_secret_key', 'value' => 'sk_test_xxxxxxxxxxxxxxxxxxxxxx'],
            ['key' => 'currency', 'value' => 'Ghs'],
            ['key' => 'low_stock', 'value' => '100'],
            ['key' => 'timezone', 'value' => 'Africa/Accra'],
            ['key' => 'date_format', 'value' => 'Y-m-d'],
            ['key' => 'time_format', 'value' => 'H:i:s'],
            ['key' => 'default_language', 'value' => 'en'],
            ['key' => 'footer_text', 'value' => '© 2025 My Application. All rights reserved.'],
            ['key' => 'maintenance_mode', 'value' => 'false'],
            ['key' => 'contact_form_email', 'value' => 'support@myapplication.com'],
            ['key' => 'sms_api_key', 'value' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'],
            ['key' => 'google_analytics_id', 'value' => 'UA-XXXXXXXXX-X'],
            ['key' => 'facebook_pixel_id', 'value' => 'XXXXXXXXXXXXXX'],
            ['key' => 'default_avatar', 'value' => 'default-avatar.png'],
            ['key' => 'pagination_limit', 'value' => '10'],
            ['key' => 'max_upload_size', 'value' => '2048'],
            ['key' => 'enable_registration', 'value' => 'true'],
            ['key' => 'support_email', 'value' => 'support@myapplication.com'],
            ['key' => 'support_phone', 'value' => '+1234567890'],
            ['key' => 'recaptcha_site_key', 'value' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'],
            ['key' => 'recaptcha_secret_key', 'value' => 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxx'],
            ['key' => 'bank_name', 'value' => 'Consolidated Bank Ghana'],
            ['key' => 'bank_account_name', 'value' => 'Issah Mohammed Adams'],
            ['key' => 'bank_account_number', 'value' => '098877665430'],
            ['key' => 'payment_terms', 'value' => '70% payment is required upon invoice confirmation, with the remaining 30% due upon delivery to the consignee at the specified address.'],

        ];
        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SettingsSeeder::class,
            // CategorySeeder::class,
            PreferencesSeeder::class,
            // CustomerSeeder::class,
            // SupplierSeeder::class,
            UnitSeeder::class,
            // ProductSeeder::class,
            // ServicesSeeder::class,
            SmsTemplatesSeeder::class
        ]);
    }
}

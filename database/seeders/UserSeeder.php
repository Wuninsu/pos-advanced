<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'email' => 'admin@mail.com',
                'role' => 'admin',
                'password' => Hash::make('test1234'),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'uuid' => Str::uuid()->toString(),
            ],
            // [
            //     'username' => 'cahier',
            //     'email' => 'cahier@mail.com',
            //     'role' => 'cashier',
            //     'password' => Hash::make('test1234'),
            //     'status' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'uuid' => Str::uuid()->toString(),
            // ],
            // [
            //     'username' => 'cashier2',
            //     'email' => 'cashier2@mail.com',
            //     'role' => 'cashier',
            //     'password' => Hash::make('test1234'),
            //     'status' => 1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            //     'uuid' => Str::uuid()->toString(),
            // ]
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Combine Harvester',
                'description' => 'A service for harvesting crops efficiently using combine harvesters.'
            ],
            [
                'name' => 'Tractor',
                'description' => 'Tractor services for land preparation, plowing, and other farm activities.'
            ],
            [
                'name' => 'Transport and logistics',
                'description' => 'Reliable transportation and logistics solutions for agricultural and general goods.'
            ],
        ];

        DB::table('services')->insert($services);
    }
}

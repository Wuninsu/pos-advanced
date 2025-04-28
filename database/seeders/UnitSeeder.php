<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $units = [
            // 🛒 Retail Units
            ['name' => 'Piece', 'abbreviation' => 'pc'],
            ['name' => 'Dozen', 'abbreviation' => 'dz'],
            ['name' => 'Pack', 'abbreviation' => 'pk'],
            ['name' => 'Box', 'abbreviation' => 'box'],
            ['name' => 'Carton', 'abbreviation' => 'ctn'],
            ['name' => 'Pair', 'abbreviation' => 'pr'],
            ['name' => 'Set', 'abbreviation' => 'set'],
            ['name' => 'Roll', 'abbreviation' => 'roll'],
            ['name' => 'Strip', 'abbreviation' => 'strip'],
            ['name' => 'Sheet', 'abbreviation' => 'sheet'],
            ['name' => 'Bottle', 'abbreviation' => 'btl'],
            ['name' => 'Bag', 'abbreviation' => 'bag'],
            ['name' => 'Tube', 'abbreviation' => 'tube'],
            ['name' => 'Sachet', 'abbreviation' => 'sachet'],
            ['name' => 'Can', 'abbreviation' => 'can'],

            // ⚖️ Weight
            ['name' => 'Milligram', 'abbreviation' => 'mg'],
            ['name' => 'Centigram', 'abbreviation' => 'cg'],
            ['name' => 'Decigram', 'abbreviation' => 'dg'],
            ['name' => 'Gram', 'abbreviation' => 'g'],
            ['name' => 'Dekagram', 'abbreviation' => 'dag'],
            ['name' => 'Hectogram', 'abbreviation' => 'hg'],
            ['name' => 'Kilogram', 'abbreviation' => 'kg'],
            ['name' => 'Metric Ton', 'abbreviation' => 't'],
            ['name' => 'Pound', 'abbreviation' => 'lb'],
            ['name' => 'Ounce', 'abbreviation' => 'oz'],

            // 💧 Volume
            ['name' => 'Milliliter', 'abbreviation' => 'ml'],
            ['name' => 'Centiliter', 'abbreviation' => 'cl'],
            ['name' => 'Deciliter', 'abbreviation' => 'dl'],
            ['name' => 'Liter', 'abbreviation' => 'l'],
            ['name' => 'Cubic Centimeter', 'abbreviation' => 'cm³'],
            ['name' => 'Cubic Meter', 'abbreviation' => 'm³'],
            ['name' => 'Fluid Ounce', 'abbreviation' => 'fl oz'],
            ['name' => 'Pint', 'abbreviation' => 'pt'],
            ['name' => 'Quart', 'abbreviation' => 'qt'],
            ['name' => 'Gallon', 'abbreviation' => 'gal'],

            // 📏 Length
            ['name' => 'Millimeter', 'abbreviation' => 'mm'],
            ['name' => 'Centimeter', 'abbreviation' => 'cm'],
            ['name' => 'Decimeter', 'abbreviation' => 'dm'],
            ['name' => 'Meter', 'abbreviation' => 'm'],
            ['name' => 'Kilometer', 'abbreviation' => 'km'],
            ['name' => 'Inch', 'abbreviation' => 'in'],
            ['name' => 'Foot', 'abbreviation' => 'ft'],
            ['name' => 'Yard', 'abbreviation' => 'yd'],
            ['name' => 'Mile', 'abbreviation' => 'mi'],

            // ⏱️ Time (if needed for usage-based products)
            ['name' => 'Second', 'abbreviation' => 's'],
            ['name' => 'Minute', 'abbreviation' => 'min'],
            ['name' => 'Hour', 'abbreviation' => 'h'],
            ['name' => 'Day', 'abbreviation' => 'd'],

            // 🔢 Miscellaneous
            ['name' => 'Bundle', 'abbreviation' => 'bndl'],
            ['name' => 'Unit', 'abbreviation' => 'unit'],
            ['name' => 'Litre per Hour', 'abbreviation' => 'l/h'],
            ['name' => 'Kilowatt Hour', 'abbreviation' => 'kWh'],
        ];

        foreach ($units as &$unit) {
            $unit['created_at'] = $now;
            $unit['updated_at'] = $now;
        }

        DB::table('units')->insert($units);
    }
}

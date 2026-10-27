<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeasurementUnitsSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            // Quantity & Packaging
            ['code' => 'piece', 'name' => 'Piece', 'symbol' => 'pc', 'unit_type' => 'quantity', 'decimal_places' => 0],
            ['code' => 'box', 'name' => 'Box', 'symbol' => 'box', 'unit_type' => 'quantity', 'decimal_places' => 0],
            ['code' => 'package', 'name' => 'Package', 'symbol' => 'pkg', 'unit_type' => 'quantity', 'decimal_places' => 0],
            ['code' => 'bulk', 'name' => 'Bulk / Sack', 'symbol' => 'blk', 'unit_type' => 'quantity', 'decimal_places' => 2],
            ['code' => 'dozen', 'name' => 'Dozen', 'symbol' => 'dz', 'unit_type' => 'quantity', 'decimal_places' => 0],
            ['code' => 'roll', 'name' => 'Roll', 'symbol' => 'roll', 'unit_type' => 'quantity', 'decimal_places' => 2],
            
            // Weight
            ['code' => 'kilogram', 'name' => 'Kilogram', 'symbol' => 'kg', 'unit_type' => 'weight', 'decimal_places' => 4],
            ['code' => 'gram', 'name' => 'Gram', 'symbol' => 'g', 'unit_type' => 'weight', 'decimal_places' => 2],
            ['code' => 'milligram', 'name' => 'Milligram', 'symbol' => 'mg', 'unit_type' => 'weight', 'decimal_places' => 2],
            ['code' => 'pound', 'name' => 'Pound', 'symbol' => 'lb', 'unit_type' => 'weight', 'decimal_places' => 4],
            ['code' => 'ounce', 'name' => 'Ounce', 'symbol' => 'oz', 'unit_type' => 'weight', 'decimal_places' => 2],
            
            // Volume
            ['code' => 'liter', 'name' => 'Liter', 'symbol' => 'L', 'unit_type' => 'volume', 'decimal_places' => 4],
            ['code' => 'milliliter', 'name' => 'Milliliter', 'symbol' => 'mL', 'unit_type' => 'volume', 'decimal_places' => 2],
            ['code' => 'gallon', 'name' => 'Gallon', 'symbol' => 'gal', 'unit_type' => 'volume', 'decimal_places' => 4],
            
            // Length
            ['code' => 'meter', 'name' => 'Meter', 'symbol' => 'm', 'unit_type' => 'length', 'decimal_places' => 2],
            ['code' => 'centimeter', 'name' => 'Centimeter', 'symbol' => 'cm', 'unit_type' => 'length', 'decimal_places' => 2],
            ['code' => 'kilometer', 'name' => 'Kilometer', 'symbol' => 'km', 'unit_type' => 'length', 'decimal_places' => 2],
            
            // Time / Service
            ['code' => 'hour', 'name' => 'Hour', 'symbol' => 'hr', 'unit_type' => 'time', 'decimal_places' => 2],
            ['code' => 'service', 'name' => 'Service', 'symbol' => 'serv', 'unit_type' => 'time', 'decimal_places' => 0],
        ];

        foreach ($units as $unit) {
            DB::table('measurement_units')->updateOrInsert(
                ['code' => $unit['code']],
                array_merge($unit, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
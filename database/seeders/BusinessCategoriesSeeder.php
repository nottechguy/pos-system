<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['code' => 'grocery', 'name' => 'Grocery Store'],
            ['code' => 'convenience_store', 'name' => 'Convenience Store'],
            ['code' => 'pharmacy', 'name' => 'Pharmacy'],
            ['code' => 'restaurant', 'name' => 'Restaurant'],
            ['code' => 'hardware_store', 'name' => 'Hardware Store'],
            ['code' => 'clothing_store', 'name' => 'Clothing Store'],
            ['code' => 'other', 'name' => 'Other'],
        ];

        foreach ($categories as $category) {
            DB::table('business_categories')->updateOrInsert(
                ['code' => $category['code']],
                array_merge($category, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
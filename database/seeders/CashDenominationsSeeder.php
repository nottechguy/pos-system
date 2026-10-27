<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashDenominationsSeeder extends Seeder
{
    protected int $businessId;

    public function __construct(int $businessId)
    {
        $this->businessId = $businessId;
    }

    public function run(): void
    {
        // Standard Mexican Peso (MXN) bills and coins
        $denominations = [
            // Bills
            ['value' => 1000.00, 'type' => 'bill'],
            ['value' => 500.00, 'type' => 'bill'],
            ['value' => 200.00, 'type' => 'bill'],
            ['value' => 100.00, 'type' => 'bill'],
            ['value' => 50.00, 'type' => 'bill'],
            ['value' => 20.00, 'type' => 'bill'],
            // Coins
            ['value' => 20.00, 'type' => 'coin'],
            ['value' => 10.00, 'type' => 'coin'],
            ['value' => 5.00, 'type' => 'coin'],
            ['value' => 2.00, 'type' => 'coin'],
            ['value' => 1.00, 'type' => 'coin'],
            ['value' => 0.50, 'type' => 'coin'],
        ];

        foreach ($denominations as $item) {
            DB::table('cash_denominations')->updateOrInsert(
                ['business_id' => $this->businessId, 'value' => $item['value']],
                [
                    'business_id' => $this->businessId,
                    'value' => $item['value'],
                    'type' => $item['type'],
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
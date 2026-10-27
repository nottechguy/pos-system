<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    protected int $businessId;

    public function __construct(int $businessId)
    {
        $this->businessId = $businessId;
    }

    public function run(): void
    {
        $methods = [
            [
                'code' => 'cash',
                'name' => 'Cash',
                'requires_reference' => false,
                'affects_cash_drawer' => true,
            ],
            [
                'code' => 'card',
                'name' => 'Credit / Debit Card',
                'requires_reference' => true,
                'affects_cash_drawer' => false,
            ],
            [
                'code' => 'bank_transfer',
                'name' => 'Bank Transfer',
                'requires_reference' => true,
                'affects_cash_drawer' => false,
            ],
            [
                'code' => 'customer_credit',
                'name' => 'Customer Credit',
                'requires_reference' => false,
                'affects_cash_drawer' => false,
            ],
            [
                'code' => 'other',
                'name' => 'Other',
                'requires_reference' => false,
                'affects_cash_drawer' => false,
            ],
        ];

        foreach ($methods as $method) {
            DB::table('payment_methods')->updateOrInsert(
                ['business_id' => $this->businessId, 'code' => $method['code']],
                array_merge($method, ['business_id' => $this->businessId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
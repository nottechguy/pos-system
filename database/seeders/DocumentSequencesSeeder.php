<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSequencesSeeder extends Seeder
{
    protected int $storeId;

    public function __construct(int $storeId)
    {
        $this->storeId = $storeId;
    }

    public function run(): void
    {
        DB::table('document_sequences')->updateOrInsert(
            [
                'store_id' => $this->storeId, 
                'document_type' => 'sale'
            ],
            [
                'prefix' => 'TICK-',
                'next_number' => 1,
                'padding' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
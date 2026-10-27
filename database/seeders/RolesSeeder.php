<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'code' => 'admin',
                'name' => 'Administrator',
                'description' => 'Complete administrative access to the POS system.',
                'is_system' => true,
            ],
            [
                'code' => 'employee',
                'name' => 'Employee',
                'description' => 'Standard cashier and floor operator role.',
                'is_system' => true,
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['code' => $role['code']],
                array_merge($role, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
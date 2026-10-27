<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users module
            ['code' => 'users.view', 'module' => 'users', 'name' => 'View Users'],
            ['code' => 'users.create', 'module' => 'users', 'name' => 'Create Users'],
            ['code' => 'users.update', 'module' => 'users', 'name' => 'Update Users'],
            ['code' => 'users.delete', 'module' => 'users', 'name' => 'Delete Users'],
            ['code' => 'users.permissions.manage', 'module' => 'users', 'name' => 'Manage Permissions'],

            // Stores module
            ['code' => 'stores.view', 'module' => 'stores', 'name' => 'View Stores'],
            ['code' => 'stores.manage', 'module' => 'stores', 'name' => 'Manage Stores'],

            // Products module
            ['code' => 'products.view', 'module' => 'products', 'name' => 'View Products'],
            ['code' => 'products.create', 'module' => 'products', 'name' => 'Create Products'],
            ['code' => 'products.update', 'module' => 'products', 'name' => 'Update Products'],
            ['code' => 'products.delete', 'module' => 'products', 'name' => 'Delete Products'],
            ['code' => 'products.prices.manage', 'module' => 'products', 'name' => 'Manage Prices'],

            // Inventory module
            ['code' => 'inventory.view', 'module' => 'inventory', 'name' => 'View Inventory'],
            ['code' => 'inventory.adjust', 'module' => 'inventory', 'name' => 'Adjust Inventory'],
            ['code' => 'inventory.transfer', 'module' => 'inventory', 'name' => 'Transfer Inventory'],
            ['code' => 'inventory.receive', 'module' => 'inventory', 'name' => 'Receive Inventory'],

            // Sales module
            ['code' => 'sales.create', 'module' => 'sales', 'name' => 'Create Sales'],
            ['code' => 'sales.discount', 'module' => 'sales', 'name' => 'Apply Discounts'],
            ['code' => 'sales.void', 'module' => 'sales', 'name' => 'Void Sales'],
            ['code' => 'sales.refund', 'module' => 'sales', 'name' => 'Refund Sales'],

            // Quotations module
            ['code' => 'quotations.create', 'module' => 'quotations', 'name' => 'Create Quotations'],
            ['code' => 'quotations.send', 'module' => 'quotations', 'name' => 'Send Quotations'],

            // Shifts & Cash
            ['code' => 'shifts.open', 'module' => 'shifts', 'name' => 'Open Shifts'],
            ['code' => 'shifts.close', 'module' => 'shifts', 'name' => 'Close Shifts'],
            ['code' => 'cash.manage', 'module' => 'cash', 'name' => 'Manage Cash Drawer'],

            // Reports & Settings
            ['code' => 'reports.view', 'module' => 'reports', 'name' => 'View Reports'],
            ['code' => 'settings.manage', 'module' => 'settings', 'name' => 'Manage Settings'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['code' => $permission['code']],
                array_merge($permission, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }
}
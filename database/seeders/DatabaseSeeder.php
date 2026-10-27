<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Run global lookup seeders
        $this->call([
            BusinessCategoriesSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            MeasurementUnitsSeeder::class,
        ]);

        // 2. Assign all permissions to the 'admin' role
        $adminRoleId = DB::table('roles')->where('code', 'admin')->value('id');
        $permissionIds = DB::table('permissions')->pluck('id');

        foreach ($permissionIds as $permissionId) {
            DB::table('permission_role')->updateOrInsert([
                'role_id' => $adminRoleId,
                'permission_id' => $permissionId,
            ], [
                'created_at' => now(),
            ]);
        }

        // 3. Create a default Business for testing/initial deployment
        $categoryId = DB::table('business_categories')->where('code', 'grocery')->value('id');
        
        $businessId = DB::table('businesses')->insertGetId([
            'category_id' => $categoryId,
            'name' => 'Abarrotes Todo En Uno',
            'legal_name' => 'Abarrotes Todo En Uno',
            'tax_id' => 'XAXX010101000',
            'currency_code' => 'MXN',
            'timezone' => 'America/Mexico_City',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Run tenant-specific seeders passing the new business ID
        (new PaymentMethodsSeeder($businessId))->run();
        (new CashDenominationsSeeder($businessId))->run();

        // 5. Create default Admin User
        $adminUserId = DB::table('users')->insertGetId([
            'business_id' => $businessId,
            'role_id' => $adminRoleId,
            'code' => '100001',
            'first_name' => 'Sergio Alberto',
            'last_name' => '',
            'email' => 'sergio@abarrotestodoenuno',
            'password' => Hash::make('V935cU1#'),
            'pin_hash' => Hash::make('2505'),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5.5. Generate Sanctum API Token for Catalog Sync automatically
        $adminUser = User::find($adminUserId);
        if ($adminUser) {
            // Revoke any existing sync tokens to avoid duplicates upon re-seeding
            $adminUser->tokens()->where('name', 'catalog-sync-token')->delete();
            $tokenResult = $adminUser->createToken('catalog-sync-token');
            
            if (isset($this->command)) {
                $this->command->info("🔑 Generated Catalog Sync Token: " . $tokenResult->plainTextToken);
            }
        }

        // 6. Create default Store
        $storeId = DB::table('stores')->insertGetId([
            'business_id' => $businessId,
            'manager_user_id' => $adminUserId,
            'code' => 'ST01',
            'name' => 'Sucursal Principal',
            'address_line_1' => 'Calle Heroico Colegio Militar',
            'city' => 'Petatlán',
            'state' => 'Guerrero',
            'postal_code' => '40850',
            'country_code' => 'MX',
            'timezone' => 'America/Mexico_City',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 7. Associate Admin User with the Store
        DB::table('store_user')->insert([
            'store_id' => $storeId,
            'user_id' => $adminUserId,
            'is_default' => true,
            'is_active' => true,
            'job_title' => 'Store Manager',
            'assigned_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 8. Create default POS Terminal
        DB::table('pos_terminals')->insert([
            'store_id' => $storeId,
            'code' => 'TERM01',
            'name' => 'Terminal Caja 1',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 9. Ensure Document Sequences exist BEFORE products
        (new DocumentSequencesSeeder($storeId))->run();

        // 10. Run the Catalog Seeder
        (new CatalogSeeder($businessId, $storeId, $adminUserId))->run();
    }
}
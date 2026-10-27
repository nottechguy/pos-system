<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function __construct(
        protected int $businessId,
        protected int $storeId,
        protected int $adminUserId
    ) {}

    public function run(): void
    {
        // 1. Setup Departments & Categories using updateOrInsert to prevent duplicates
        $departmentsAndCategories = [
            'Embutidos y Carnes Frías' => ['Jamón y Salchichas', 'Chorizos y Tocino'],
            'Lácteos y Refrigerados' => ['Quesos', 'Leches', 'Cremas y Yogurt', 'Gelatinas', 'Mantequillas'],
            'Canasta Básica' => ['Huevos', 'Tomates', 'Chiles', 'Tortillas', 'Frutas y Verduras', 'Tubérculos', 'Grasas y Mantecas', 'Frijoles en Bolsa', 'Pastas y Sopas', 'Granos y Semillas', 'Harinas y Aceites', 'Aderezos', 'Purés', 'Moles', 'Azúcar', 'Sazonadores', 'Endulzantes', 'Especias y Condimentos', 'Miel'],
            'Enlatados y Conservas' => ['Pescados Enlatados', 'Chiles y Salsas', 'Frijoles en Lata', 'Frutas en Lata', 'Verduras en Lata', 'Frutas en Conserva', 'Frutas en Almibar', 'Leches en Lata', 'Mermeladas'],
            'Desayunos y Merienda' => ['Café y Té', 'Cereales y Avena', 'Galletas y Panes', 'Gelatinas', 'Panadería'],
            'Bebidas' => ['Polvo para Bebidas', 'Jugos y Néctares', 'Refrescos', 'Aguas Purificadas', 'Aguas Minerales', 'Bebidas Energizantes', 'Bebidas Rehidrantes', 'Cervezas y Licores'],
            'Botanas y Dulces' => ['Chocolates', 'Gomas de Mascar y Caramelos', 'Papas Fritas, Frituras y Cacahuates', 'Granos y Semillas', 'Helados', 'Salsas'],
            'Limpieza del Hogar' => ['Detergentes y Suavizantes', 'Limpiadores y Cloro', 'Accesorios de Limpieza', 'Insecticidas y Plaguicidas'],
            'Higiene y Cuidado Personal' => ['Papel y Toallas', 'Ciudado Facial', 'Cuidado del Cabello', 'Cuidado Dental y Corporal'],
            'Desechables' => ['Pañales', 'Platos y Cubiertos', 'Aluminio y Plástico'],
            'Mascotas' => ['Alimento Seco', 'Sobres y Premios'],
            'Artículos para el Hogar' => ['Focos y Pilas', 'Velas y Veladoras', 'Encendedores y Cerillos'],
            'Surtido Adicional (Mostrador)' => ['Medicamentos Libres', 'Cigarros'],
            'Postres' => ['Levadura', 'Mermeladas, Cremas y Jarabes', 'Concentrados']
        ];

        $deptIds = [];
        $catIds = [];
        
        $deptCounter = 1;
        $catCounter = 1;

        foreach ($departmentsAndCategories as $deptName => $categories) {
            DB::table('departments')->updateOrInsert(
                ['business_id' => $this->businessId, 'name' => $deptName],
                [
                    'code' => 'DEP-' . str_pad($deptCounter++, 3, '0', STR_PAD_LEFT),
                    'is_active' => true,
                    'updated_at' => now(),
                ]
            );
            $deptIds[$deptName] = DB::table('departments')->where('name', $deptName)->value('id');

            foreach ($categories as $catName) {
                DB::table('product_categories')->updateOrInsert(
                    ['business_id' => $this->businessId, 'department_id' => $deptIds[$deptName], 'name' => $catName],
                    [
                        'code' => 'CAT-' . str_pad($catCounter++, 3, '0', STR_PAD_LEFT),
                        'is_active' => true,
                        'updated_at' => now(),
                    ]
                );
                $catIds[$catName] = DB::table('product_categories')->where('name', $catName)->value('id');
            }
        }

        // 2. Get 'Piece' and 'Kilogram' Measurement Unit IDs
        $pieceUnitId = DB::table('measurement_units')->where('code', 'piece')->value('id');
        $kgUnitId = DB::table('measurement_units')->where('code', 'kg')->value('id') ?? $pieceUnitId; 

        // 3. Setup Default Price List
        DB::table('price_lists')->updateOrInsert(
            ['business_id' => $this->businessId, 'store_id' => $this->storeId, 'code' => 'RETAIL'],
            [
                'name' => 'Precio Público General',
                'currency_code' => 'MXN',
                'is_default' => true,
                'is_active' => true,
                'updated_at' => now(),
            ]
        );
        $priceListId = DB::table('price_lists')->where('code', 'RETAIL')->value('id');

        // 4. Define robust seed Products
        $products = [/*
            ['dept' => 'Embutidos y Carnes Frías', 'cat' => 'Jamón y Salchichas', 'sku' => 'JAM-PAV-250', 'name' => 'Jamón de Pavo Fud 250g', 'barcode' => '7501040000010', 'cost_price' => 28.00, 'sale_price' => 38.00, 'unit' => $pieceUnitId],
            ['dept' => 'Bebidas', 'cat' => 'Refrescos', 'sku' => 'CC-600', 'name' => 'Coca-Cola 600 ml', 'barcode' => '7501055300075', 'cost_price' => 12.50, 'sale_price' => 18.00, 'unit' => $pieceUnitId],*/
            ['dept' => 'Bebidas', 'cat' => 'Aguas Purificadas', 'sku' => 'AGU-500', 'name' => 'Botella de agua 500 ml', 'barcode' => '7501055304745', 'cost_price' => 4.00, 'sale_price' => 8.00, 'unit' => $pieceUnitId],/*
            ['dept' => 'Botanas y Dulces', 'cat' => 'Papas Fritas y Salados', 'sku' => 'SAB-42', 'name' => 'Sabritas Original', 'barcode' => '7501011167360', 'cost_price' => 14.00, 'sale_price' => 20.00, 'unit' => $pieceUnitId],
            ['dept' => 'Canasta Básica', 'cat' => 'Granos y Semillas', 'sku' => 'LIM-COL-KG', 'name' => 'Limón Colima (Granel)', 'barcode' => '2000000000012', 'cost_price' => 18.00, 'sale_price' => 35.00, 'unit' => $kgUnitId, 'fractional' => true],*/
        ];

        foreach ($products as $p) {
            $departmentId = $deptIds[$p['dept']];
            $categoryId = $catIds[$p['cat']];
            $isFractional = $p['fractional'] ?? false;

            // Insert/Update Product Idempotently
            DB::table('products')->updateOrInsert(
                ['business_id' => $this->businessId, 'sku' => $p['sku']],
                [
                    'department_id' => $departmentId,
                    'category_id' => $categoryId,
                    'inventory_unit_id' => $p['unit'],
                    'sale_unit_id' => $p['unit'],
                    'name' => $p['name'],
                    'product_type' => 'standard',
                    'allows_fractional_qty' => $isFractional,
                    'track_inventory' => true,
                    'allow_negative_inventory' => true,
                    'is_active' => true,
                    'created_by_user_id' => $this->adminUserId,
                    'updated_at' => now(),
                ]
            );
            $productId = DB::table('products')->where('sku', $p['sku'])->value('id');

            // Barcodes
            DB::table('product_barcodes')->updateOrInsert(
                ['product_id' => $productId, 'barcode' => $p['barcode']],
                [
                    'unit_id' => $p['unit'],
                    'conversion_factor' => 1.000000,
                    'is_primary' => true,
                    'updated_at' => now(),
                ]
            );

            // Prices
            $margin = $p['sale_price'] > 0 ? (($p['sale_price'] - $p['cost_price']) / $p['sale_price']) * 100 : 0;
            DB::table('product_prices')->updateOrInsert(
                ['product_id' => $productId, 'price_list_id' => $priceListId],
                [
                    'cost_price' => $p['cost_price'],
                    'sale_price' => $p['sale_price'],
                    'discount' => 0.0000,
                    'margin' => $margin,
                    'valid_from' => now(),
                    'created_by_user_id' => $this->adminUserId,
                    'updated_at' => now(),
                ]
            );

            // Store Configuration
            DB::table('store_products')->updateOrInsert(
                ['store_id' => $this->storeId, 'product_id' => $productId],
                [
                    'default_cost' => $p['cost_price'],
                    'last_cost' => $p['cost_price'],
                    'average_cost' => $p['cost_price'],
                    'is_available' => true,
                    'updated_at' => now(),
                ]
            );

            // Inventory Initial Setup (Only insert if it doesn't exist to prevent stacking +100 continuously)
            $inventoryExists = DB::table('inventory_balances')
                ->where('store_id', $this->storeId)
                ->where('product_id', $productId)
                ->exists();

            if (!$inventoryExists) {
                DB::table('inventory_balances')->insert([
                    'store_id' => $this->storeId,
                    'product_id' => $productId,
                    'quantity_on_hand' => 100.0000,
                    'quantity_reserved' => 0.0000,
                    'version' => 1,
                ]);
                
                DB::table('inventory_movements')->insert([
                    'uuid' => Str::uuid(),
                    'store_id' => $this->storeId,
                    'product_id' => $productId,
                    'user_id' => $this->adminUserId,
                    'movement_type' => 'initial_setup',
                    'quantity' => 100.0000,
                    'unit_cost' => $p['cost_price'],
                    'balance_after' => 100.0000,
                    'reason' => 'Initial system bootstrap',
                    'occurred_at' => now(),
                    'created_at' => now(),
                ]);
            }
        }
    }
}
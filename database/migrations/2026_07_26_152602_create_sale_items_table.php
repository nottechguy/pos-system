<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('measurement_units')->nullOnDelete();
            $table->foreignId('price_list_id')->nullable()->constrained('price_lists')->nullOnDelete();
            
            $table->string('sku_snapshot', 64)->nullable();
            $table->string('barcode_snapshot', 80)->nullable();
            $table->string('name_snapshot', 160);
            $table->string('unit_snapshot', 30)->nullable();
            
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_price', 14, 4);
            $table->decimal('unit_cost_snapshot', 14, 4)->nullable();
            
            $table->decimal('gross_total', 14, 2);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2);
            
            $table->timestamps();
            
            $table->index('sale_id');
            $table->index(['product_id', 'sale_id']);
            $table->index(['product_id', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_items');
    }
};
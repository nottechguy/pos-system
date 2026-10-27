<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('store_products', function (Blueprint $table) {
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->foreignId('preferred_supplier_id')->nullable()->constrained('suppliers')->nullOnDelete();
            
            $table->decimal('default_cost', 14, 4)->nullable();
            $table->decimal('last_cost', 14, 4)->nullable();
            $table->decimal('average_cost', 14, 4)->nullable();
            
            $table->decimal('minimum_quantity', 14, 4)->default(0);
            $table->decimal('maximum_quantity', 14, 4)->nullable();
            $table->decimal('reorder_quantity', 14, 4)->nullable();
            
            $table->string('shelf_location', 80)->nullable();
            $table->boolean('is_available')->default(true);
            
            $table->timestamps();
            
            $table->primary(['store_id', 'product_id']);
            $table->index(['product_id', 'is_available']);
            $table->index(['store_id', 'is_available']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('store_products');
    }
};
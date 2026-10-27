<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_product', function (Blueprint $table) {
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->string('supplier_sku', 80)->nullable();
            $table->decimal('last_cost', 14, 4)->nullable();
            $table->decimal('minimum_order_quantity', 14, 4)->nullable();
            $table->unsignedSmallInteger('lead_time_days')->nullable();
            $table->boolean('is_preferred')->default(false);
            
            $table->timestamps();
            
            $table->primary(['supplier_id', 'product_id']);
            $table->index(['product_id', 'is_preferred']);
            $table->index(['supplier_id', 'is_preferred']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('supplier_product');
    }
};
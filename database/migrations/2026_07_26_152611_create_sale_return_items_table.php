<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->cascadeOnDelete();
            
            // Cascades are permitted here because returning items inherently belong to the return document
            $table->foreignId('sale_item_id')->constrained('sale_items')->cascadeOnDelete();
            
            // Product can be nullified if removed from master catalog
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_price_snapshot', 14, 4);
            
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2);
            
            $table->boolean('return_to_inventory')->default(true);
            
            $table->timestamp('created_at')->nullable();
            
            $table->index('sale_return_id');
            $table->index('sale_item_id');
            $table->index('product_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_return_items');
    }
};
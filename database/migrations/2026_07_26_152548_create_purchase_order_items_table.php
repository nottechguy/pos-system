<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            
            $table->string('sku_snapshot', 64);
            $table->string('name_snapshot', 160);
            
            $table->decimal('ordered_quantity', 14, 4);
            $table->decimal('received_quantity', 14, 4)->default(0);
            $table->decimal('unit_cost', 14, 4);
            
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2);
            
            $table->timestamps();
            
            $table->index(['purchase_order_id', 'product_id']);
            $table->index('product_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('purchase_order_items');
    }
};
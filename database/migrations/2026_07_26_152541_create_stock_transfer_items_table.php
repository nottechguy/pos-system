<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_transfer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained('stock_transfers')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            
            $table->decimal('requested_quantity', 14, 4);
            $table->decimal('shipped_quantity', 14, 4)->default(0);
            $table->decimal('received_quantity', 14, 4)->default(0);
            $table->decimal('unit_cost_snapshot', 14, 4)->nullable();
            
            $table->string('notes', 255)->nullable();
            $table->timestamps();
            
            $table->unique(['stock_transfer_id', 'product_id'], 'stock_transfer_items_transfer_product_unique');
            $table->index('product_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_transfer_items');
    }
};
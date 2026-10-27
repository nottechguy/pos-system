<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('goods_receipt_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goods_receipt_id')->constrained('goods_receipts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_cost', 14, 4);
            
            $table->string('lot_number', 80)->nullable();
            $table->date('expiration_date')->nullable();
            
            $table->timestamps();
            
            $table->index(['goods_receipt_id', 'product_id']);
            $table->index(['product_id', 'expiration_date']);
            $table->index('lot_number');
        });
    }

    public function down(): void {
        Schema::dropIfExists('goods_receipt_items');
    }
};
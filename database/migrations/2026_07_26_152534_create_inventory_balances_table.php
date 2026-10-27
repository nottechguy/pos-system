<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_balances', function (Blueprint $table) {
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            
            $table->decimal('quantity_on_hand', 14, 4)->default(0);
            $table->decimal('quantity_reserved', 14, 4)->default(0);
            
            $table->unsignedBigInteger('version')->default(0);
            $table->timestamp('updated_at')->nullable();
            
            $table->primary(['store_id', 'product_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('inventory_balances');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('suspended_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('suspended_sale_id')->constrained('suspended_sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('price_list_id')->nullable()->constrained('price_lists')->nullOnDelete();
            
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_price', 14, 4);
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->index('suspended_sale_id');
            $table->index('product_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('suspended_sale_items');
    }
};
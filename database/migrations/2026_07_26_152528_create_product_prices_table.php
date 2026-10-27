<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('price_list_id')->constrained('price_lists')->cascadeOnDelete();
            
            $table->decimal('cost_price', 14, 4)->default(0);
            $table->decimal('sale_price', 14, 4)->default(0);
            $table->decimal('discount', 14, 4)->default(0);
            $table->decimal('margin', 14, 4)->default(0);

            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            $table->unique(['product_id', 'price_list_id', 'valid_from'], 'prod_prices_list_from_unique');
            $table->index(['price_list_id', 'product_id']);
            $table->index(['product_id', 'valid_from', 'valid_until']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_prices');
    }
};
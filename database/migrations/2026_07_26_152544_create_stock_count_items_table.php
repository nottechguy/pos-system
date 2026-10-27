<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_count_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_count_id')->constrained('stock_counts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            
            $table->decimal('expected_quantity', 14, 4);
            $table->decimal('counted_quantity', 14, 4)->nullable();
            $table->decimal('unit_cost_snapshot', 14, 4)->nullable();
            
            $table->foreignId('counted_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('counted_at')->nullable();
            $table->timestamps();
            
            $table->unique(['stock_count_id', 'product_id']);
            $table->index('product_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_count_items');
    }
};
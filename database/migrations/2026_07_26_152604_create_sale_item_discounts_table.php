<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_item_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_item_id')->constrained('sale_items')->cascadeOnDelete();
            
            $table->foreignId('quantity_rule_id')->nullable()->constrained('product_quantity_rules')->nullOnDelete();
            
            $table->string('discount_type', 30);
            $table->string('description', 160)->nullable();
            
            $table->decimal('rate', 7, 4)->nullable();
            $table->decimal('amount', 14, 2);
            
            $table->timestamp('created_at')->nullable();
            
            $table->index('sale_item_id');
            $table->index('quantity_rule_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_item_discounts');
    }
};
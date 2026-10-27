<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_quantity_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('price_list_id')->nullable()->constrained('price_lists')->cascadeOnDelete();
            
            $table->string('name', 100);
            $table->string('rule_type', 30);
            
            $table->decimal('minimum_quantity', 14, 4)->default(1);
            $table->decimal('group_quantity', 14, 4)->nullable();
            $table->decimal('discount_value', 14, 4);
            
            $table->boolean('repeatable')->default(false);
            $table->unsignedSmallInteger('priority')->default(100);
            
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            
            $table->index(['product_id', 'is_active', 'starts_at', 'ends_at'], 'prod_qty_rules_active_dates_idx');
            $table->index(['price_list_id', 'product_id']);
            $table->index(['product_id', 'minimum_quantity', 'priority'], 'prod_qty_rules_min_qty_pri_idx');
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_quantity_rules');
    }
};
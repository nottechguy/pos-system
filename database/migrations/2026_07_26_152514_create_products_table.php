<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->restrictOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->restrictOnDelete();
            
            $table->foreignId('inventory_unit_id')->constrained('measurement_units')->restrictOnDelete();
            $table->foreignId('sale_unit_id')->constrained('measurement_units')->restrictOnDelete();
            $table->foreignId('net_content_unit_id')->nullable()->constrained('measurement_units')->restrictOnDelete();
            
            $table->string('sku', 64);
            $table->string('name', 160);
            $table->text('description')->nullable();
            $table->string('product_type', 20);
            
            $table->boolean('allows_fractional_qty')->default(false);
            $table->decimal('sale_to_inventory_factor', 14, 6)->default(1);
            $table->decimal('net_content_quantity', 14, 4)->nullable();
            
            $table->boolean('track_inventory')->default(true);
            $table->boolean('allow_negative_inventory')->default(false);
            $table->decimal('target_margin_percent', 7, 4)->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['business_id', 'sku']);
            $table->index(['business_id', 'name']);
            $table->index(['department_id', 'category_id']);
            $table->index(['business_id', 'is_active']);
            $table->index('product_type');
        });
    }

    public function down(): void {
        Schema::dropIfExists('products');
    }
};
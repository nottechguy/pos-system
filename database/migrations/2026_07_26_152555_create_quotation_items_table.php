<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('measurement_units')->nullOnDelete();
            
            $table->string('sku_snapshot', 64)->nullable();
            $table->string('barcode_snapshot', 80)->nullable();
            $table->string('name_snapshot', 160);
            $table->string('description_snapshot', 255)->nullable();
            $table->string('unit_snapshot', 30)->nullable();
            
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_price', 14, 4);
            
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_rate_snapshot', 7, 4)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('line_total', 14, 2);
            
            $table->unsignedSmallInteger('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index(['quotation_id', 'sort_order']);
            $table->index('product_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('quotation_items');
    }
};
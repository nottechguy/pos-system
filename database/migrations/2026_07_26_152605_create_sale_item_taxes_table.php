<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_item_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_item_id')->constrained('sale_items')->cascadeOnDelete();
            
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->nullOnDelete();
            
            $table->string('name_snapshot', 80);
            $table->decimal('rate_snapshot', 7, 4);
            
            $table->decimal('taxable_amount', 14, 2);
            $table->decimal('tax_amount', 14, 2);
            
            $table->timestamp('created_at')->nullable();
            
            $table->index('sale_item_id');
            $table->index('tax_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_item_taxes');
    }
};
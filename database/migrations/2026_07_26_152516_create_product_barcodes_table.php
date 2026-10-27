<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_barcodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('measurement_units')->restrictOnDelete();
            
            // NO UNIQUE CONSTRAINT: Allows the same barcode to exist multiple times
            // for different presentations/units of the same product.
            $table->string('barcode', 80);
            
            $table->decimal('conversion_factor', 14, 6)->default(1);
            $table->boolean('is_primary')->default(false);
            
            $table->timestamps();
            
            $table->index(['product_id', 'is_primary']);
            $table->index(['product_id', 'unit_id']);
            $table->index('barcode'); // Added an index for fast POS scanning performance
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_barcodes');
    }
};
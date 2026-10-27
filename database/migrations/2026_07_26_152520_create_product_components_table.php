<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_components', function (Blueprint $table) {
            $table->foreignId('kit_product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('component_product_id')->constrained('products')->cascadeOnDelete();
            
            $table->decimal('quantity', 14, 4);
            
            $table->timestamps();
            
            $table->primary(['kit_product_id', 'component_product_id']);
        });

        // Enforce the constraint directly at the database level
        DB::statement('ALTER TABLE product_components ADD CONSTRAINT chk_kit_component_diff CHECK (kit_product_id <> component_product_id)');
    }

    public function down(): void {
        Schema::dropIfExists('product_components');
    }
};
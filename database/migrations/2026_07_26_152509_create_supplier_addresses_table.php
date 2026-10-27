<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            
            $table->string('address_line_1', 160);
            $table->string('address_line_2', 160)->nullable();
            
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 12)->nullable();
            $table->char('country_code', 2);
            
            $table->boolean('is_primary')->default(false);
            
            $table->timestamps();
            
            $table->index(['supplier_id', 'is_primary']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('supplier_addresses');
    }
};
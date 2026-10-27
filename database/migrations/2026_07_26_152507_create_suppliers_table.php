<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            
            $table->string('code', 20);
            $table->string('supplier_type', 20);
            $table->string('legal_name', 160);
            $table->string('trade_name', 160)->nullable();
            $table->string('tax_id', 30)->nullable();
            
            $table->string('email', 254)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('notes')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['business_id', 'code']);
            $table->index(['business_id', 'legal_name']);
            $table->index(['business_id', 'trade_name']);
            $table->index(['business_id', 'is_active']);
            $table->index('tax_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('suppliers');
    }
};
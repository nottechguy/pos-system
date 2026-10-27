<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            
            $table->string('code', 30);
            $table->string('name', 80);
            $table->boolean('requires_reference')->default(false);
            $table->boolean('affects_cash_drawer')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->unique(['business_id', 'code']);
            $table->index(['business_id', 'is_active']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('payment_methods');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_return_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods')->restrictOnDelete();
            
            $table->decimal('amount', 14, 2);
            $table->string('reference', 160)->nullable();
            $table->timestamp('refunded_at');
            
            $table->timestamp('created_at')->nullable();
            
            $table->index('sale_return_id');
            $table->index(['payment_method_id', 'refunded_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_return_payments');
    }
};
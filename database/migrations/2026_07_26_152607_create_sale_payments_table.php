<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            
            $table->foreignId('payment_method_id')->constrained('payment_methods')->restrictOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('cash_shifts')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->decimal('amount', 14, 2);
            $table->decimal('received_amount', 14, 2)->nullable();
            $table->decimal('change_amount', 14, 2)->default(0);
            
            $table->string('reference', 160)->nullable();
            $table->string('status', 20)->default('completed');
            
            $table->timestamp('paid_at');
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            $table->index(['sale_id', 'status']);
            $table->index(['payment_method_id', 'paid_at']);
            $table->index(['shift_id', 'paid_at']);
            $table->index('reference');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_payments');
    }
};
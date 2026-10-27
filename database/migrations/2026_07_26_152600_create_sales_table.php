<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('terminal_id')->constrained('pos_terminals')->restrictOnDelete();
            
            // A completed sale must restrict shift deletion
            $table->foreignId('shift_id')->nullable()->constrained('cash_shifts')->restrictOnDelete();
            
            // Historical users and customers use nullOnDelete
            $table->foreignId('cashier_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            
            $table->string('number', 40);
            $table->string('status', 30)->default('pending');
            $table->char('currency_code', 3)->default('MXN');
            
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            $table->decimal('paid_total', 14, 2)->default(0);
            $table->decimal('change_total', 14, 2)->default(0);
            
            $table->string('customer_name_snapshot', 160)->nullable();
            
            $table->timestamp('sold_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('voided_at')->nullable();
            
            $table->foreignId('voided_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('void_reason', 255)->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->unique(['store_id', 'number']);
            $table->index(['store_id', 'sold_at']);
            $table->index(['store_id', 'status', 'sold_at']);
            $table->index(['cashier_user_id', 'sold_at']);
            $table->index(['customer_id', 'sold_at']);
            $table->index(['shift_id', 'status']);
            $table->index(['terminal_id', 'sold_at']);
            $table->index('quotation_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('sales');
    }
};
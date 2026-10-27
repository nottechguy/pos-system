<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('credit_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('credit_account_id')->constrained('credit_accounts')->restrictOnDelete();
            
            $table->foreignId('sale_id')->nullable()->constrained('sales')->nullOnDelete();
            $table->foreignId('sale_return_id')->nullable()->constrained('sale_returns')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('entry_type', 20);
            $table->decimal('amount', 14, 2);
            $table->decimal('balance_after', 14, 2)->nullable();
            
            $table->timestamp('due_at')->nullable();
            $table->string('reference', 160)->nullable();
            $table->string('notes', 255)->nullable();
            
            $table->timestamp('occurred_at');
            $table->timestamp('created_at')->nullable();
            
            $table->index(['credit_account_id', 'occurred_at']);
            $table->index(['credit_account_id', 'due_at']);
            $table->index('sale_id');
            $table->index('sale_return_id');
            $table->index(['entry_type', 'occurred_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('credit_entries');
    }
};
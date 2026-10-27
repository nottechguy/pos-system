<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_shifts', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('terminal_id')->constrained('pos_terminals')->restrictOnDelete();
            
            $table->foreignId('opened_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('closed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('status', 20)->default('open');
            
            $table->decimal('opening_cash', 14, 2)->default(0);
            $table->decimal('expected_cash', 14, 2)->nullable();
            $table->decimal('counted_cash', 14, 2)->nullable();
            $table->decimal('difference', 14, 2)->nullable();
            
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['store_id', 'opened_at']);
            $table->index(['terminal_id', 'status']);
            $table->index(['opened_by_user_id', 'opened_at']);
            $table->index(['status', 'opened_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cash_shifts');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            $table->string('transfer_number', 40);
            
            $table->foreignId('source_store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('destination_store_id')->constrained('stores')->restrictOnDelete();
            
            $table->string('status', 30)->default('draft');
            
            $table->foreignId('requested_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('shipped_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('received_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['source_store_id', 'transfer_number']);
            $table->index(['source_store_id', 'status', 'created_at']);
            $table->index(['destination_store_id', 'status', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_transfers');
    }
};
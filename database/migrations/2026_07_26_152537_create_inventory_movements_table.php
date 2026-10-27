<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('inventory_movements', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('cash_shifts')->nullOnDelete();
            
            $table->string('movement_type', 30);
            $table->decimal('quantity', 14, 4);
            $table->decimal('unit_cost', 14, 4)->nullable();
            $table->decimal('balance_after', 14, 4)->nullable();
            
            // Polymorphic references without DB-level FK constraints
            $table->string('reference_type', 80)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->string('reason', 255)->nullable();
            $table->timestamp('occurred_at');
            $table->timestamp('created_at')->nullable();
            
            $table->index(['store_id', 'product_id', 'occurred_at']);
            $table->index(['product_id', 'occurred_at']);
            $table->index(['reference_type', 'reference_id']);
            $table->index(['user_id', 'occurred_at']);
            $table->index(['movement_type', 'occurred_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('inventory_movements');
    }
};
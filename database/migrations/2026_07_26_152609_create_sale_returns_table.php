<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('sale_id')->constrained('sales')->restrictOnDelete();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained('cash_shifts')->restrictOnDelete();
            $table->foreignId('processed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('number', 40);
            $table->string('status', 20)->default('completed');
            
            $table->decimal('subtotal', 14, 2);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2);
            
            $table->string('reason', 255);
            $table->timestamp('returned_at');
            
            $table->timestamps();
            
            $table->unique(['store_id', 'number']);
            $table->index(['sale_id', 'returned_at']);
            $table->index(['store_id', 'returned_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('sale_returns');
    }
};
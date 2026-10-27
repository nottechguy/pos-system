<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            
            $table->string('number', 40);
            $table->string('status', 20)->default('draft');
            
            $table->foreignId('ordered_by_user_id')->constrained('users')->restrictOnDelete();
            
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('expected_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->unique(['store_id', 'number']);
            $table->index(['supplier_id', 'status', 'ordered_at']);
            $table->index(['store_id', 'status', 'ordered_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('purchase_orders');
    }
};
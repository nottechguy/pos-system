<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->restrictOnDelete();
            $table->foreignId('purchase_order_id')->nullable()->constrained('purchase_orders')->restrictOnDelete();
            
            $table->string('number', 40);
            $table->foreignId('received_by_user_id')->constrained('users')->restrictOnDelete();
            
            $table->timestamp('received_at');
            $table->string('supplier_document_number', 80)->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->unique(['store_id', 'number']);
            $table->index('purchase_order_id');
            $table->index(['supplier_id', 'received_at']);
            $table->index(['store_id', 'received_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('goods_receipts');
    }
};
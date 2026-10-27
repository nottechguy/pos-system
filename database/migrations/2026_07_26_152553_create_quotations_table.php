<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->unique();
            
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            
            $table->foreignId('author_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            
            $table->string('number', 40);
            $table->string('status', 20)->default('draft');
            $table->char('currency_code', 3)->default('MXN');
            
            $table->string('customer_name_snapshot', 160)->nullable();
            $table->string('customer_email_snapshot', 254)->nullable();
            $table->string('customer_phone_snapshot', 20)->nullable();
            
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->decimal('discount_total', 14, 2)->default(0);
            $table->decimal('tax_total', 14, 2)->default(0);
            $table->decimal('total', 14, 2)->default(0);
            
            $table->timestamp('valid_until')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['store_id', 'number']);
            $table->index(['customer_id', 'created_at']);
            $table->index(['author_user_id', 'created_at']);
            $table->index(['store_id', 'status', 'created_at']);
            $table->index(['valid_until', 'status']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('quotations');
    }
};
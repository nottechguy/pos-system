<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('credit_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->restrictOnDelete();
            
            $table->decimal('credit_limit', 14, 2)->default(0);
            $table->unsignedSmallInteger('payment_term_days')->default(0);
            $table->decimal('balance_cached', 14, 2)->default(0);
            $table->string('status', 20)->default('active');
            
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            
            $table->timestamps();
            
            $table->unique('customer_id');
            $table->index('status');
        });
    }

    public function down(): void {
        Schema::dropIfExists('credit_accounts');
    }
};
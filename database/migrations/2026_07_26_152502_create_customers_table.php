<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            
            $table->string('code', 20);
            $table->string('customer_type', 20)->default('individual');
            
            $table->string('first_name', 80)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('business_name', 160)->nullable();
            $table->string('tax_id', 30)->nullable();
            
            $table->string('email', 254)->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('notes')->nullable();
            
            $table->boolean('email_marketing_consent')->default(false);
            $table->boolean('push_marketing_consent')->default(false);
            $table->timestamp('consent_updated_at')->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['business_id', 'code']);
            $table->index(['business_id', 'phone']);
            $table->index(['business_id', 'email']);
            $table->index(['business_id', 'tax_id']);
            $table->index(['business_id', 'last_name', 'first_name']);
            $table->index(['business_id', 'business_name']);
            $table->index(['business_id', 'is_active']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('customers');
    }
};
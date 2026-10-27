<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('manager_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('code', 20);
            $table->string('name', 100);
            $table->string('phone_line_1', 20)->nullable();
            $table->string('phone_line_2', 20)->nullable();
            $table->string('email', 254)->nullable();
            
            $table->string('address_line_1', 160);
            $table->string('address_line_2', 160)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 12);
            $table->char('country_code', 2)->default('MX');
            $table->string('timezone', 64)->default('America/Mexico_City');
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['business_id', 'code']);
            $table->index(['business_id', 'is_active']);
            $table->index(['city', 'state']);
            // FK automatically indexes manager_user_id, but explicitly defining per requirements
            $table->index('manager_user_id'); 
        });
    }

    public function down(): void {
        Schema::dropIfExists('stores');
    }
};
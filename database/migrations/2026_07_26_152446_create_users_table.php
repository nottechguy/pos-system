<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();
            
            $table->char('code', 6);
            $table->string('first_name', 60);
            $table->string('last_name', 80);
            $table->string('email', 254)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('avatar_path', 255)->nullable();
            
            $table->string('password', 255);
            $table->string('pin_hash', 255)->nullable();
            $table->string('status', 20)->default('active');
            
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            
            $table->timestamps();
            $table->softDeletes();

            // Composite Uniques
            $table->unique(['business_id', 'code']);
            $table->unique(['business_id', 'email']);
            
            // Composite Indexes for specific lookup patterns
            $table->index(['role_id', 'status']);
            $table->index(['business_id', 'status']);
            $table->index(['business_id', 'last_name', 'first_name']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
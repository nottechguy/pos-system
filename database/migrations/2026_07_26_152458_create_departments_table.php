<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('responsible_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('code', 30);
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 254)->nullable();
            
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['business_id', 'code']);
            $table->unique(['business_id', 'name']);
            $table->index('responsible_user_id');
            $table->index(['business_id', 'is_active']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('departments');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('store_user', function (Blueprint $table) {
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->string('job_title', 80)->nullable();
            $table->timestamp('assigned_at');
            
            $table->timestamps();
            
            $table->primary(['store_id', 'user_id']);
            $table->index(['user_id', 'is_active']);
            $table->index(['store_id', 'is_active']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('store_user');
    }
};
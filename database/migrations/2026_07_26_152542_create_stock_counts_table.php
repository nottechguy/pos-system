<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            
            $table->string('number', 40);
            $table->string('status', 20)->default('draft');
            
            $table->foreignId('started_by_user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['store_id', 'number']);
            $table->index(['store_id', 'status', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_counts');
    }
};
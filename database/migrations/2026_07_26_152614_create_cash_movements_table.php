<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_shift_id')->constrained('cash_shifts')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('movement_type', 30);
            $table->decimal('amount', 14, 2);
            
            $table->string('reference_type', 80)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            
            $table->string('reason', 255)->nullable();
            $table->timestamp('occurred_at');
            
            $table->timestamp('created_at')->nullable();
            
            $table->index(['cash_shift_id', 'occurred_at']);
            $table->index(['movement_type', 'occurred_at']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cash_movements');
    }
};
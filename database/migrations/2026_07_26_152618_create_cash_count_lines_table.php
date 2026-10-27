<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_count_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_shift_id')->constrained('cash_shifts')->cascadeOnDelete();
            $table->foreignId('denomination_id')->constrained('cash_denominations')->restrictOnDelete();
            
            $table->unsignedInteger('quantity');
            $table->decimal('total', 14, 2);
            
            $table->timestamps();
            
            $table->unique(['cash_shift_id', 'denomination_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cash_count_lines');
    }
};
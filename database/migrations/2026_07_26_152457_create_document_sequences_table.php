<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('document_sequences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            
            $table->string('document_type', 30);
            $table->string('prefix', 20)->nullable();
            $table->unsignedBigInteger('next_number')->default(1);
            $table->unsignedTinyInteger('padding')->default(6);
            $table->string('reset_period', 20)->nullable();
            
            $table->timestamp('last_reset_at')->nullable();
            $table->timestamps();
            
            $table->unique(['store_id', 'document_type']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('document_sequences');
    }
};
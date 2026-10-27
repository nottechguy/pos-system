<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cash_denominations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            
            $table->decimal('value', 14, 2);
            $table->string('type', 10); // 'coin' or 'bill'
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            $table->unique(['business_id', 'value']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('cash_denominations');
    }
};
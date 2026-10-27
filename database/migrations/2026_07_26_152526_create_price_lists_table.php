<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('store_id')->nullable()->constrained('stores')->restrictOnDelete();
            
            $table->string('code', 30);
            $table->string('name', 80);
            $table->char('currency_code', 3)->default('MXN');
            $table->unsignedSmallInteger('priority')->default(100);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['business_id', 'store_id', 'code']);
            $table->index(['store_id', 'is_active', 'priority']);
            $table->index(['business_id', 'is_active', 'priority']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('price_lists');
    }
};
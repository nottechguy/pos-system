<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('department_id')->nullable()->constrained('departments')->restrictOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('product_categories')->restrictOnDelete();
            
            $table->string('code', 30);
            $table->string('name', 80);
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['business_id', 'code']);
            $table->index(['business_id', 'department_id']);
            $table->index('parent_id');
            $table->index(['business_id', 'name']);
            $table->index(['business_id', 'is_active']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('product_categories');
    }
};
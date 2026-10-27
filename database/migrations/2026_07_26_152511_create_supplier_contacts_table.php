<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            
            $table->string('name', 120);
            $table->string('job_title', 80)->nullable();
            $table->string('email', 254)->nullable();
            $table->string('phone', 20)->nullable();
            
            $table->boolean('is_primary')->default(false);
            
            $table->timestamps();
            
            $table->index(['supplier_id', 'is_primary']);
            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void {
        Schema::dropIfExists('supplier_contacts');
    }
};
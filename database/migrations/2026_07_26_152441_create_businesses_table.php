<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('business_categories')->nullOnDelete();
            
            $table->string('name', 100)->index();
            $table->string('legal_name', 160);
            $table->string('tax_id', 30)->nullable()->index();
            $table->string('bio', 500)->nullable();
            
            $table->string('phone_line_1', 20)->nullable();
            $table->string('phone_line_2', 20)->nullable();
            $table->string('email', 254)->nullable();
            $table->string('website', 255)->nullable();
            
            $table->char('currency_code', 3)->default('MXN');
            $table->string('timezone', 64)->default('America/Mexico_City');
            $table->string('logo_path', 255)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('businesses');
    }
};
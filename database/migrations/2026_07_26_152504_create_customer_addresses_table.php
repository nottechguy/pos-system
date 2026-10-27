<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            
            $table->string('type', 20)->default('primary');
            $table->string('address_line_1', 160);
            $table->string('address_line_2', 160)->nullable();
            $table->string('neighborhood', 100)->nullable();
            
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 12);
            $table->char('country_code', 2)->default('MX');
            
            $table->boolean('is_primary')->default(false);
            
            $table->timestamps();
            
            $table->index(['customer_id', 'is_primary']);
            $table->index(['customer_id', 'type']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('customer_addresses');
    }
};
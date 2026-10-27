<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('measurement_units', function (Blueprint $table) {
            $table->id();
            
            $table->string('code', 20)->unique();
            $table->string('name', 60);
            $table->string('symbol', 15);
            $table->string('unit_type', 30);
            $table->unsignedTinyInteger('decimal_places')->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('measurement_units');
    }
};
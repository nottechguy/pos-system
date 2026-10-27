<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('customer_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            
            $table->text('token');
            $table->char('token_hash', 64)->unique();
            $table->string('platform', 20)->nullable();
            
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('revoked_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['customer_id', 'revoked_at']);
            $table->index(['customer_id', 'last_seen_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('customer_devices');
    }
};
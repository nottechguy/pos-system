<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pos_terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->restrictOnDelete();
            
            $table->string('code', 30);
            $table->string('name', 80);
            $table->char('device_uuid', 36)->nullable()->unique();
            $table->string('printer_name', 120)->nullable();
            $table->string('app_version', 30)->nullable();
            
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->unique(['store_id', 'code']);
            $table->index(['store_id', 'is_active']);
            $table->index('last_seen_at');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pos_terminals');
    }
};
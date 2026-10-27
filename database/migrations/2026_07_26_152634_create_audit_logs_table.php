<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('store_id')->nullable()->constrained('stores');
            $table->foreignId('terminal_id')->nullable()->constrained('pos_terminals');
            
            $table->string('action');
            $table->morphs('auditable'); // Creates auditable_type and auditable_id
            
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            $table->uuid('request_uuid')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            
            // Audit logs are append-only. No updated_at.
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

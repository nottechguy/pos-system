<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('outbound_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->restrictOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('related_type', 80)->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            
            $table->string('recipient_email', 254);
            $table->string('subject', 255);
            $table->string('status', 20)->default('pending');
            
            $table->string('provider_message_id', 255)->nullable();
            $table->text('failure_message')->nullable();
            
            $table->timestamp('sent_at')->nullable();
            
            $table->timestamps();
            
            $table->index(['related_type', 'related_id']);
            $table->index(['status', 'created_at']);
            $table->index(['recipient_email', 'created_at']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('outbound_emails');
    }
};
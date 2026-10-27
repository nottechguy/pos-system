<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            
            $table->string('effect', 10); // 'grant' or 'deny'
            
            $table->timestamps();
            
            $table->primary(['user_id', 'permission_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('permission_user');
    }
};
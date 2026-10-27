<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained('businesses')->cascadeOnDelete();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->string('banner_image')->nullable(); // <-- Added banner image support
            $table->string('promotion_type', 30); // 'single', 'pack', 'bulk'
            $table->decimal('promotional_price', 14, 4);
            $table->decimal('minimum_quantity', 14, 4)->default(1.0000);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('promotion_product', function (Blueprint $table) {
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->decimal('promotional_price', 14, 4)->nullable(); // Specific price per product in this promo
            $table->decimal('discount_value', 14, 4)->nullable();     // Specific discount value if applicable
            $table->primary(['promotion_id', 'product_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('promotion_product');
        Schema::dropIfExists('promotions');
    }
};
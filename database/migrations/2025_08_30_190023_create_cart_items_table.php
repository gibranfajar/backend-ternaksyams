<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->foreignId('variantsize_id')->constrained('variant_sizes')->onDelete('cascade');
            $table->integer('qty');
            $table->boolean('is_sale')->default(false);
            $table->boolean('is_flashsale')->default(false);
            $table->enum('discount_type', ['percent', 'value'])->nullable()->default('percent');
            $table->integer('discount')->default(0);
            $table->integer('original_price')->default(0);
            $table->integer('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};

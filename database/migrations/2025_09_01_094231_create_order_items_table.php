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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('variantsize_id')->constrained('variant_sizes')->onDelete('cascade');
            $table->string('name');
            $table->string('variant');
            $table->string('size');
            $table->integer('original_price');
            $table->enum('discout_type', ['percent', 'value'])->default('percent');
            $table->integer('discount');
            $table->integer('price');
            $table->integer('qty');
            $table->integer('total');
            $table->boolean('is_sale')->default(false);
            $table->boolean('is_flashsale')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

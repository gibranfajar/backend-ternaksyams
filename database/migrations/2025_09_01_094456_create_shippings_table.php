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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('shipping_options_id')->constrained('shipping_options')->onDelete('cascade');
            $table->foreignId('shipping_information_id')->constrained('shipping_informations')->onDelete('cascade');
            $table->integer('weight');
            $table->string('order_number')->nullable();
            $table->string('receipt_number')->nullable();
            $table->enum('status', ['packaging', 'sent', 'return done', 'return', 'received'])->default('packaging');
            $table->dateTime('shipped_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};

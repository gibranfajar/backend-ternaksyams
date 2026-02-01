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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['transaction', 'shipping', 'product'])->default('transaction');
            $table->enum('target', ['all', 'users'])->default('all');
            $table->enum('amount_type', ['percent', 'value'])->default('percent');
            $table->integer('amount');
            $table->integer('max_value')->nullable();
            $table->integer('min_transaction_value');
            $table->integer('quota')->default(1);
            $table->integer('limit')->default(1);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->enum('status', ['draft', 'active', 'inactive'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

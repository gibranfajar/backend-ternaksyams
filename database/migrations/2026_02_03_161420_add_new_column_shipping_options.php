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
        Schema::table('shipping_options', function (Blueprint $table) {
            $table->integer('weight')->default(0);
            $table->integer('shipping_cashback')->default(0);
            $table->integer('grand_total')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_options', function (Blueprint $table) {
            //
        });
    }
};

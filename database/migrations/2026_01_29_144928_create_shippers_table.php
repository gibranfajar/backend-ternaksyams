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
        Schema::create('shippers', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name');
            $table->string('shipper_name');
            $table->string('shipper_phone');
            $table->string('shipper_email');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('subdistrict');
            $table->string('shipper_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippers');
    }
};

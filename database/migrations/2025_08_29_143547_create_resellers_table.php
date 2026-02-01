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
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp')->unique();
            $table->string('email')->unique();
            $table->text('address');
            $table->integer('province_id');
            $table->integer('city_id');
            $table->integer('district_id');
            $table->string('postal_code');
            $table->string('bank');
            $table->string('account_number')->unique();
            $table->string('account_name');
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended', 'inactive'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resellers');
    }
};

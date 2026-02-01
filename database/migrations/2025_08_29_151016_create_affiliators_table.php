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
        Schema::create('affiliators', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp')->unique();
            $table->string('email')->unique();
            $table->string('province');
            $table->string('city');
            $table->string('sosmed_account')->nullable();
            $table->string('shopee_account')->nullable();
            $table->string('tokopedia_account')->nullable();
            $table->string('tiktok_account')->nullable();
            $table->string('lazada_account')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliators');
    }
};

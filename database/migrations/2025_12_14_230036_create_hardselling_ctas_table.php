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
        Schema::create('hardselling_ctas', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->string('background');
            $table->string('whatsapp');
            $table->string('link_whatsapp');
            $table->string('shopee');
            $table->string('link_shopee');
            $table->string('tiktok');
            $table->string('link_tiktok');
            $table->string('tokopedia');
            $table->string('link_tokopedia');
            $table->string('seller');
            $table->string('link_seller');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardselling_ctas');
    }
};

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
        Schema::create('hardselling_footers', function (Blueprint $table) {
            $table->id();
            $table->string('footer_text');
            $table->string('background_color');
            $table->string('youtube');
            $table->string('instagram');
            $table->string('tiktok');
            $table->string('facebook');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardselling_footers');
    }
};

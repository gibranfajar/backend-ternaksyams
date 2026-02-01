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
        Schema::create('hardsellings', function (Blueprint $table) {
            $table->id();
            $table->string('content_image');
            $table->string('button_image');
            $table->string('button_link')->nullable();
            $table->enum('position', ['top', 'bottom']);
            $table->integer('sort');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hardsellings');
    }
};

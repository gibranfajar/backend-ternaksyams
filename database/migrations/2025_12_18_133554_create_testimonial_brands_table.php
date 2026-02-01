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
        Schema::create('testimonial_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('social_media');
            $table->string('city_age');
            $table->text('message');
            $table->string('image');
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial_brands');
    }
};

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
        Schema::table('flashsale_items', function (Blueprint $table) {
            // Drop FK lama
            $table->dropForeign(['flashsale_id']);

            // Tambah FK baru dengan ON DELETE CASCADE
            $table->foreign('flashsale_id')
                ->references('id')
                ->on('flash_sales')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flashsale_items', function (Blueprint $table) {
            // Drop FK cascade
            $table->dropForeign(['flashsale_id']);

            // Balikin ke FK tanpa cascade
            $table->foreign('flashsale_id')
                ->references('id')
                ->on('flash_sales');
        });
    }
};

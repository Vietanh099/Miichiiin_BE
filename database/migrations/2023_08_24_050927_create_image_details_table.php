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
        Schema::create('image_details', function (Blueprint $table) {
            $table->id();
            $table->integer('id_hotel')->nullable();
            $table->integer('id_rooms')->nullable();
            $table->integer('id_cate')->nullable();
            $table->softDeletes(); // add
            $table->integer('id_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_details');
    }
};

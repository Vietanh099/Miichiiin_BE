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
        Schema::create('category_rooms', function (Blueprint $table) {
            $table->id();
            $table->integer('id_hotel')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('short_description')->nullable();
            $table->integer('quantity_of_people')->nullable();
            $table->integer('price')->nullable();
            $table->integer('acreage')->nullable();
            $table->integer('floor')->nullable();
            $table->softDeletes(); // add
            $table->integer('status')->default(1);
            $table->integer('likes')->nullable();
            $table->integer('views')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_rooms');
    }
};

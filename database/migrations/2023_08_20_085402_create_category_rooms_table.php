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
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->integer('quantity_of_people')->nullable();
            $table->integer('price')->nullable();
            $table->integer('acreage')->nullable();
            $table->integer('floor')->nullable();
            $table->integer('status')->default(1);
            $table->integer('likes')->nullable();
            $table->integer('views')->nullable();
            $table->softDeletes(); // add
            $table->timestamp('updated_at')->default(now());
            $table->timestamp('created_at')->default(now());

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

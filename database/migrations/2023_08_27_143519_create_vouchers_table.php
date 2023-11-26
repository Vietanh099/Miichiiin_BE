<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->integer('type')->default(1);
            $table->integer('quantity')->nullable();
            $table->integer('discount')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->integer('status')->default(1);
            $table->string('meta')->nullable();
            $table->softDeletes(); // add
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

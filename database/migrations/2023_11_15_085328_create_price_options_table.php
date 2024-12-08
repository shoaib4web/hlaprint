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
        Schema::create('price_options', function (Blueprint $table) {
            $table->id();
            $table->string('page_size');
            $table->string('color_type');
            $table->string('sidedness');
            $table->unsignedBigInteger('no_of_pages');
            $table->unsignedBigInteger('shop_id');
            $table->decimal('base_price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_options');
    }
};

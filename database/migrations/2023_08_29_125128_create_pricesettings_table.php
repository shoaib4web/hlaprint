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
        Schema::create('pricesettings', function (Blueprint $table) {
            $table->id();
            $table->string('color_copy');
            $table->string('BW_copy');
            $table->string('duplex');
            $table->string('single');
            $table->string('pageA1');
            $table->string('pageA2');
            $table->string('pageA3');
            $table->string('pageA4');
            $table->boolean('status')->enum('active','inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pricesettings');
    }
};

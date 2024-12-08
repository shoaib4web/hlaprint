<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('financial_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->string('name');
            $table->string('account_number');
            $table->string('country');
            $table->string('bank');
            $table->string('bank_branch')->nullable();
            $table->string('email');
            $table->string('mobile_number');
            $table->string('address_1');
            $table->string('address_2')->nullable();
            $table->timestamps();

            // Foreign key constraint for shop
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_details');
    }
};

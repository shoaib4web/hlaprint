<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->boolean('subscription_status');
            $table->dateTime('subscription_start');
            $table->dateTime('subscription_renew');
            $table->dateTime('payment_due');
            $table->boolean('is_installment');
            $table->boolean('print_invoice');
            $table->boolean('print_separator');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shop_options');
    }
}

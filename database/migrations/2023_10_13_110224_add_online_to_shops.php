<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlineToShops extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->boolean('online')->default(false); // Add an 'online' column with a default value of false
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('online');
        });
    }
}

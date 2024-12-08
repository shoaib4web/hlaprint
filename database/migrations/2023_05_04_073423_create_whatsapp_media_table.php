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
        Schema::create('whatsapp_media', function (Blueprint $table) {
            $table->id();
            $table->string('message');
            $table->string('phone');
            $table->string('file_type');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_size');
            $table->string('msg_id');
            $table->integer('user_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_message');
    }
};

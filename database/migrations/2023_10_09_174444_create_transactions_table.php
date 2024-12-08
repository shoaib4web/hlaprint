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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('print_job_id')->nullable(); // Print job IDs, you might want to use a JSON column instead
            $table->string('trans_id')->nullable(); // Transaction ID
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->decimal('amount', 10, 2)->nullable();
            $table->string('currency')->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

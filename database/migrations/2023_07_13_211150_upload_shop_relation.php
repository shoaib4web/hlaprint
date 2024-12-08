<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\PrintJobsModel;
use App\Models\Shops;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_Shop_relation', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PrintJobsModel::class);
            $table->foreignIdFor(Shops::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_Shop_relation');
    }
};

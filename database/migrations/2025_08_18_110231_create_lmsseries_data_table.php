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
        Schema::create('lmsseries_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lmsseries_id')->index('lmsseries_id');
            $table->unsignedBigInteger('lmscontent_id')->index('lmscontent_id');
            $table->timestamps();
            $table->unsignedBigInteger('quiz_id')->nullable()->index('lmsseries_data_fk');
            $table->integer('display_order')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmsseries_data');
    }
};

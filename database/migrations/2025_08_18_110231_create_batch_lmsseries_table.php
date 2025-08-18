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
        Schema::create('batch_lmsseries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lms_series_id')->nullable()->index('batch_lmsseries_fk');
            $table->unsignedBigInteger('batch_id')->nullable()->index('batch_lmsseries_fk_1');
            $table->unsignedBigInteger('institute_id')->nullable()->index('batch_lmsseries_fk_2');
            $table->enum('is_pop_exam', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_lmsseries');
    }
};

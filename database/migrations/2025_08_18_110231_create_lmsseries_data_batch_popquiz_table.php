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
        Schema::create('lmsseries_data_batch_popquiz', function (Blueprint $table) {
            $table->unsignedBigInteger('lmsseries_id')->index('lmsseries_id');
            $table->unsignedBigInteger('lmscontent_id')->index('lmscontent_id');
            $table->unsignedBigInteger('batch_id')->nullable()->index('lmsseries_data_batch_popquiz_fk');
            $table->enum('pop_quiz', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmsseries_data_batch_popquiz');
    }
};

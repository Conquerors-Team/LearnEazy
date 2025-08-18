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
        Schema::table('batch_lmsseries', function (Blueprint $table) {
            $table->foreign(['lms_series_id'], 'batch_lmsseries_FK')->references(['id'])->on('lmsseries')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['batch_id'], 'batch_lmsseries_FK_1')->references(['id'])->on('batches')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['institute_id'], 'batch_lmsseries_FK_2')->references(['id'])->on('institutes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batch_lmsseries', function (Blueprint $table) {
            $table->dropForeign('batch_lmsseries_FK');
            $table->dropForeign('batch_lmsseries_FK_1');
            $table->dropForeign('batch_lmsseries_FK_2');
        });
    }
};

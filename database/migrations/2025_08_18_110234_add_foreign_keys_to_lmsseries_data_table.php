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
        Schema::table('lmsseries_data', function (Blueprint $table) {
            $table->foreign(['quiz_id'], 'lmsseries_data_FK')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['lmsseries_id'], 'lmsseries_data_ibfk_1')->references(['id'])->on('lmsseries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['lmscontent_id'], 'lmsseries_data_ibfk_2')->references(['id'])->on('lmscontents')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lmsseries_data', function (Blueprint $table) {
            $table->dropForeign('lmsseries_data_FK');
            $table->dropForeign('lmsseries_data_ibfk_1');
            $table->dropForeign('lmsseries_data_ibfk_2');
        });
    }
};

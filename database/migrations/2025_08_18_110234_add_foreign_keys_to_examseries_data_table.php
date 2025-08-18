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
        Schema::table('examseries_data', function (Blueprint $table) {
            $table->foreign(['examseries_id'], 'examseries_data_ibfk_1')->references(['id'])->on('examseries')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['quiz_id'], 'examseries_data_ibfk_2')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examseries_data', function (Blueprint $table) {
            $table->dropForeign('examseries_data_ibfk_1');
            $table->dropForeign('examseries_data_ibfk_2');
        });
    }
};

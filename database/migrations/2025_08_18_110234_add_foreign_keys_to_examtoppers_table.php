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
        Schema::table('examtoppers', function (Blueprint $table) {
            $table->foreign(['user_id'], 'examtoppers_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['quiz_id'], 'examtoppers_ibfk_2')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['quiz_result_id'], 'examtoppers_ibfk_3')->references(['id'])->on('quizresults')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examtoppers', function (Blueprint $table) {
            $table->dropForeign('examtoppers_ibfk_1');
            $table->dropForeign('examtoppers_ibfk_2');
            $table->dropForeign('examtoppers_ibfk_3');
        });
    }
};

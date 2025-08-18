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
        Schema::table('questionbank_quizzes', function (Blueprint $table) {
            $table->foreign(['questionbank_id'], 'questionbank_quizzes_ibfk_1')->references(['id'])->on('questionbank')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['subject_id'], 'questionbank_quizzes_ibfk_2')->references(['id'])->on('subjects')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['quize_id'], 'questionbank_quizzes_ibfk_3')->references(['id'])->on('quizzes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionbank_quizzes', function (Blueprint $table) {
            $table->dropForeign('questionbank_quizzes_ibfk_1');
            $table->dropForeign('questionbank_quizzes_ibfk_2');
            $table->dropForeign('questionbank_quizzes_ibfk_3');
        });
    }
};

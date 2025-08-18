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
        Schema::table('quizzes_subjects', function (Blueprint $table) {
            $table->foreign(['quiz_id'], 'quizzes_subjects_FK')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['subject_id'], 'quizzes_subjects_FK_1')->references(['id'])->on('subjects')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes_subjects', function (Blueprint $table) {
            $table->dropForeign('quizzes_subjects_FK');
            $table->dropForeign('quizzes_subjects_FK_1');
        });
    }
};

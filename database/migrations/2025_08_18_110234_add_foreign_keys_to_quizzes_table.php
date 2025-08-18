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
        Schema::table('quizzes', function (Blueprint $table) {
            $table->foreign(['competitive_exam_type_id'], 'quizzes_FK')->references(['id'])->on('competitive_exam_types')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['category_id'], 'quizzes_ibfk_1')->references(['id'])->on('quizcategories')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['instructions_page_id'], 'quizzes_ibfk_2')->references(['id'])->on('instructions')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign('quizzes_FK');
            $table->dropForeign('quizzes_ibfk_1');
            $table->dropForeign('quizzes_ibfk_2');
        });
    }
};

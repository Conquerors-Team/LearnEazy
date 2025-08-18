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
        Schema::table('examseries_courses', function (Blueprint $table) {
            $table->foreign(['exam_series_id'], 'lmsseries_courses_FK1')->references(['id'])->on('examseries')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['course_id'], 'lmsseries_courses_FK_11')->references(['id'])->on('courses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['class_id'], 'lmsseries_courses_FK_21')->references(['id'])->on('student_classes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('examseries_courses', function (Blueprint $table) {
            $table->dropForeign('lmsseries_courses_FK1');
            $table->dropForeign('lmsseries_courses_FK_11');
            $table->dropForeign('lmsseries_courses_FK_21');
        });
    }
};

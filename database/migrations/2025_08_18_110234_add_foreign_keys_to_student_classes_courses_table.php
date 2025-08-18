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
        Schema::table('student_classes_courses', function (Blueprint $table) {
            $table->foreign(['student_class_id'], 'student_classes_courses_FK')->references(['id'])->on('student_classes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['course_id'], 'student_classes_courses_FK_1')->references(['id'])->on('courses')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_classes_courses', function (Blueprint $table) {
            $table->dropForeign('student_classes_courses_FK');
            $table->dropForeign('student_classes_courses_FK_1');
        });
    }
};

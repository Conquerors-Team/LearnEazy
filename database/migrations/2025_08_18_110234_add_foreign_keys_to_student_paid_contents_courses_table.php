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
        Schema::table('student_paid_contents_courses', function (Blueprint $table) {
            $table->foreign(['course_id'], 'student_paid_contents_courses_FK')->references(['id'])->on('courses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['student_paid_contents_id'], 'student_paid_contents_courses_FK_1')->references(['id'])->on('student_paid_contents')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_paid_contents_courses', function (Blueprint $table) {
            $table->dropForeign('student_paid_contents_courses_FK');
            $table->dropForeign('student_paid_contents_courses_FK_1');
        });
    }
};

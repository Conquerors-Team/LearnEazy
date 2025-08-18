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
        Schema::create('student_classes_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('student_class_id')->nullable()->index('student_classes_courses_fk_11');
            $table->unsignedBigInteger('course_id')->nullable()->index('student_classes_courses_fk1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_classes_courses');
    }
};

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
        Schema::create('examseries_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_series_id')->index('lmsseries_courses_fk1');
            $table->unsignedBigInteger('course_id')->index('lmsseries_courses_fk_11');
            $table->unsignedBigInteger('class_id')->index('lmsseries_courses_fk_21');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examseries_courses');
    }
};

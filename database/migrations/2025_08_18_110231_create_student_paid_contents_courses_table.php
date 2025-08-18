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
        Schema::create('student_paid_contents_courses', function (Blueprint $table) {
            $table->integer('student_paid_contents_id')->nullable()->index('student_paid_contents_courses_fk_1');
            $table->unsignedBigInteger('course_id')->nullable()->index('student_paid_contents_courses_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_paid_contents_courses');
    }
};

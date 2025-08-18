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
        Schema::create('lmsseries_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('lms_series_id')->index('lmsseries_courses_fk');
            $table->unsignedBigInteger('course_id')->index('lmsseries_courses_fk_1');
            $table->unsignedBigInteger('class_id')->index('lmsseries_courses_fk_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmsseries_courses');
    }
};

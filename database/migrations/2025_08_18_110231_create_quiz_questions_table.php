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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('quiz_id')->nullable();
            $table->integer('quiz_result_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->longText('questions_data')->nullable();
            $table->longText('subjects_data')->nullable();
            $table->tinyInteger('is_exam_started')->default(1);
            $table->tinyInteger('is_exam_completed')->default(0);
            $table->longText('current_state')->nullable();
            $table->integer('current_hour')->nullable();
            $table->integer('current_minute')->nullable();
            $table->integer('current_second')->nullable();
            $table->integer('current_question_id')->nullable();
            $table->timestamp('created_at')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('updated_at')->default('0000-00-00 00:00:00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};

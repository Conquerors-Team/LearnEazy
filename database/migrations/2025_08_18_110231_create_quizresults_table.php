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
        Schema::create('quizresults', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 100);
            $table->unsignedBigInteger('quiz_id')->index('quiz_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->unsignedInteger('marks_obtained')->default(0);
            $table->decimal('negative_marks', 10)->default(0);
            $table->unsignedInteger('total_marks')->default(0);
            $table->decimal('percentage', 10);
            $table->enum('exam_status', ['pass', 'fail', 'pending'])->default('pending');
            $table->text('answers');
            $table->text('subject_analysis')->nullable();
            $table->text('correct_answer_questions')->nullable();
            $table->text('wrong_answer_questions')->nullable();
            $table->text('not_answered_questions')->nullable();
            $table->text('time_spent_correct_answer_questions');
            $table->text('time_spent_wrong_answer_questions');
            $table->text('time_spent_not_answered_questions');
            $table->string('percentage_title');
            $table->string('grade_title');
            $table->string('grade_points');
            $table->integer('rank')->nullable();
            $table->integer('total_users_for_this_quiz')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable()->index('batch_id');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizresults');
    }
};

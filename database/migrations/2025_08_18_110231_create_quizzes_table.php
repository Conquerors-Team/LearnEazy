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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('dueration');
            $table->unsignedBigInteger('category_id')->nullable()->index('category_id');
            $table->tinyInteger('is_paid')->default(0);
            $table->decimal('cost', 10)->nullable();
            $table->integer('validity');
            $table->decimal('total_marks', 10)->unsigned()->default(0);
            $table->boolean('having_negative_mark')->default(false);
            $table->decimal('negative_mark', 10)->default(0);
            $table->decimal('pass_percentage', 10)->unsigned()->default(0);
            $table->string('tags');
            $table->tinyInteger('publish_results_immediately')->default(1);
            $table->text('description');
            $table->integer('total_questions');
            $table->unsignedBigInteger('instructions_page_id')->index('instructions_page_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->tinyInteger('show_in_front')->default(0);
            $table->string('exam_type', 20)->default('NSNT');
            $table->text('section_data')->nullable();
            $table->tinyInteger('has_language')->default(0);
            $table->string('image', 250)->nullable();
            $table->string('language_name', 50)->nullable();
            $table->tinyInteger('quiz_applicability')->default(0);
            $table->string('start_time', 10)->nullable();
            $table->decimal('marks_per_question', 10)->nullable()->default(0);
            $table->string('display_type', 100)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->string('year', 100)->nullable();
            $table->enum('is_multisubject', ['yes', 'no'])->nullable()->default('no');
            $table->bigInteger('competitive_exam_type_id')->nullable()->index('quizzes_fk');
            $table->integer('record_created_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};

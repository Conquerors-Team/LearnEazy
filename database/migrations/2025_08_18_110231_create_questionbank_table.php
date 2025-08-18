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
        Schema::create('questionbank', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_id')->index('subject_id');
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->unsignedBigInteger('topic_id')->index('topic_id');
            $table->unsignedBigInteger('sub_topic_id')->nullable();
            $table->string('question_tags');
            $table->string('slug');
            $table->enum('question_type', ['radio', 'checkbox', 'descriptive', 'blanks', 'match', 'para', 'video', 'audio'])->default('radio');
            $table->text('question');
            $table->string('question_file');
            $table->boolean('question_file_is_url')->default(false);
            $table->unsignedInteger('total_answers');
            $table->text('answers');
            $table->integer('total_correct_answers')->default(1);
            $table->text('correct_answers');
            $table->unsignedInteger('marks');
            $table->integer('time_to_spend')->default(1);
            $table->enum('difficulty_level', ['easy', 'medium', 'hard'])->default('easy');
            $table->string('hint', 250);
            $table->text('explanation');
            $table->string('explanation_file', 50)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->text('question_l2')->nullable();
            $table->text('explanation_l2')->nullable();
            $table->unsignedBigInteger('questionbank_category_id')->nullable()->index('questionbank_fk');
            $table->unsignedBigInteger('question_bank_type_id')->nullable()->index('questionbank_fk_1');
            $table->unsignedBigInteger('competitive_exam_type_id')->nullable();
            $table->string('question_code', 100)->nullable();
            $table->string('year', 100)->nullable();
            $table->bigInteger('created_by_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionbank');
    }
};

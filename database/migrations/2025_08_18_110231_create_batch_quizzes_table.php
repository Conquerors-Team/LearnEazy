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
        Schema::create('batch_quizzes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('quiz_id')->nullable()->index('batch_quizzes_quiz_id_idx');
            $table->integer('category_id')->nullable();
            $table->integer('batch_id')->nullable()->index('batch_quizzes_batch_id_idx');
            $table->integer('institute_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('date_time')->nullable()->comment('This will use for LIVE Quizzes');
            $table->enum('is_popquiz', ['yes', 'no'])->nullable()->default('no')->comment('This will use for \'LIVE\' quizzes');
            $table->unsignedBigInteger('onlineclass_id')->nullable();

            $table->index(['quiz_id', 'batch_id'], 'batch_quizzes_quiz_id_idx1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_quizzes');
    }
};

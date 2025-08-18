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
        Schema::create('questionbank_quizzes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('questionbank_id')->index('questionbank_quizzes_questionbank_id_foreign');
            $table->unsignedBigInteger('quize_id')->index('quize_id');
            $table->unsignedBigInteger('subject_id')->index('subject_id');
            $table->integer('marks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionbank_quizzes');
    }
};

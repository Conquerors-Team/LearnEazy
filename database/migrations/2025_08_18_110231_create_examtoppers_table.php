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
        Schema::create('examtoppers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->unsignedBigInteger('quiz_id')->index('quiz_id');
            $table->decimal('percentage', 10);
            $table->integer('rank');
            $table->unsignedBigInteger('quiz_result_id')->index('quiz_result_id');
            $table->timestamps();

            $table->index(['quiz_id'], 'quiz_id_2');
            $table->index(['quiz_result_id'], 'quiz_result_id_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examtoppers');
    }
};

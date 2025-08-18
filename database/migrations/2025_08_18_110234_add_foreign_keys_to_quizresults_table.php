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
        Schema::table('quizresults', function (Blueprint $table) {
            $table->foreign(['quiz_id'], 'quizresults_ibfk_1')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['user_id'], 'quizresults_ibfk_2')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['batch_id'], 'quizresults_ibfk_3')->references(['id'])->on('batches')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quizresults', function (Blueprint $table) {
            $table->dropForeign('quizresults_ibfk_1');
            $table->dropForeign('quizresults_ibfk_2');
            $table->dropForeign('quizresults_ibfk_3');
        });
    }
};

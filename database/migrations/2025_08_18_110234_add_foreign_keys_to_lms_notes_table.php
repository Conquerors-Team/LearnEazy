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
        Schema::table('lms_notes', function (Blueprint $table) {
            $table->foreign(['subject_id'], 'lmsseries_FK1')->references(['id'])->on('subjects')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['quiz_id'], 'lmsseries_FK_11')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lms_notes', function (Blueprint $table) {
            $table->dropForeign('lmsseries_FK1');
            $table->dropForeign('lmsseries_FK_11');
        });
    }
};

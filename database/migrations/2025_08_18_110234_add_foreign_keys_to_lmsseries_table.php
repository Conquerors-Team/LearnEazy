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
        Schema::table('lmsseries', function (Blueprint $table) {
            $table->foreign(['subject_id'], 'lmsseries_FK')->references(['id'])->on('subjects')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['quiz_id'], 'lmsseries_FK_1')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['lms_category_id'], 'lmsseries_ibfk_1')->references(['id'])->on('lmscategories')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lmsseries', function (Blueprint $table) {
            $table->dropForeign('lmsseries_FK');
            $table->dropForeign('lmsseries_FK_1');
            $table->dropForeign('lmsseries_ibfk_1');
        });
    }
};

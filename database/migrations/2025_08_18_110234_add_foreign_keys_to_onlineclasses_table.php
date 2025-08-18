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
        Schema::table('onlineclasses', function (Blueprint $table) {
            $table->foreign(['batch_id'], 'notifications_FK1')->references(['id'])->on('batches')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['lmsseries_id'], 'onlineclasses_FK')->references(['id'])->on('lmsseries')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['lmsnotes_id'], 'onlineclasses_FK_1')->references(['id'])->on('lms_notes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['student_class_id'], 'onlineclasses_FK_2')->references(['id'])->on('student_classes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['live_quiz_id'], 'onlineclasses_FK_3')->references(['id'])->on('quizzes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('onlineclasses', function (Blueprint $table) {
            $table->dropForeign('notifications_FK1');
            $table->dropForeign('onlineclasses_FK');
            $table->dropForeign('onlineclasses_FK_1');
            $table->dropForeign('onlineclasses_FK_2');
            $table->dropForeign('onlineclasses_FK_3');
        });
    }
};

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
        Schema::table('lmscontents', function (Blueprint $table) {
            $table->foreign(['chapter_id'], 'lmscontents_FK')->references(['id'])->on('chapters')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['topic_id'], 'lmscontents_FK_1')->references(['id'])->on('topics')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['sub_topic_id'], 'lmscontents_FK_2')->references(['id'])->on('topics')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['subject_id'], 'lmscontents_ibfk_1')->references(['id'])->on('subjects')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lmscontents', function (Blueprint $table) {
            $table->dropForeign('lmscontents_FK');
            $table->dropForeign('lmscontents_FK_1');
            $table->dropForeign('lmscontents_FK_2');
            $table->dropForeign('lmscontents_ibfk_1');
        });
    }
};

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
        Schema::table('questionbank', function (Blueprint $table) {
            $table->foreign(['questionbank_category_id'], 'questionbank_FK')->references(['id'])->on('questionbank_categories')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['question_bank_type_id'], 'questionbank_FK_1')->references(['id'])->on('question_bank_types')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['subject_id'], 'questionbank_ibfk_1')->references(['id'])->on('subjects')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['topic_id'], 'questionbank_ibfk_2')->references(['id'])->on('topics')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionbank', function (Blueprint $table) {
            $table->dropForeign('questionbank_FK');
            $table->dropForeign('questionbank_FK_1');
            $table->dropForeign('questionbank_ibfk_1');
            $table->dropForeign('questionbank_ibfk_2');
        });
    }
};

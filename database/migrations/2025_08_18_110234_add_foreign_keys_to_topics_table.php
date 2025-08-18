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
        Schema::table('topics', function (Blueprint $table) {
            $table->foreign(['chapter_id'], 'topics_FK')->references(['id'])->on('chapters')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['subject_id'], 'topics_ibfk_1')->references(['id'])->on('subjects')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropForeign('topics_FK');
            $table->dropForeign('topics_ibfk_1');
        });
    }
};

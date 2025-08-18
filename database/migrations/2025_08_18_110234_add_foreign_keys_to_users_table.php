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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['student_class_id'], 'users_FK')->references(['id'])->on('student_classes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['course_id'], 'users_FK2')->references(['id'])->on('courses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['board_id'], 'users_FK_1')->references(['id'])->on('boards')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_FK');
            $table->dropForeign('users_FK2');
            $table->dropForeign('users_FK_1');
        });
    }
};

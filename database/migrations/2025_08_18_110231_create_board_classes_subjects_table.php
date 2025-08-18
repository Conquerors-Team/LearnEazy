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
        Schema::create('board_classes_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('board_class_id')->nullable()->index('board_classes_subjects_fk_1');
            $table->unsignedBigInteger('board_subject_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_classes_subjects');
    }
};

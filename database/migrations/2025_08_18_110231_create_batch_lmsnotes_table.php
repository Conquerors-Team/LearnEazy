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
        Schema::create('batch_lmsnotes', function (Blueprint $table) {
            $table->unsignedBigInteger('lms_note_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('institute_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_lmsnotes');
    }
};

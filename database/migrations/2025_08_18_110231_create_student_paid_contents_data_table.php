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
        Schema::create('student_paid_contents_data', function (Blueprint $table) {
            $table->unsignedBigInteger('student_paid_contents_id')->nullable();
            $table->integer('item_id')->nullable();
            $table->char('item_type', 50)->nullable();
            $table->timestamps();
            $table->integer('subject_id')->nullable();
            $table->integer('chapter_id')->nullable();
            $table->integer('topic_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_paid_contents_data');
    }
};

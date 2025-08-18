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
        Schema::create('lms_notes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->string('image');
            $table->text('description');
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->unsignedBigInteger('subject_id')->nullable()->index('lmsseries_fk1');
            $table->unsignedBigInteger('quiz_id')->nullable()->index('lmsseries_fk_11');
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('sub_topic_id')->nullable();
            $table->string('content_type', 100)->nullable();
            $table->string('file_path', 512)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lms_notes');
    }
};

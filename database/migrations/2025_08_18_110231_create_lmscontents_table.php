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
        Schema::create('lmscontents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('code', 20);
            $table->string('image');
            $table->unsignedBigInteger('subject_id')->index('subject_id');
            $table->enum('content_type', ['file', 'video', 'audio', 'url', 'video_url', 'audio_url', 'iframe', 'animation', 'text'])->default('file');
            $table->boolean('is_url')->default(false);
            $table->string('file_path')->nullable();
            $table->text('description');
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->unsignedBigInteger('chapter_id')->nullable()->index('lmscontents_fk');
            $table->unsignedBigInteger('topic_id')->nullable()->index('lmscontents_fk_1');
            $table->unsignedBigInteger('sub_topic_id')->nullable()->index('lmscontents_fk_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmscontents');
    }
};

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
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_id')->index('subject_id');
            $table->unsignedBigInteger('chapter_id')->nullable()->index('topics_fk');
            $table->bigInteger('parent_id')->default(0)->comment('Topic/Sibtopic');
            $table->string('topic_name');
            $table->string('slug', 50)->unique('slug');
            $table->text('description');
            $table->integer('sort_order')->default(1);
            $table->timestamps();
            $table->integer('institute_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};

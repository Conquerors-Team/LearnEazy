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
        Schema::create('lmsseries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->tinyInteger('is_paid')->default(0);
            $table->decimal('cost', 10);
            $table->integer('validity');
            $table->integer('total_items');
            $table->unsignedBigInteger('lms_category_id')->nullable()->index('lms_category_id');
            $table->string('image');
            $table->text('short_description');
            $table->text('description');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('record_updated_by');
            $table->tinyInteger('show_in_front')->default(0);
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->unsignedBigInteger('subject_id')->nullable()->index('lmsseries_fk');
            $table->unsignedBigInteger('quiz_id')->nullable()->index('lmsseries_fk_1');
            $table->unsignedBigInteger('chapter_id')->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('sub_topic_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmsseries');
    }
};

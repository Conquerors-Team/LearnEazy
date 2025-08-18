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
        Schema::create('onlineclasses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('short_description');
            $table->text('description');
            $table->string('url');
            $table->timestamp('valid_from')->default('0000-00-00 00:00:00');
            $table->timestamp('valid_to')->default('0000-00-00 00:00:00');
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->unsignedBigInteger('batch_id')->nullable()->index('notifications_fk1');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->string('class_time', 50)->nullable();
            $table->integer('class_duration')->nullable()->comment('Duration in minutes');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->string('topic', 100)->nullable();
            $table->unsignedBigInteger('topic_id')->nullable();
            $table->unsignedBigInteger('lmsseries_id')->nullable()->index('onlineclasses_fk');
            $table->unsignedBigInteger('lmsnotes_id')->nullable()->index('onlineclasses_fk_1');
            $table->unsignedBigInteger('student_class_id')->nullable()->index('onlineclasses_fk_2');
            $table->unsignedBigInteger('live_quiz_id')->nullable()->index('onlineclasses_fk_3');
            $table->enum('live_quiz_popstatus', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onlineclasses');
    }
};

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
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('short_description');
            $table->text('description');
            $table->string('url');
            $table->timestamp('valid_from')->useCurrentOnUpdate()->useCurrent();
            $table->timestamp('valid_to')->default('0000-00-00 00:00:00');
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->unsignedBigInteger('batch_id')->nullable()->index('notifications_fk');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->string('notification_for', 100)->nullable();
            $table->unsignedBigInteger('student_class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

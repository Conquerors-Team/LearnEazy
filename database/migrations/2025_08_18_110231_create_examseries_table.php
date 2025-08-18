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
        Schema::create('examseries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug', 50);
            $table->unsignedBigInteger('category_id')->index('category_id');
            $table->boolean('is_paid')->default(false);
            $table->decimal('cost', 10);
            $table->integer('validity');
            $table->integer('total_exams');
            $table->integer('total_questions');
            $table->string('image', 50);
            $table->text('short_description');
            $table->text('description');
            $table->integer('record_updated_by');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
            $table->integer('institute_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examseries');
    }
};

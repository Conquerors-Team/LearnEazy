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
        Schema::create('subjects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('subject_title', 50);
            $table->string('subject_code', 20);
            $table->string('slug', 50)->unique('slug');
            $table->integer('maximum_marks');
            $table->integer('pass_marks');
            $table->tinyInteger('is_lab')->default(0);
            $table->tinyInteger('is_elective_type')->default(0);
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->string('image', 500)->nullable();
            $table->unsignedBigInteger('subjects_logos_id')->nullable()->index('subjects_fk');
            $table->string('color_code', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};

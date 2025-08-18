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
        Schema::create('student_paid_contents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title', 191)->nullable();
            $table->string('slug', 191)->nullable();
            $table->integer('cost')->nullable();
            $table->integer('duration')->nullable();
            $table->enum('duration_type', ['Day', 'Week', 'Month', 'Year'])->nullable();
            $table->integer('total_items')->nullable();
            $table->integer('institute_id')->nullable();
            $table->timestamps();
            $table->string('display_type', 100)->nullable()->comment('subject,chapter,previousyear,grand');
            $table->integer('free_trail_days')->nullable();
            $table->boolean('is_paid')->nullable()->default(true);
            $table->string('image', 100)->nullable();
            $table->string('short_description', 100)->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_paid_contents');
    }
};

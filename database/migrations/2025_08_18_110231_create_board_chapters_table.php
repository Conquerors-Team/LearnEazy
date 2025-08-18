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
        Schema::create('board_chapters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug', 100)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('file_input', 150)->nullable();
            $table->enum('status', ['Active', 'Inactive'])->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('board_id')->nullable();
            $table->unsignedBigInteger('board_class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_chapters');
    }
};

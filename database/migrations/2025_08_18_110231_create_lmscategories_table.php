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
        Schema::create('lmscategories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category');
            $table->string('slug')->unique();
            $table->string('image');
            $table->text('description');
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmscategories');
    }
};

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
        Schema::create('batch_faculty', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index('batch_faculty_fk');
            $table->unsignedBigInteger('batch_id')->nullable()->index('batch_faculty_fk_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_faculty');
    }
};

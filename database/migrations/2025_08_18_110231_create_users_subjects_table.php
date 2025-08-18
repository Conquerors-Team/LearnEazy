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
        Schema::create('users_subjects', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index('users_subjects_fk');
            $table->unsignedBigInteger('subject_id')->nullable()->index('users_subjects_fk_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_subjects');
    }
};

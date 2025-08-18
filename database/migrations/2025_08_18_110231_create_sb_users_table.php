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
        Schema::create('sb_users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('password', 100)->nullable();
            $table->string('email')->nullable()->unique('email');
            $table->string('profile_image')->nullable();
            $table->string('user_type', 10);
            $table->dateTime('creation_time');
            $table->string('token', 50)->unique('token');
            $table->dateTime('last_activity')->nullable();
            $table->integer('typing')->nullable()->default(-1);
            $table->tinyInteger('department')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sb_users');
    }
};

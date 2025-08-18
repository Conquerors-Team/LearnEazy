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
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->string('key')->nullable()->unique();
            $table->string('slug')->nullable()->unique();
            $table->string('image', 50)->nullable();
            $table->text('settings_data')->nullable();
            $table->text('description');
            $table->timestamps();
            $table->enum('status', ['Active', 'Inactive'])->nullable()->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

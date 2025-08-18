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
        Schema::create('permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 191);
            $table->string('module', 50)->nullable();
            $table->timestamps();
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->enum('default_for_institute', ['yes', 'no'])->nullable()->default('yes');
            $table->enum('default_for_faculty', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};

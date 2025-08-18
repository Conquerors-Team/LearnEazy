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
        Schema::create('alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->char('type', 10)->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->enum('select_type', ['batch', 'yesno', 'text'])->nullable()->default('batch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};

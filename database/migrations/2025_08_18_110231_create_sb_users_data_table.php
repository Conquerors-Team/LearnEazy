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
        Schema::create('sb_users_data', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('slug');
            $table->string('name');
            $table->text('value');

            $table->unique(['user_id', 'slug'], 'sb_users_data_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sb_users_data');
    }
};

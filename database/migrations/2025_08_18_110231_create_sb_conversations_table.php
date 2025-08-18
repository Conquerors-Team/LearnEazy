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
        Schema::create('sb_conversations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->string('title')->nullable();
            $table->dateTime('creation_time');
            $table->tinyInteger('status_code')->nullable()->default(0);
            $table->tinyInteger('department')->nullable();
            $table->integer('agent_id')->nullable()->index('agent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sb_conversations');
    }
};

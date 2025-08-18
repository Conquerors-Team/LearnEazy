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
        Schema::create('sb_messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id');
            $table->text('message');
            $table->dateTime('creation_time');
            $table->tinyInteger('status_code')->nullable()->default(0);
            $table->text('attachments')->nullable();
            $table->text('payload')->nullable();
            $table->integer('conversation_id')->index('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sb_messages');
    }
};

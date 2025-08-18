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
        Schema::create('user_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action', 191);
            $table->string('action_model', 191)->nullable();
            $table->integer('action_id')->nullable();
            $table->timestamps();
            $table->unsignedInteger('user_id')->nullable()->index('259281_5c4fd29b38b49');
            $table->longText('record_original')->nullable();
            $table->longText('record_update')->nullable();
            $table->string('ipaddress', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_actions');
    }
};

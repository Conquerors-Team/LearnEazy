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
        Schema::create('users_login_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username', 100)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('ipaddress', 100)->nullable();
            $table->string('device_name', 100)->nullable();
            $table->string('device_type', 100)->nullable();
            $table->string('platform', 500)->nullable();
            $table->string('platform_version', 100)->nullable();
            $table->string('browser', 500)->nullable();
            $table->string('browser_version', 100)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('user_id')->nullable()->index('users_login_history_fk');
            $table->string('login_status', 800)->nullable()->default('Tried');
            $table->string('robot', 100)->nullable();
            $table->string('languages', 100)->nullable();
            $table->text('request_headers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_login_history');
    }
};

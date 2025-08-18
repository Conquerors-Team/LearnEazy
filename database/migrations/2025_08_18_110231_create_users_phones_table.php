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
        Schema::create('users_phones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mobile_number', 100)->nullable();
            $table->string('country_code', 5)->nullable()->default('91');
            $table->string('status', 100)->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index('users_phones_fk');
            $table->timestamps();
            $table->string('otp', 100)->nullable();
            $table->integer('otp_used')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_phones');
    }
};

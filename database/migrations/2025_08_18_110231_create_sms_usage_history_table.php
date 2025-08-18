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
        Schema::create('sms_usage_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('message')->nullable();
            $table->text('controller_details')->nullable();
            $table->unsignedBigInteger('institute_id')->nullable()->index('sms_usage_history_fk');
            $table->string('phone', 100)->nullable();
            $table->enum('sms_type', ['otp', 'sms'])->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_usage_history');
    }
};

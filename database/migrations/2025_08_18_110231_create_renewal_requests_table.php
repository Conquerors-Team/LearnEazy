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
        Schema::create('renewal_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('message');
            $table->timestamps();
            $table->unsignedInteger('institute_id')->default(0);
            $table->unsignedBigInteger('created_by_id')->nullable()->index('renewal_requests_fk');
            $table->unsignedInteger('message_id')->nullable()->index('renewal_requests_fk_1');
            $table->unsignedInteger('reply_id')->nullable()->index('renewal_requests_fk_2');
            $table->enum('replied', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renewal_requests');
    }
};

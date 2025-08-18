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
        Schema::create('alerts_enabled', function (Blueprint $table) {
            $table->unsignedInteger('alert_id')->nullable()->index('alerts_enabled_fk');
            $table->unsignedBigInteger('institute_id')->nullable()->index('alerts_enabled_fk_2');
            $table->char('batch_id', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts_enabled');
    }
};

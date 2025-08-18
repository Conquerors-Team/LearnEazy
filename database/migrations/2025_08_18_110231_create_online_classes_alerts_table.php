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
        Schema::create('online_classes_alerts', function (Blueprint $table) {
            $table->integer('class_id')->nullable();
            $table->integer('faculty_id')->nullable();
            $table->timestamps();
            $table->enum('notified_sms', ['yes', 'no'])->nullable()->default('no');
            $table->enum('notified_email', ['yes', 'no'])->nullable()->default('no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_classes_alerts');
    }
};

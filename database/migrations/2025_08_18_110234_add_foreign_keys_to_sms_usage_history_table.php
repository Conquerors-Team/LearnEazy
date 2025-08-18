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
        Schema::table('sms_usage_history', function (Blueprint $table) {
            $table->foreign(['institute_id'], 'sms_usage_history_FK')->references(['id'])->on('institutes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sms_usage_history', function (Blueprint $table) {
            $table->dropForeign('sms_usage_history_FK');
        });
    }
};

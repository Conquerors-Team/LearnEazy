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
        Schema::table('alerts_enabled', function (Blueprint $table) {
            $table->foreign(['alert_id'], 'alerts_enabled_FK')->references(['id'])->on('alerts')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['institute_id'], 'alerts_enabled_FK_2')->references(['id'])->on('institutes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts_enabled', function (Blueprint $table) {
            $table->dropForeign('alerts_enabled_FK');
            $table->dropForeign('alerts_enabled_FK_2');
        });
    }
};

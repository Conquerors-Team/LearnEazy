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
        Schema::table('lmsseries_lmsgroups', function (Blueprint $table) {
            $table->foreign(['lmsseries_id'], 'lmsseries_lmsgroups_FK')->references(['id'])->on('lmsseries')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['lmsgroups_id'], 'lmsseries_lmsgroups_FK_1')->references(['id'])->on('lmsgroups')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lmsseries_lmsgroups', function (Blueprint $table) {
            $table->dropForeign('lmsseries_lmsgroups_FK');
            $table->dropForeign('lmsseries_lmsgroups_FK_1');
        });
    }
};

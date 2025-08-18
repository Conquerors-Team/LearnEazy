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
        Schema::create('lmsseries_lmsgroups', function (Blueprint $table) {
            $table->unsignedBigInteger('lmsseries_id')->nullable()->index('lmsseries_lmsgroups_fk');
            $table->unsignedBigInteger('lmsgroups_id')->nullable()->index('lmsseries_lmsgroups_fk_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lmsseries_lmsgroups');
    }
};

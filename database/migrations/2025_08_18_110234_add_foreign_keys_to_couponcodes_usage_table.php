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
        Schema::table('couponcodes_usage', function (Blueprint $table) {
            $table->foreign(['user_id'], 'couponcodes_usage_ibfk_1')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['coupon_id'], 'couponcodes_usage_ibfk_2')->references(['id'])->on('couponcodes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couponcodes_usage', function (Blueprint $table) {
            $table->dropForeign('couponcodes_usage_ibfk_1');
            $table->dropForeign('couponcodes_usage_ibfk_2');
        });
    }
};

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
        Schema::table('permission_users', function (Blueprint $table) {
            $table->foreign(['permission_id'], 'permission_users_FK')->references(['id'])->on('permissions')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['user_id'], 'permission_users_FK_1')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_users', function (Blueprint $table) {
            $table->dropForeign('permission_users_FK');
            $table->dropForeign('permission_users_FK_1');
        });
    }
};

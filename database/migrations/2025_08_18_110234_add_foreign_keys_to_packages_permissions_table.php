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
        Schema::table('packages_permissions', function (Blueprint $table) {
            $table->foreign(['package_id'], 'packages_permissions_FK')->references(['id'])->on('packages')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['permission_id'], 'packages_permissions_FK_1')->references(['id'])->on('permissions')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages_permissions', function (Blueprint $table) {
            $table->dropForeign('packages_permissions_FK');
            $table->dropForeign('packages_permissions_FK_1');
        });
    }
};

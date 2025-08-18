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
        Schema::table('permission_institute', function (Blueprint $table) {
            $table->foreign(['institute_id'], 'permission_institute_FK')->references(['id'])->on('institutes')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['permission_id'], 'permission_institute_FK_1')->references(['id'])->on('permissions')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_institute', function (Blueprint $table) {
            $table->dropForeign('permission_institute_FK');
            $table->dropForeign('permission_institute_FK_1');
        });
    }
};

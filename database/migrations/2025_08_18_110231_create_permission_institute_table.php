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
        Schema::create('permission_institute', function (Blueprint $table) {
            $table->unsignedInteger('permission_id');
            $table->unsignedBigInteger('institute_id')->index('permission_role_role_id_foreign1');

            $table->primary(['permission_id', 'institute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_institute');
    }
};

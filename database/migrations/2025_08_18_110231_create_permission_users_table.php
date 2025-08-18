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
        Schema::create('permission_users', function (Blueprint $table) {
            $table->unsignedInteger('permission_id')->nullable()->index('permission_users_fk');
            $table->unsignedBigInteger('user_id')->nullable()->index('permission_users_fk_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_users');
    }
};

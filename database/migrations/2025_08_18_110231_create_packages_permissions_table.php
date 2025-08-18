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
        Schema::create('packages_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable()->index('packages_permissions_fk');
            $table->unsignedInteger('permission_id')->nullable()->index('packages_permissions_fk_1');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages_permissions');
    }
};

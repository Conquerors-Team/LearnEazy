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
        Schema::create('packages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 500)->nullable();
            $table->string('slug', 100)->nullable();
            $table->integer('number_of_logins')->nullable();
            $table->string('package_for', 50)->nullable()->default('institute');
            $table->timestamps();
            $table->enum('trail_available', ['yes', 'no'])->nullable()->default('no');
            $table->integer('trail_period_days')->nullable();
            $table->enum('is_default', ['yes', 'no'])->nullable()->default('no');
            $table->unsignedBigInteger('institute_id')->nullable()->index('packages_fk');
            $table->enum('duration_type', ['Day', 'Week', 'Month', 'Year'])->nullable()->default('Day');
            $table->integer('duration')->nullable()->default(10);
            $table->integer('cost')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->string('image', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

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
        Schema::create('student_classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->string('slug', 100)->unique('slug_unique');
            $table->tinyText('description')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('updated_at', 45)->nullable();
            $table->unsignedBigInteger('institute_id')->nullable()->index('student_classes_fk');
            $table->enum('status', ['Active', 'Inactive'])->nullable()->default('Active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_classes');
    }
};

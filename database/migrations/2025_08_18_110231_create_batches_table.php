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
        Schema::create('batches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->nullable();
            $table->integer('institute_id')->nullable();
            $table->string('name', 250)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('start_time', 20)->nullable();
            $table->string('end_time', 20)->nullable();
            $table->integer('capacity')->default(0);
            $table->decimal('fee_perhead', 10)->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->unsignedBigInteger('student_class_id')->nullable()->index('batches_fk');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->enum('enable_sms_alerts', ['yes', 'no'])->nullable()->default('no');
            $table->enum('enable_email_alerts', ['yes', 'no'])->nullable()->default('yes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

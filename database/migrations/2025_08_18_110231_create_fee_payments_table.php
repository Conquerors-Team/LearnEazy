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
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('batch_student_id')->index('batch_student_id');
            $table->integer('user_id')->nullable();
            $table->integer('batch_id')->nullable();
            $table->integer('institute_id')->nullable();
            $table->decimal('amount', 10)->nullable()->default(0);
            $table->decimal('paid_amount', 10)->default(0);
            $table->string('payment_method', 20)->nullable();
            $table->date('paid_date')->nullable();
            $table->decimal('discount', 10)->default(0);
            $table->decimal('balance', 10)->default(0);
            $table->text('comments')->nullable();
            $table->integer('added_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};

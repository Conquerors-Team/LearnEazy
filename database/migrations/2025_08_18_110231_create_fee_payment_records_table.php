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
        Schema::create('fee_payment_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('fee_payment_id')->index('fee_payment_id');
            $table->integer('user_id')->nullable();
            $table->integer('batch_id')->nullable();
            $table->integer('institute_id')->default(0);
            $table->decimal('amount', 10)->default(0);
            $table->decimal('paid_amount', 10)->default(0);
            $table->decimal('balance', 10)->default(0);
            $table->date('paid_date')->nullable();
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
        Schema::dropIfExists('fee_payment_records');
    }
};

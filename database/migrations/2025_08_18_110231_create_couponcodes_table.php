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
        Schema::create('couponcodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('slug');
            $table->string('coupon_code');
            $table->enum('discount_type', ['value', 'percent']);
            $table->decimal('discount_value', 10);
            $table->decimal('minimum_bill', 10);
            $table->decimal('discount_maximum_amount', 10);
            $table->date('valid_from');
            $table->date('valid_to');
            $table->integer('usage_limit');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->text('description');
            $table->text('coupon_code_applicability')->nullable();
            $table->integer('record_updated_by');
            $table->timestamps();
            $table->integer('institute_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couponcodes');
    }
};

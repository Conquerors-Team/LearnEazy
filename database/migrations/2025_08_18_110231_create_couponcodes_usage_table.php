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
        Schema::create('couponcodes_usage', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('item_id');
            $table->string('item_type', 50);
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->decimal('item_cost', 10);
            $table->decimal('total_invoice_amount', 10);
            $table->decimal('discount_amount', 10);
            $table->unsignedBigInteger('coupon_id')->index('coupon_id');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couponcodes_usage');
    }
};

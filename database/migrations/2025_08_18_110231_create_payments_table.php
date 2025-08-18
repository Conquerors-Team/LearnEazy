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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 100);
            $table->integer('item_id');
            $table->string('item_name', 50);
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('plan_type', ['combo', 'lms', 'exam', 'other', 'package', 'paidcontent']);
            $table->string('payment_gateway');
            $table->string('transaction_id');
            $table->boolean('paid_by_parent')->default(false);
            $table->string('paid_by');
            $table->decimal('cost', 10);
            $table->tinyInteger('coupon_applied')->default(0);
            $table->integer('coupon_id');
            $table->decimal('actual_cost', 10);
            $table->decimal('discount_amount', 10);
            $table->decimal('after_discount', 10);
            $table->decimal('paid_amount', 10);
            $table->string('payment_status');
            $table->text('other_details');
            $table->text('transaction_record')->nullable();
            $table->text('notes');
            $table->text('admin_comments')->nullable();
            $table->timestamps();
            $table->integer('institute_id')->nullable()->default(0);
            $table->boolean('subscribe_onlineclasses')->nullable()->default(false);
            $table->decimal('online_classes_price', 10)->nullable();
            $table->boolean('notification_closed')->nullable()->default(false)->comment('Used to display trail period notifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

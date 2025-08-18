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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->index('id');
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('slug', 50);
            $table->boolean('login_enabled')->default(true);
            $table->integer('role_id');
            $table->integer('parent_id')->nullable();
            $table->string('image');
            $table->string('phone', 50)->nullable();
            $table->text('address')->nullable();
            $table->boolean('stripe_active')->default(false);
            $table->string('stripe_id', 250)->nullable();
            $table->string('stripe_plan', 30)->nullable();
            $table->string('paypal_email', 50)->nullable();
            $table->string('card_brand', 50)->nullable();
            $table->string('card_last_four', 50)->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->rememberToken();
            $table->text('settings')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->integer('institute_id')->default(0);
            $table->integer('branch_id')->default(0);
            $table->integer('added_by')->nullable();
            $table->string('activation_code', 100)->nullable();
            $table->boolean('is_verified')->nullable()->default(false);
            $table->unsignedBigInteger('student_class_id')->nullable()->index('users_fk');
            $table->string('phone_code', 100)->nullable();
            $table->string('otp', 100)->nullable();
            $table->integer('otp_used')->nullable()->default(0);
            $table->boolean('is_mobile_verified')->nullable()->default(false);
            $table->timestamp('last_login')->nullable();
            $table->unsignedBigInteger('board_id')->nullable()->index('users_fk_1');
            $table->unsignedBigInteger('course_id')->nullable()->index('users_fk2');
            $table->string('online_url', 500)->nullable();
            $table->text('white_board_code')->nullable();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->dateTime('valid_until')->nullable();
            $table->enum('assigned', ['yes', 'no', 'registered'])->nullable()->default('yes');
            $table->timestamp('trial_until')->nullable();
            $table->enum('is_loggedin', ['yes', 'no'])->nullable()->default('no');
            $table->string('last_session', 500)->nullable();

            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

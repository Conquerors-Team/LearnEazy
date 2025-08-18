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
        Schema::create('institutes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->integer('parent_id')->default(0);
            $table->string('institute_name', 250)->nullable();
            $table->text('institute_address')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('comments')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('fax', 15)->nullable();
            $table->string('web_site')->nullable();
            $table->string('logo', 50)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
            $table->date('valid_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};

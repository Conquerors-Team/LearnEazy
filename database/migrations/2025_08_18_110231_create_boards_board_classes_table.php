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
        Schema::create('boards_board_classes', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id')->nullable()->index('boards_board_classes_fk');
            $table->unsignedBigInteger('board_class_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards_board_classes');
    }
};

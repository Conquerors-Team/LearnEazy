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
        Schema::table('renewal_requests', function (Blueprint $table) {
            $table->foreign(['created_by_id'], 'renewal_requests_FK')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['message_id'], 'renewal_requests_FK_1')->references(['id'])->on('messenger_threads')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['reply_id'], 'renewal_requests_FK_2')->references(['id'])->on('messenger_threads')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('renewal_requests', function (Blueprint $table) {
            $table->dropForeign('renewal_requests_FK');
            $table->dropForeign('renewal_requests_FK_1');
            $table->dropForeign('renewal_requests_FK_2');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('messages', 'sender_role')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->string('sender_role')->nullable()->after('sender_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('messages', 'sender_role')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropColumn('sender_role');
            });
        }
    }
};

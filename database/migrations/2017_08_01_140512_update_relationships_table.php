<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRelationshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->boolean('status')->default(0)->after('following_id');
            $table->renameColumn('user_id', 'sender_id');
            $table->renameColumn('following_id', 'recipient_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relationships', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->renameColumn('sender_id', 'user_id');
            $table->renameColumn('recipient_id', 'following_id');
        });
    }
}

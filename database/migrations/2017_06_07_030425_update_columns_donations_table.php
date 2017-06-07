<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateColumnsDonationsTable extends Migration
{
    public function up()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->integer('campaign_id')->index()->after('user_id');
            $table->integer('goal_id')->index()->after('event_id');
            $table->dropColumn('donation_type_id');
        });
    }

    public function down()
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('campaign_id');
            $table->dropColumn('goal_id');
            $table->integer('donation_type_id')->after('value');
        });
    }
}

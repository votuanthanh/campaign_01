<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->renameColumn('target_id', 'likeable_id');
            $table->renameColumn('target_type', 'likeable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->renameColumn('likeable_id', 'target_id');
            $table->renameColumn('likeable_type', 'target_type');
        });
    }
}

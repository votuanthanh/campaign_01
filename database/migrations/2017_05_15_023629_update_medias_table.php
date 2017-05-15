<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->renameColumn('target_id', 'mediable_id');
            $table->renameColumn('target_type', 'mediable_type');
            $table->dropColumn([
                'user_id',
                'caption',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medias', function (Blueprint $table) {
            $table->renameColumn('mediable_id', 'target_id');
            $table->renameColumn('mediable_type', 'target_type');
            $table->integer('user_id')->index();
            $table->string('caption')->nullable();
        });
    }
}

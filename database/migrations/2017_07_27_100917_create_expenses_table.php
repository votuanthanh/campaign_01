<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\DatabaseManager;

class CreateExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('event_id');
            $table->integer('goal_id');
            $table->float('cost', 11, 2);
            $table->dateTime('time')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('reason')->nullable();
            $table->boolean('type');
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}

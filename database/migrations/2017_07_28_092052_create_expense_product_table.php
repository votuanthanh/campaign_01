<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseProductTable extends Migration
{
    public function up()
    {
        Schema::create('expense_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expense_id')->index();
            $table->integer('product_id')->index();
            $table->integer('quality_id');
            $table->float('quantity', 11, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expense_product');
    }
}

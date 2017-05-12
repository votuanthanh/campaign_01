<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_email_unique');
            $table->string('email')->nullable()->change();
            $table->string('birthday')->nullable()->after('password');
            $table->string('address')->nullable()->after('birthday');
            $table->string('phone')->nullable()->after('address');
            $table->string('avatar')->nullable()->after('phone');
            $table->tinyInteger('status')->after('avatar');
            $table->string('token_confirm')->nullable()->after('status');
            $table->softDeletes()->after('token_confirm');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->change();
            $table->dropColumn([
                'birthday',
                'address',
                'phone',
                'avatar',
                'status',
                'token_confirm',
                'deleted_at',
            ]);
        });
    }
}

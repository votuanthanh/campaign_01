<?php

use Illuminate\Database\Seeder;
use App\Models\SocialAccount;

class SocialAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SocialAccount::truncate();
    }
}

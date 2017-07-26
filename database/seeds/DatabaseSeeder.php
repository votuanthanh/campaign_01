<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;
use App\Models\Activity;
use App\Models\Like;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::truncate();
        Activity::truncate();
        Like::truncate();

        $this->call(SocialAccountTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CampaignTableSeeder::class);
        //$this->call(ActionTableSeeder::class);
        $this->call(QualityTableSeeder::class);
        $this->call(DonationTypeTableSeeder::class);
        $this->call(GoalTableSeeder::class);
        $this->call(DonationTableSeeder::class);
    }
}

<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TypeTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(RelationshipTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(CampaignTableSeeder::class);
        $this->call(EventTableSeeder::class);
        $this->call(ActionTableSeeder::class);
        $this->call(QualityTableSeeder::class);
        $this->call(DonationTypeTableSeeder::class);
        $this->call(DonationTableSeeder::class);
    }
}

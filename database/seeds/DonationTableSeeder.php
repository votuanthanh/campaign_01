<?php

use Illuminate\Database\Seeder;
use App\Models\Donation;

class DonationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Donation::truncate();
        factory(Donation::class, 10)->create();
    }
}

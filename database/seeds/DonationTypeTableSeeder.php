<?php

use Illuminate\Database\Seeder;
use App\Models\DonationType;

class DonationTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DonationType::truncate();
        factory(DonationType::class, 10)->create();
    }
}

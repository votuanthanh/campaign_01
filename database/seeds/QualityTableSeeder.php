<?php

use Illuminate\Database\Seeder;
use App\Models\Quality;

class QualityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Quality::truncate();
        factory(Quality::class, 10)->create();
    }
}

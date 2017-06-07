<?php

use App\Models\Goal;
use Illuminate\Database\Seeder;

class GoalTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Goal::truncate();
        factory(Goal::class, 20)->create();
    }
}

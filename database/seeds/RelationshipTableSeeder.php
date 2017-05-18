<?php

use Illuminate\Database\Seeder;
use App\Models\Relationship;

class RelationshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Relationship::truncate();
        factory(Relationship::class, 10)->create();
    }
}

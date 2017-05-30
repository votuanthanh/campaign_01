<?php

use Illuminate\Database\Seeder;
use App\Models\Type;

class TypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::truncate();
        $types = [
            'Site',
            'Campaign',
        ];

        foreach ($types as $value) {
            factory(Type::class)->create([
                'name' => $value,
            ]);
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        \DB::table('role_user')->truncate();
        \DB::table('campaign_user')->truncate();

        factory(User::class, 20)->create()->each(function ($user) {
            $roleIds = Role::all()->pluck('id')->toArray();
            $campaignIds = Campaign::all()->pluck('id')->toArray();

            foreach (range(1, 4) as $key) {
                $array[array_rand($campaignIds)] = [
                    'role_id' => array_rand($roleIds),
                ];
            }

            $user->roles()->attach(array_rand($roleIds, rand(1, 3)));
            $user->Campaigns()->attach($array);
        });
        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'status' => 1,
        ]);
    }
}

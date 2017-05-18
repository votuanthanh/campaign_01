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
        \DB::table('relationships')->truncate();
        factory(User::class, 20)->create()->each(function ($user) {
            $roleIds = Role::all()->pluck('id')->toArray();
            $userIds = User::where('id', '<>', $user->id)
                ->pluck('id')->toArray();
            $user->roles()->attach(array_rand($roleIds, rand(1, 3)));
            $user->followings()->attach(array_rand($userIds, rand(1, 3)));
        });

        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'status' => 1,
        ]);
    }
}

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

        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'status' => 1,
        ])->roles()->attach([1]);

        factory(User::class, 20)->create()->each(function ($user) {
            $roleIds = Role::all()->pluck('id')->toArray();
            $userIds = User::where('id', '<>', $user->id)->pluck('id')->toArray();
            $user->roles()->attach($this->getRandomElement($roleIds));
            $user->followings()->attach($this->getRandomElement($userIds));
        });
    }

    public function getRandomElement($array)
    {
        $arrayRoles = [];
        $tmp = array_rand($array, rand(2, 4));

        foreach ($tmp as $item) {
            $arrayRoles[] = $array[$item];
        }

        return $arrayRoles;
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Tag;
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
        \DB::table('tag_user')->truncate();

        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'status' => 1,
        ])->roles()->attach([1]);

        factory(User::class, 20)->create()->each(function ($user) {
            $roleIds = Role::pluck('id')->toArray();
            $tagIds = Tag::pluck('id')->toArray();
            $userIds = User::where('id', '<>', $user->id)->pluck('id')->toArray();
            $user->roles()->attach($this->getRandomElement($roleIds, 2, 4));
            $user->followings()->attach($this->getRandomElement($userIds, 4, 19));
            $user->tags()->attach($this->getRandomElement($tagIds, 2, 4));
        });
    }

    public function getRandomElement($array, $start, $end)
    {
        $arrayRoles = [];
        $tmp = array_rand($array, rand($start, $end));

        foreach ($tmp as $item) {
            $arrayRoles[] = $array[$item];
        }

        return $arrayRoles;
    }
}

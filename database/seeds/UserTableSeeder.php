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

        // Create user admin and set role for admin
        $idRoleAdmin = Role::ofRole(Role::TYPE_SYSTEM, Role::ROLE_ADMIN)->get();

        factory(User::class)
            ->states(Role::ROLE_ADMIN)
            ->create()
            ->roles()
            ->attach($idRoleAdmin);

        factory(User::class, 20)->create()->each(function ($user) {
            $idRoleUser = Role::ofRole(Role::TYPE_SYSTEM, Role::ROLE_USER)->get();

            $tagIds = Tag::pluck('id')->toArray();
            $userIds = User::where('id', '<>', $user->id)->pluck('id')->toArray();
            $user->roles()->attach($idRoleUser);
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

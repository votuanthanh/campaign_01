<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;

class CampaignTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campaign::truncate();
        \DB::table('campaign_tag')->truncate();
        \DB::table('campaign_user')->truncate();
        factory(Campaign::class, 10)->create()->each(function ($campaign) {
            $tagIds = App\Models\Tag::all()->pluck('id');
            $userIds = User::all()->pluck('id');
            $roleIds = Role::all()->pluck('id');

            foreach (range(1, 4) as $key) {
                $array[$userIds->random()] = [
                    'role_id' => $roleIds->random(),
                    'status' => rand(1, 3),
                ];
            }

            foreach (range(1, 2) as $key) {
                $like[] = factory(App\Models\Like::class)->make()->toArray();
                $setting[] = factory(App\Models\Setting::class)->make()->toArray();
                $media[] = factory(App\Models\Media::class)->make();
            }

            $campaign->tags()->attach($tagIds->random(rand(1, 3)));
            $campaign->likes()->createMany($like);
            $campaign->settings()->createMany($setting);
            $campaign->media()->saveMany($media);
            $campaign->users()->attach($array);
        });
    }
}

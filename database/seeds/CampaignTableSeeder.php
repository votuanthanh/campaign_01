<?php

use Illuminate\Database\Seeder;
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
        factory(Campaign::class, 10)->create()->each(function ($campaign) {
            $tagIds = App\Models\Tag::all()->pluck('id')->toArray();
            $campaign->tags()->attach(array_rand($tagIds, rand(1, 3)));

            foreach (range(1, 2) as $key) {
                $like[] = factory(App\Models\Like::class)->make()->toArray();
                $setting[] = factory(App\Models\Setting::class)->make()->toArray();
                $media[] = factory(App\Models\Media::class)->make()->toArray();
            }

            $campaign->likes()->createMany($like);
            $campaign->settings()->createMany($setting);
            $campaign->media()->createMany($media);
        });
    }
}

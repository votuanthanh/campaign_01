<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;
use App\Models\Event;
use App\Models\Like;
use App\Models\Setting;
use App\Models\Media;
use App\Models\Comment;

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
        Event::truncate();
        Media::truncate();
        Comment::truncate();
        \DB::table('campaign_tag')->truncate();
        \DB::table('campaign_user')->truncate();
        \DB::table('events')->truncate();

        factory(Campaign::class, 10)->create()->each(function ($campaign) {
            $range = rand(2, 5);

            // set role when join in campaign
            $campaign->users()->attach($this->setRoleUserJoinCampaign());

            // Create Tag for a campaign
            $tagIds = App\Models\Tag::all()->pluck('id');
            $campaign->tags()->attach($tagIds->random(rand(1, 3)));

            // Save many like for campaign
            $campaign->likes()->saveMany(factory(Like::class, $range)->make());

            // Set settings for campaign
            $campaign->settings()->saveMany(factory(Setting::class, $range)->make());

            // Save photo for campaign
            $campaign->media()->saveMany(factory(Media::class, $range)->make());

            $this->createEvents($campaign);
        });
    }

    public function setRoleUserJoinCampaign()
    {
        return User::inRandomOrder()
            ->get()
            ->reduce(function ($carry, $user) {
                $roleCampagin = $this->roleCampaign();

                if ($carry && $this->hasOwnerCampaign($carry)) {
                    $roleCampagin = $this->notRoleOwnerCampaign();
                }

                $carry[$user->id] = [
                    'role_id' => $roleCampagin->id,
                    'status' => $user->id % 5 == 0 ? 0 : 1,
                ];

                return $carry;
            });
    }

    public function createEvents($campaign)
    {
        $userApproved = $campaign->users()
            ->wherePivot('status', 1)
            ->where(function ($query) {
                $idRoleOwner = $this->idBossCampaign(Role::ROLE_OWNER);
                $idRoleModerator = $this->idBossCampaign(Role::ROLE_MODERATOR);

                $query->whereIn('campaign_user.role_id', [$idRoleOwner, $idRoleModerator]);
            })
            ->inRandomOrder()
            ->get();

        foreach (range(0, rand(0, 10)) as $temp) {
            $dataEvent = factory(Event::class)->make([
                'user_id' => $userApproved->random()->id,
            ]);

            $event = $campaign->events()->save($dataEvent);
            $event->comments()->saveMany(factory(Comment::class, rand(1, 10))->make());
            $event->likes()->saveMany(factory(Like::class, rand(1, 20))->make());
            $event->media()->saveMany(factory(Media::class, rand(1, 5))->make());
            $event->settings()->saveMany(factory(Setting::class, rand(1, 5))->make());
        }
    }

    public function hasOwnerCampaign($data)
    {
        return collect($data)->contains(function ($data) {
            return $data['role_id'] == $this->idBossCampaign(ROLE::ROLE_OWNER);
        });
    }

    public function idBossCampaign($type)
    {
        return Role::ofRole(Role::TYPE_CAMPAIGN, $type)->first()->id;
    }

    public function roleCampaign()
    {
        return Role::whereType(Role::TYPE_CAMPAIGN)
            ->inRandomOrder()
            ->first();
    }

    public function notRoleOwnerCampaign()
    {
        return Role::whereType(Role::TYPE_CAMPAIGN)
            ->where('name', '<>', Role::ROLE_OWNER)
            ->inRandomOrder()
            ->first();
    }
}

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
use App\Models\Activity;
use App\Models\Action;

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
        Action::truncate();
        \DB::table('campaign_tag')->truncate();
        \DB::table('campaign_user')->truncate();
        \DB::table('events')->truncate();

        factory(Campaign::class, 10)->create()->each(function ($campaign) {
            $range = rand(2, 5);

            // set role when join in campaign
            $userJoin = $this->setRoleUserJoinCampaign();
            $campaign->users()->attach($userJoin);

            // Create joining activities for user taking in campaign
            $campaign->activities()->saveMany($this->initialUserJoin($userJoin));

            // Create activity's user that store campaign
            $this->createActivityOwner($campaign);

            // Create Tag for a campaign
            $tagIds = App\Models\Tag::all()->pluck('id');
            $campaign->tags()->attach($tagIds->random(rand(1, 3)));

            // Save many like for campaign
            $this->likeTarget($campaign, $userJoin);

            // Set settings for campaign
            $campaign->settings()->saveMany(factory(Setting::class, $range)->make());

            // Save photo for campaign
            $campaign->media()->saveMany(factory(Media::class, $range)->make());

            $this->createEvents($campaign, $userJoin);
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

    public function createEvents($campaign, $userJoin)
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
            $ownerEvent = $userApproved->random()->id;

            $dataEvent = factory(Event::class)->make([
                'user_id' => $ownerEvent,
            ]);

            $event = $campaign->events()->save($dataEvent);
            $event->activities()->create([
                'user_id' => $ownerEvent,
                'name' => Activity::CREATE,
            ]);

            $event->comments()->saveMany(factory(Comment::class, rand(1, 10))->make());
            $this->likeTarget($event, $userJoin);
            $event->media()->saveMany(factory(Media::class, rand(1, 5))->make());
            $event->settings()->saveMany(factory(Setting::class, rand(1, 5))->make());

            // Create Action For Event
            $ownerAction = $campaign->users()
                ->wherePivot('status', 1)
                ->inRandomOrder()
                ->get();

            // Each action belongs to user that random top $ownerAction
            foreach (range(0, rand(1, 5)) as $tempAction) {
                $ownerId = $ownerAction->random()->id;

                $actions = $this->createActionOfEvent($event, $ownerId);

                $actions->each(function ($action) use ($ownerId, $userJoin) {
                    $action->activities()->create([
                        'user_id' => $ownerId,
                        'name' => Activity::CREATE,
                    ]);

                    $action->media()->saveMany(factory(Media::class, rand(1, 10))->make());
                    $action->comments()->saveMany(factory(Comment::class, rand(1, 10))->make());
                    $this->likeTarget($action, $userJoin);
                });
            }
        }
    }

    public function createActionOfEvent($event, $ownerId)
    {
        $actions = factory(Action::class, rand(1, 5))->make(['user_id' =>  $ownerId]);

        return $event->actions()->saveMany($actions);
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

    public function initialUserJoin($userJoin)
    {
        return collect($userJoin)
            ->keys()
            ->map(function ($userId) {
                return new Activity([
                    'user_id' => $userId,
                    'name' => Activity::JOIN
                ]);
            });
    }

    public function createActivityOwner($campaign)
    {
        $owner = $campaign->users()->wherePivot('role_id', $this->idBossCampaign(Role::ROLE_OWNER))->first();
        $campaign->activities()->create(['user_id' => $owner->id, 'name' => Activity::CREATE]);
    }

    public function likeTarget($target, $userJoin)
    {
        return collect($userJoin)->keys()
            ->shuffle()
            ->take(rand(5, 20))
            ->each(function ($userId) use ($target) {
                $target->likes()->save(factory(Like::class)->make([
                    'user_id' => $userId,
                ]));
            });
    }
}

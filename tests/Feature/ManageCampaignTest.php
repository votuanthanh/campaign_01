<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Repositories\Eloquent\RoleRepository;

class ManageCampaignTest extends TestCase
{
    use DatabaseTransactions;

    public function testChangeRoleWithUnauthorizedThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $owner = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $ownerId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)
            ->id;
        $campaign->users()->attach([
            $owner->id => ['role_id' => $ownerId],
        ]);
        $this->actingAs($user, 'api');
        $response = $this->json('PATCH', route('campaign.change-role'), [
            'user_id' => 1,
            'campaign_id' => $campaign->id,
            'role_id' => 4,
        ]);

        $response->assertJsonFragment([
                'http_status' => [
                    'code' => INTERNAL_SERVER_ERROR,
                    'message' => 'This action is unauthorized.',
                    'status' => false,
                ],
            ]);
    }

    public function testChangeRoleWithInvalidDataThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $ownerId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)
            ->id;
        $campaign->users()->attach([
            $user->id => ['role_id' => $ownerId],
        ]);
        $this->actingAs($user, 'api');
        $response = $this->json('PATCH', route('campaign.change-role'), [
            'user_id' => 123,
            'campaign_id' => $campaign->id,
            'role_id' => 4,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR)
            ->assertExactJson([
                'http_status' => [
                    'code' => INTERNAL_SERVER_ERROR,
                    'message' => 'Invalid data input',
                    'status' => false,
                ],
            ]);
    }

    public function testChangeRoleThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $member = factory(User::class)->create();
        $owner = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $ownerId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)
            ->id;
        $memberId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_MEMBER, Role::TYPE_CAMPAIGN)
            ->id;
        $campaign->users()->attach([
            $owner->id => ['role_id' => $ownerId],
            $member->id => ['role_id' => $memberId],
        ]);
        $this->actingAs($owner, 'api');
        $response = $this->json('PATCH', route('campaign.change-role'), [
            'user_id' => $member->id,
            'campaign_id' => $campaign->id,
            'role_id' => 6,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testRemoveUserWithUnauthorizedThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $owner = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $ownerId = app(\App\Repositories\Eloquent\RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)
            ->id;
        $campaign->users()->attach([
            $owner->id => ['role_id' => $ownerId],
        ]);
        $this->actingAs($user, 'api');
        $response = $this->json('PATCH', route('campaign.remove-user'), [
            'user_id' => 1,
            'campaign_id' => $campaign->id,
        ]);

        $response->assertExactJson([
                'http_status' => [
                    'code' => INTERNAL_SERVER_ERROR,
                    'message' => 'This action is unauthorized.',
                    'status' => false,
                ],
            ]);
    }

    public function testRemoveUserThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $member = factory(User::class)->create();
        $owner = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $ownerId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)
            ->id;
        $memberId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_MEMBER, Role::TYPE_CAMPAIGN)
            ->id;
        $campaign->users()->attach([
            $owner->id => ['role_id' => $ownerId],
            $member->id => ['role_id' => $memberId],
        ]);
        $this->actingAs($owner, 'api');
        $response = $this->json('PATCH', route('campaign.remove-user'), [
            'user_id' => $member->id,
            'campaign_id' => $campaign->id,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testChangeOwnerThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $member = factory(User::class)->create();
        $owner = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create();
        $ownerId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)
            ->id;
        $memberId = app(RoleRepository::class)
            ->findRoleOrFail(Role::ROLE_MEMBER, Role::TYPE_CAMPAIGN)
            ->id;
        $campaign->users()->attach([
            $owner->id => ['role_id' => $ownerId],
            $member->id => ['role_id' => $memberId],
        ]);
        $this->actingAs($owner, 'api');
        $response = $this->json('PATCH', route('campaign.change-owner'), [
            'user_id' => $member->id,
            'campaign_id' => $campaign->id,
            'role_id' => 6,
        ]);

        $response->assertStatus(CODE_OK);
    }
}

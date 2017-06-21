<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ShowCampaignTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test show campaign with authorize then success.
     *
     * @return void
     */
    public function testShowCampaignWithAuthorizeThenSuccess()
    {
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create(['hashtag' => 'Default', 'status' => Campaign::ACTIVE]);
        $roleOwnerId = Role::where('type', Role::TYPE_CAMPAIGN)->where('name', Role::ROLE_OWNER)->pluck('id')->first();
        $campaign->users()->attach([
            $user->id => [
                'role_id' => $roleOwnerId,
            ],
        ]);

        $this->actingAs($user, 'api');
        $response = $this->json('GET', route('campaign.show', ['id' => $campaign->id]), [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    /**
     * test show campaign with authorize then fail.
     *
     * @return void
     */
    public function testShowCampaignWithAuthorizeThenFail()
    {
        $user = factory(User::class)->create();
        $campaign = factory(Campaign::class)->create(['hashtag' => 'Default', 'status' => Campaign::BLOCK]);
        $roleOwnerId = Role::where('type', Role::TYPE_CAMPAIGN)->where('name', Role::ROLE_OWNER)->pluck('id')->first();
        $campaign->users()->attach([
            '1' => [
                'role_id' => $roleOwnerId,
            ],
        ]);

        $this->actingAs($user, 'api');
        $response = $this->json('GET', route('campaign.show', ['id' => $campaign->id]), [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(UNAUTHORIZED);
    }
}

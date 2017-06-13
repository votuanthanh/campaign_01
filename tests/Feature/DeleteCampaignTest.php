<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteCampaignTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * test delete campaign success.
     *
     * @return void 
     */
    public function testDeleteCampaignSuccessWithAuthorized()
    {
        $user = factory(User::class)->create();
        $userId = $user->id;
        $campaign = factory(Campaign::class)->create(['hashtag' => 'Default']);
        $roleOwnerId = Role::where('type', Role::TYPE_CAMPAIGN)->where('name', Role::ROLE_OWNER)->pluck('id')->first(); 
        $campaign->users()->attach([
            $userId => [
                'role_id' => $roleOwnerId,
            ],
        ]);

        $this->actingAs($user, 'api');
        $response = $this->json('DELETE', route('campaign.delete'), [
            'id' => $campaign->id,
        ],[
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);
        
        $response->assertStatus(CODE_OK);
    }

     /**
     * test delete campaign fail.
     *
     * @return void 
     */
    public function testDeleteCampaignFailWithAuthorized()
    {
        $user = factory(User::class)->create();
        $userId = $user->id;
        $campaign = factory(Campaign::class)->create(['hashtag' => 'Default']);
        $roleCampaignIds = Role::where('type', Role::TYPE_CAMPAIGN)->pluck('id', 'name')->toArray(); 
        $campaign->users()->attach([
            $userId => [
                'role_id' => $roleCampaignIds[Role::ROLE_MEMBER],
            ],
            '1' => [
                'role_id' => $roleCampaignIds[Role::ROLE_OWNER],
            ],
        ]);

        $this->actingAs($user, 'api');
        $response = $this->json('DELETE', route('campaign.delete'), [
            'id' => $campaign->id,
        ],[
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(UNAUTHORIZED);
    }

     /**
     * test delete campaign fail.
     *
     * @return void 
     */
    public function testDeleteCampaignFailWithCampaignNotFound()
    {
        $user = factory(User::class)->create();
        $userId = $user->id;
        $campaign = factory(Campaign::class)->create(['hashtag' => 'Default']);
        $roleCampaignIds = Role::where('type', Role::TYPE_CAMPAIGN)->pluck('id', 'name')->toArray(); 
        $campaign->users()->attach([
            $userId => [
                'role_id' => $roleCampaignIds[Role::ROLE_MEMBER],
            ],
            '1' => [
                'role_id' => $roleCampaignIds[Role::ROLE_OWNER],
            ],
        ]);

        $this->actingAs($user, 'api');
        $response = $this->json('DELETE', route('campaign.delete'), [
            'id' => 1000,
        ],[
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);
        
        $response->assertStatus(NOT_FOUND);
    }
}   

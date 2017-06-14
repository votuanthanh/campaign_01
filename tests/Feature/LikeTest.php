<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Action;
use App\Models\Event;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Role;
use App\Models\Like;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LikeTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLikeActionSuccess()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#likeCampaign', 'status' => Campaign::ACTIVE]);
        $event = factory(Event::class)->create(['campaign_id' => $campaign->id]);
        $action = factory(Action::class)->create(['event_id' => $event->id]);
        $user = factory(User::class)->create(['email' => 'test.like.action@gmail.com']);
        $campaign->users()->attach($user->id, [
            'role_id' => Role::where(['name' => Role::ROLE_MEMBER, 'type' => Role::TYPE_CAMPAIGN])->first(['id']),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->json('post', route('action.like', ['actionId' => $action->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUnLikeActionSuccess()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#likeCampaign', 'status' => Campaign::ACTIVE]);
        $event = factory(Event::class)->create(['campaign_id' => $campaign->id]);
        $action = factory(Action::class)->create(['event_id' => $event->id]);
        $user = factory(User::class)->create(['email' => 'test.like.action@gmail.com']);
        $campaign->users()->attach($user->id, [
            'role_id' => Role::where(['name' => Role::ROLE_MEMBER, 'type' => Role::TYPE_CAMPAIGN])->first(['id']),
        ]);

        $this->actingAs($user, 'api');
        $like = $user->likes()->create([
            'user_id' => $user->id,
            'likeable_id' => $action->id,
            'likeable_type' => Action::class,
        ]);

        $response = $this->json('post', route('action.like', ['actionId' => $action->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertExactJson([
            'action' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUnLikeEventSuccess()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#likeCampaign', 'status' => Campaign::ACTIVE]);
        $event = factory(Event::class)->create(['campaign_id' => $campaign->id]);
        $user = factory(User::class)->create(['email' => 'test.ublike.event@gmail.com']);
        $campaign->users()->attach($user->id, [
            'role_id' => Role::where(['name' => Role::ROLE_MEMBER, 'type' => Role::TYPE_CAMPAIGN])->first(['id']),
        ]);

        $this->actingAs($user, 'api');
        $like = $user->likes()->create([
            'user_id' => $user->id,
            'likeable_id' => $event->id,
            'likeable_type' => Event::class,
        ]);

        $response = $this->json('post', route('event.like', ['eventId' => $event->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertExactJson([
            'event' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testLikeEventSuccess()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#likeCampaign', 'status' => Campaign::ACTIVE]);
        $event = factory(Event::class)->create(['campaign_id' => $campaign->id]);
        $user = factory(User::class)->create(['email' => 'test.like.event@gmail.com']);
        $user->campaigns()->attach($campaign->id, [
            'role_id' => Role::where(['name' => Role::ROLE_MEMBER, 'type' => Role::TYPE_CAMPAIGN])->first(['id']),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->json('post', route('event.like', ['eventId' => $event->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testLikeCampaignSuccess()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#likeCampaign', 'status' => Campaign::ACTIVE]);
        $user = factory(User::class)->create(['email' => 'test.like.campaign@gmail.com']);
        $user->campaigns()->attach($campaign->id, [
            'role_id' => Role::where(['name' => Role::ROLE_MEMBER, 'type' => Role::TYPE_CAMPAIGN])->first(['id']),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->json('post', route('campaign.like', ['campaignId' => $campaign->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUnLikeCampaignSuccess()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#likedemo', 'status' => Campaign::ACTIVE]);
        $user = factory(User::class)->create(['email' => 'test.unlike.campaign@gmail.com']);
        $campaign->users()->attach($user->id, [
            'role_id' => Role::where(['name' => Role::ROLE_MEMBER, 'type' => Role::TYPE_CAMPAIGN])->first(['id']),
        ]);

        $this->actingAs($user, 'api');
        $like = $user->likes()->create([
            'user_id' => $user->id,
            'likeable_id' => $campaign->id,
            'likeable_type' => Campaign::class,
        ]);

        $response = $this->json('post', route('campaign.like', ['campaignId' => $campaign->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertExactJson([
            'campaign' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUserNotHavePermissionThenFail()
    {
        $campaign = factory(Campaign::class)->create(['hashtag' => '#cantlike', 'status' => Campaign::ACTIVE]);
        $campaign->settings()->create([
            'key' => config('settings.campaigns.status'),
            'value' => config('settings.value_of_settings.status.private'),
        ]);
        $user = factory(User::class)->create(['email' => 'test.nothavepermission@gmail.com']);
        $user->roles()->attach($user->id, [
            'role_id' => Role::where(['name' => Role::ROLE_USER, 'type' => Role::TYPE_SYSTEM])->first(['id']),
        ]);

        $this->actingAs($user, 'api');

        $response = $this->json('post', route('campaign.like', ['campaignId' => $campaign->id]), [], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Event;
use App\Models\Campaign;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateEventTest extends TestCase
{
    use DatabaseTransactions;

    public function testCreateEventWithOwnerThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $campaign = Campaign::find(4);
        $roleCampaign = Role::where('name', Role::ROLE_OWNER)->where('type', Role::TYPE_CAMPAIGN)->first();
        $campaign->users()->attach([
            $user->id => [
                'role_id' => $roleCampaign->id,
            ],
        ]);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('event.create'), [
            'title' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude,
            'latitude' => $faker->latitude,
            'campaign_id' => '4',
            'settings' => [
                1 => '15/3/2017',
                2 => '30/3/2017',
            ],
            'donations' => [
                1 => [
                  'type' => 'rice',
                  'goal' => '20',
                  'quality' => 'kg',
                ],
                2 => [
                  'type' => 'bạc',
                  'goal' => '100',
                  'quality' => 'gam',
                ],
            ],
            'files' => [
                UploadedFile::fake()->image('avatar.jpg', 600, 600),
            ],
        ], [
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

    public function testCreateEventWithAdminThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = User::find(1);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('event.create'), [
            'title' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude,
            'latitude' => $faker->latitude,
            'campaign_id' => '4',
            'settings' => [
                1 => '15/3/2017',
                2 => '30/3/2017',
            ],
            'donations' => [
                1 => [
                  'type' => 'rice',
                  'goal' => '20',
                  'quality' => 'kg',
                ],
                2 => [
                  'type' => 'bạc',
                  'goal' => '100',
                  'quality' => 'gam',
                ],
            ],
            'files' => [
                UploadedFile::fake()->image('avatar.jpg', 600, 600),
            ],
        ], [
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

    public function testCreateEventDoNotOwnerThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('event.create'), [
            'title' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude,
            'latitude' => $faker->latitude,
            'campaign_id' => '4',
            'settings' => [
                1 => '15/3/2017',
                2 => '30/3/2017',
            ],
            'donations' => [
                1 => [
                  'type' => 'rice',
                  'goal' => '20',
                  'quality' => 'kg',
                ],
                2 => [
                  'type' => 'bạc',
                  'goal' => '100',
                  'quality' => 'g',
                ],
            ],
            'files' => [
                UploadedFile::fake()->image('avatar.jpg', 600, 600),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR)->assertJsonFragment([
            'http_status' => [
                'code' => INTERNAL_SERVER_ERROR,
                'status' => false,
                'message' => 'Have error when create event',
            ],
        ]);
    }

    public function testCreateEventWithTitlesNullThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $campaign = Campaign::find(4);
        $campaign->users()->attach([
            $user->id => [
                'role_id' => 3,
            ],
        ]);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('event.create'), [
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude,
            'latitude' => $faker->latitude,
            'campaign_id' => '4',
            'settings' => [
                1 => '15/3/2017',
                2 => '30/3/2017',
            ],
            'donations' => [
                1 => [
                  'type' => 'rice',
                  'goal' => '20',
                  'quality' => 'kg',
                ],
                2 => [
                  'type' => 'gold',
                  'goal' => '100',
                  'quality' => 'gam',
                ],
            ],
            'files' => [
                UploadedFile::fake()->image('avatar.jpg', 600, 600),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(VALIDATOR_ERROR)->assertJsonFragment([
            'status' => false,
            'messages' => [
                trans('validation.required', ['attribute' => 'title']),
            ],
        ]);
    }

    public function testCreateEventWithDonationsNullThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $campaign = Campaign::find(4);
        $campaign->users()->attach([
            $user->id => [
                'role_id' => 3,
            ],
        ]);
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('event.create'), [
            'title' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude,
            'latitude' => $faker->latitude,
            'campaign_id' => '4',
            'settings' => [
                1 => '15/3/2017',
                2 => '30/3/2017',
            ],
            'files' => [
                UploadedFile::fake()->image('avatar.jpg', 600, 600),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJsonFragment([
            'event' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }
}

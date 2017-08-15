<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditCamapaignTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testEditCampaignSuccess()
    {
        $campaign = Campaign::find(1);
        $user = $campaign->owner()->first();

        if (!$user) {
            $user = factory(User::class)->create(['email' => 'exampale.unit.test@gmail.com']);
            $campaign->users()->attach([
                $user->id => [
                    'role_id' => Role::where(['name' => Role::ROLE_OWNER, 'type' => Role::TYPE_CAMPAIGN])->first(['id'])->id,
                ],
            ]);
        }

        $this->actingAs($user, 'api');
        $faker = \Faker\Factory::create();

        $response = $this->json('put', route('campaign.update', ['campaign' => $campaign->id]), [
            'hashtag' => $campaign->hashtag,
            'title' => $faker->name,
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
            'tags' => [
                [
                    'id' => 1,
                ],
                [
                    'id' => 2,
                ],
                [
                    'id' => null,
                    'name' => 'create new tag',
                ],
            ],
            'settings' => [
                [
                    'key' => 1,
                    'value' => '2',
                ],
                [
                    'key' => 2,
                    'value' => '3',
                ],
            ],
            'media' => UploadedFile::fake()->image('update.png', 600, 600),
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testEditCampaignWhenNotFoundCampaignThenFail()
    {
        $user = User::find(1);
        $this->actingAs($user, 'api');
        $faker = \Faker\Factory::create();

        $response = $this->json('put', route('campaign.update', ['campaign' => 100]), [
            'hashtag' => $faker->name,
            'title' => $faker->name,
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
            'tags' => [
                [
                    'id' => 1,
                ],
                [
                    'id' => 2,
                ],
                [
                    'id' => null,
                    'name' => 'create new tag',
                ],
            ],
            'settings' => [
                [
                    'key' => 1,
                    'value' => '2',
                ],
                [
                    'key' => 2,
                    'value' => '3',
                ],
            ],
            'media' => UploadedFile::fake()->image('update.png', 600, 600),
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(NOT_FOUND)->assertJson([
            'http_status' => [
                'code' => NOT_FOUND,
                'status' => false,
            ],
        ]);
    }

    public function testEditCampaignWhenInvalidArrayThenFail()
    {
        $campaign = Campaign::find(1);
        $user = $campaign->owner()->first();

        if (!$user) {
            $user = factory(User::class)->create(['email' => 'exampale.unit.test@gmail.com']);
            $campaign->users()->attach([
                $user->id => [
                    'role_id' => Role::where(['name' => Role::ROLE_OWNER, 'type' => Role::TYPE_CAMPAIGN])->first(['id'])->id,
                ],
            ]);
        }

        $this->actingAs($user, 'api');
        $faker = \Faker\Factory::create();

        $response = $this->json('put', route('campaign.update', ['campaign' => 1]), [
            'hashtag' => $faker->name,
            'title' => $faker->name,
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
            'tags' => [
                [
                    'id' => 1,
                    'name' => 'add tag1',
                ],
                'id' => 12,
                'name' => 'add tag1',
                [
                    'id' => null,
                    'name' => 'create new tag',
                ],
            ],
            'settings' => [
                [
                    'key' => 1,
                    'value' => '2',
                ],
                [
                    'key' => 2,
                    'value' => '3',
                ],
                'key' => 3,
                'value' => null,
            ],
            'media' => UploadedFile::fake()->image('update.png', 600, 600),
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR)->assertExactJson([
            'http_status' => [
                'status' => false,
                'code' => INTERNAL_SERVER_ERROR,
                'message' => 'Invalid format array',
        ]]);
    }

    public function testEditCampaignWhenValidateFailThenFail()
    {
        $campaign = Campaign::find(1);
        $otherCampaign = Campaign::find(3);
        $user = $campaign->owner()->first();

        if (!$user) {
            $user = factory(User::class)->create(['email' => 'exampale.unit.test@gmail.com']);
            $campaign->users()->attach([
                $user->id => [
                    'role_id' => Role::where(['name' => Role::ROLE_OWNER, 'type' => Role::TYPE_CAMPAIGN])->first(['id'])->id,
                ],
            ]);
        }

        $this->actingAs($user, 'api');
        $faker = \Faker\Factory::create();

        $response = $this->json('put', route('campaign.update', ['campaign' => 1]), [
            'hashtag' => $otherCampaign->hashtag,
            'title' => null,
            'description' => null,
            'longitude' => 181,
            'latitude' => -91,
            'tags' => [],
            'settings' => [],
            'media' => $faker->name,
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(VALIDATOR_ERROR)->assertExactJson([
            'code' => VALIDATOR_ERROR,
            'status' => false,
            'messages' => [
                trans('validation.unique', ['attribute' => 'hashtag']),
                trans('validation.required', ['attribute' => 'title']),
                trans('validation.required', ['attribute' => 'description']),
                trans('validation.max.numeric', ['attribute' => 'longitude', 'max' => 180]),
                trans('validation.min.numeric', ['attribute' => 'latitude', 'min' => -90]),
                "validation.base64url",
            ],
        ]);
    }
}

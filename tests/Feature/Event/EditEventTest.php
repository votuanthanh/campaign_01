<?php

namespace Tests\Feature\Event;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Campaign;
use App\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EditEventTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function testWhenAddNewImageThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $roleUser = Role::where('name', Role::ROLE_USER)->where('type', Role::TYPE_SYSTEM)->first();
        $roleCampaign = Role::where('name', Role::ROLE_OWNER)->where('type', Role::TYPE_CAMPAIGN)->first();
        $user->roles()->attach($roleUser->id);
        $campain = factory(Campaign::class)->create(['hashtag' => 'Nothing']);
        $campain->users()->attach([
            $user->id => [
                'role_id' => $roleCampaign->id,
            ],
        ]);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create([
            'campaign_id' => $campain->id,
            'user_id' => $user->id,
        ]);
        $response = $this->json('PATCH', route('event.update-event', ['id' =>  $event->id]), [
            'title' => $faker->name,
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
            "address" => $faker->address,
            "settings" => [
                [
                    "key" => 7,
                    "value" => "08/07/2017",
                ],
                [
                    "key" => 8,
                    "value" => "08/09/2017",
                ],
            ],
            'files' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
            "goalAdds" => [
                [
                    "type" => "xe",
                    "goal" => "123",
                    "quality" => "cái",
                ],
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertExactJson([
            'event' => true,
            'http_status' => [
                'status' => true,
                'code' => CODE_OK,
            ],
        ]);
    }

    public function testWhenEditInfoButNotRightThenFails()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $roleUser = Role::where('name', Role::ROLE_USER)->where('type', Role::TYPE_SYSTEM)->first();
        $roleCampaign = Role::where('name', Role::ROLE_OWNER)->where('type', Role::TYPE_CAMPAIGN)->first();
        $user->roles()->attach($roleUser->id);
        $campain = factory(Campaign::class)->create(['hashtag' => 'some thing']);
        $campain->users()->attach([
            $user->id => [
                'role_id' => $roleCampaign->id,
            ],
        ]);
        $otherUser = factory(User::class)->create();
        $otherUser->roles()->attach($roleUser->id);
        $this->actingAs($otherUser, 'api');
        $event = factory(Event::class)->create([
            'campaign_id' => $campain->id,
            'user_id' => $user->id,
        ]);

        $response = $this->json('PATCH', route('event.update-event', ['id' => $event->id]), [
            'title' => $faker->name,
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
            "address" => $faker->address,
            "settings" => [
                [
                    "key" => 7,
                    "value" => "08/07/2017",
                ],
                [
                    "key" => 8,
                    "value" => "08/09/2017",
                ],
            ],
            'files' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
            "goalAdds" => [
                [
                    "type" => "xe",
                    "goal" => "123",
                    "quality" => "cái",
                ],
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $otherUser->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR)->assertExactJson([
            'http_status' => [
                'status' => false,
                'code' => INTERNAL_SERVER_ERROR,
                'message' => 'Permission error: User can not edit this event.',
            ],
        ]);
    }

    public function testWhenTitleIsNullThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('PATCH', route('event.update-event', ['id' => 1]), [
            'description' => $faker->paragraph(),
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(VALIDATOR_ERROR)->assertExactJson([
            'code' => VALIDATOR_ERROR,
            'status' => false,
            'messages' => [
                trans('validation.required', ['attribute' => 'title']),
            ],
        ]);
    }

    public function testWhenDesriptionIsNullThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('PATCH', route('event.update-event', ['id' => 1]), [
            'title' => 'This is title',
            'longitude' => $faker->longitude($min = -180, $max = 180),
            'latitude' => $faker->latitude($min = -90, $max = 90),
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(VALIDATOR_ERROR)->assertExactJson([
            'code' => VALIDATOR_ERROR,
            'status' => false,
            'messages' => [
                trans('validation.required', ['attribute' => 'description']),
            ],
        ]);
    }
}

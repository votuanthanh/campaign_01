<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Event;
use App\Models\Action;
use App\Models\Media;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateActionTest extends TestCase
{
    use DatabaseTransactions;

    public function testUpdateActionWithSuperAdminThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $user2 = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $roleAdminId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $roleMemberId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleAdminId);
        $user2->roles()->attach($roleMemberId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create();
        $action = factory(Action::class)->create([
            'event_id' => $event->id,
            'user_id' => $user2->id,
        ]);

        $media = factory(Media::class, 3)->make()->toArray();
        $mediaIds = $action->media()->createMany($media)->pluck('id')->toArray();
        $mediaIds = $action->media->pluck('id')->toArray();

        $response = $this->json('PATCH', route('action.update', ['id' => $action->id]), [
            '_method' => 'patch',
            'caption' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'delete' => $mediaIds,
            'upload' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'action' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUpdateActionWithOwnerThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $roleMemberId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleMemberId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create();
        $action = factory(Action::class)->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $media = factory(Media::class, 3)->make()->toArray();
        $mediaIds = $action->media()->createMany($media)->pluck('id')->toArray();
        $mediaIds = $action->media->pluck('id')->toArray();

        $response = $this->json('PATCH', route('action.update', ['id' => $action->id]), [
            '_method' => 'patch',
            'caption' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'delete' => $mediaIds,
            'upload' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'action' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUpdateActionDoNotActionThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $roleMemberId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleMemberId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create();
        $action = factory(Action::class)->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $media = factory(Media::class, 3)->make()->toArray();
        $mediaIds = $action->media()->createMany($media)->pluck('id')->toArray();
        $mediaIds = $action->media->pluck('id')->toArray();

        $response = $this->json('PATCH', route('action.update', ['id' => -11]), [
            '_method' => 'patch',
            'caption' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'delete' => $mediaIds,
            'upload' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(NOT_FOUND);
    }

    public function testUpdateActionDoNotCaptionThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $roleMemberId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleMemberId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create();
        $action = factory(Action::class)->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $media = factory(Media::class, 3)->make()->toArray();
        $mediaIds = $action->media()->createMany($media)->pluck('id')->toArray();
        $mediaIds = $action->media->pluck('id')->toArray();

        $response = $this->json('PATCH', route('action.update', ['id' => $action]), [
            '_method' => 'patch',
            'caption' => '',
            'description' => $faker->paragraph(),
            'delete' => $mediaIds,
            'upload' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(VALIDATOR_ERROR);
    }

    public function testUpdateActionWithUploadNullThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $roleMemberId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleMemberId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create();
        $action = factory(Action::class)->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $media = factory(Media::class, 3)->make()->toArray();
        $mediaIds = $action->media()->createMany($media)->pluck('id')->toArray();
        $mediaIds = $action->media->pluck('id')->toArray();

        $response = $this->json('PATCH', route('action.update', ['id' => $action]), [
            '_method' => 'patch',
            'caption' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'delete' => $mediaIds,
            'upload' => [],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'action' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }

    public function testUpdateActionWithDeleteNullThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create([
            'status' => User::ACTIVE,
        ]);

        $roleMemberId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleMemberId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create();
        $action = factory(Action::class)->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        $media = factory(Media::class, 3)->make()->toArray();
        $mediaIds = $action->media()->createMany($media)->pluck('id')->toArray();
        $mediaIds = $action->media->pluck('id')->toArray();

        $response = $this->json('PATCH', route('action.update', ['id' => $action]), [
            '_method' => 'patch',
            'caption' => $faker->sentence(10),
            'description' => $faker->paragraph(),
            'delete' => [],
            'upload' => [
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
                $faker->image($dir = '/tmp', $width = 640, $height = 480),
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK)->assertJson([
            'action' => true,
            'http_status' => [
                'code' => CODE_OK,
                'status' => true,
            ],
        ]);
    }
}

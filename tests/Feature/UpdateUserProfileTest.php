<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UpdateUserProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function testUpdatePasswordThenSuccess()
    {
        $user = factory(User::class)->create(['password' => 'password']);
        $this->actingAs($user, 'api');

        $response = $this->json('PATCH', route('user.password'), [
            'current_password' => 'password',
            'password' => 'new_password',
            'password_confirmation' => 'new_password',
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testUpdatePasswordWithInvalidCurrentPasswordThenFail()
    {
        $user = factory(User::class)->make(['password' => 'password']);
        $this->actingAs($user, 'api');

        $response = $this->json('PATCH', route('user.password'), [
            'current_password' => 'invalid current password',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertExactJson([
            'http_status' => [
                'code' => BAD_REQUEST,
                'status' => false,
                'message' => 'Current password is incorrect!',
            ],
        ]);
    }

    public function testUpdateProfileThenSuccess()
    {
        $user = factory(User::class)->create([
            'name' => 'Fake user',
            'email' => 'fakemail@example.com',
            'password' => 'password',
            'birthday' => '1990-01-16',
            'address' => 'Fake address',
            'about' => null,
        ]);

        $this->actingAs($user, 'api');

        $response = $this->json('PATCH', route('user.profile'), [
            'name' => 'New name',
            'address' => 'New address',
            'gender' => 1,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testUpdateAvatarSuccess()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('user.avatar'), [
            'avatar' => UploadedFile::fake()->image('avatar.jpg'),
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testUpdateHeaderPhotoSuccess()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('user.header'), [
            'head_photo' => UploadedFile::fake()->image('header_photo.png'),
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testUpdateAvatarWithEmptyFieldThenFail()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $response = $this->json('POST', route('user.avatar'), [
            'avatar' => '',
        ]);

        $response->assertStatus(BAD_REQUEST);
    }
}

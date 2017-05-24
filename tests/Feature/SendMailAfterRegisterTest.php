<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SendMailAfterRegisterTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    // serve don't have redis
    // public function testRegisterSuccess()
    // {
    //     \Session::start();
    //     $response = $this->json('POST', '/register', [
    //         '_token' => \Session::token(),
    //         'email' => 'example@gmail.com',
    //         'name' => 'example',
    //         'password' => '123123',
    //         'password_confirmation' => '123123',
    //         'gender' => 1,
    //     ]);

    //     $response->assertExactJson([
    //         'status' => true,
    //     ]);
    // }

    // public function testRegisterFailWithSameEmail()
    // {
    //     \Session::start();
    //     $response = $this->json('POST', '/register', [
    //         '_token' => \Session::token(),
    //         'email' => 'example@gmail.com',
    //         'name' => 'example',
    //         'password' => '123123',
    //         'password_confirmation' => '123123',
    //         'gender' => 1,
    //     ]);

    //     $response->assertExactJson([
    //         'status' => false,
    //     ]);
    // }

    public function testRegisterFailWithInvalidEmail()
    {
        \Session::start();
        $response = $this->json('POST', '/register', [
            '_token' => \Session::token(),
            'email' => '@admin@@gmail.com',
            'name' => 'example',
            'password' => '123123',
            'password_confirmation' => '123123',
            'gender' => 1,
        ]);

        $response->assertExactJson([
            'status' => false,
        ]);
    }

    public function testRegisterFailWithEmailNull()
    {
        \Session::start();
        $response = $this->json('POST', '/register', [
            '_token' => \Session::token(),
            'email' => null,
            'name' => 'example',
            'password' => '123123',
            'password_confirmation' => '123123',
            'gender' => 1,
        ]);

        $response->assertExactJson([
            'status' => false,
        ]);
    }

    public function testRegisterFailWithInvalidPasswordConfirmation()
    {
        \Session::start();
        $response = $this->json('POST', '/register', [
            '_token' => \Session::token(),
            'email' => 'example1@gmail.com',
            'name' => 'example',
            'password' => '123123',
            'password_confirmation' => '123456',
            'gender' => 1,
        ]);

        $response->assertExactJson([
            'status' => false,
        ]);
    }

    public function testRegisterFailWithPasswordNull()
    {
        \Session::start();
        $response = $this->json('POST', '/register', [
            '_token' => \Session::token(),
            'email' => 'demo@gmail.com',
            'name' => 'example',
            'password' => null,
            'password_confirmation' => null,
            'gender' => 1,
        ]);

        $response->assertExactJson([
            'status' => false,
        ]);
    }
}

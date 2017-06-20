<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Facades\ {
    App\Services\PassportService,
    App\Services\SocialService,
    Laravel\Socialite\Contracts\Factory as Provider
};

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Provider::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $user = SocialService::createOrGetUser(Provider::driver($provider));

        return redirect('/')->with('access_token', PassportService::getTokenByUser($user));
    }
}

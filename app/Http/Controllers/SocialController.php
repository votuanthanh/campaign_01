<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Facades\ {
    App\Services\PassportService,
    App\Services\SocialService,
    Laravel\Socialite\Contracts\Factory as Provider
};
use FAuth;
use App\Models\SocialAccount;

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return $provider === SocialAccount::FRAMGIA_PROVIDER ? FAuth::redirect() : Provider::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $driver = $provider === SocialAccount::FRAMGIA_PROVIDER ? FAuth::driver($provider) : Provider::driver($provider);

        $user = SocialService::createOrGetUser($driver);

        return redirect('/')->with('access_token', PassportService::getTokenByUser($user));
    }
}

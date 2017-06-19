<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api', 'middleware' => ['xssProtection']], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('register', 'RegisterController@create')->name('register');
    });

    Route::post('check-exist', 'CommonController@checkExist');

    Route::group(['middleware' => 'auth:api'], function () {

    Route::post('logout', 'Auth\AuthController@logout')->name('logout');
        Route::group(['prefix' => '/campaign', 'as' => 'campaign.'], function () {
            Route::post('post-create', 'CampaignController@create')->name('create');
            Route::delete('post-delete', 'CampaignController@delete')->name('delete');
        });

        Route::group(['prefix' => '/event', 'as' => 'event.'], function () {
            Route::post('create', 'EventController@create')->name('create');
            Route::patch('/update/{id}', 'EventController@update')->name('update-event');
            Route::patch('/update-setting/{id}', 'EventController@updateSetting')->name('update-setting');
        });
    });

    Route::group(['as' => 'user.', 'prefix' => 'settings'], function () {
        Route::patch('password', 'UserController@updatePassword')->name('password');
        Route::patch('profile', 'UserController@updateProfile')->name('profile');
        Route::post('avatar', 'UserController@updateAvatar')->name('avatar');
        Route::post('header-photo', 'UserController@updateHeaderPhoto')->name('header');
        Route::patch('follow/{id}', 'UserController@follow')->name('follow');
        Route::patch('unfollow/{id}', 'UserController@unfollow')->name('unfollow');
    });
});

Route::group(['namespace' => 'Api', 'prefix' => 'user/{id}', 'as' => 'user.'], function () {
    Route::get('followers', 'UserController@listFollower')->name('follower');
    Route::get('followings', 'UserController@listFollowing')->name('following');
    Route::get('owned-campaign', 'UserController@listOwnedCampaign')->name('owned-campaign');
    Route::get('joined-campaign', 'UserController@listJoinedCampaign')->name('joined-campaign');
});

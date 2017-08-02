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
Route::middleware('auth:api')->get('/user', 'Api\UserController@authUser');
Route::get('/show-campaign', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'Api', 'middleware' => ['xssProtection']], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login')->name('login');
        Route::post('register', 'RegisterController@register')->name('register');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/reset', 'ResetPasswordController@reset');
    });

    Route::post('check-exist', 'CommonController@checkExist');

    Route::group(['prefix' => 'user/{id}', 'as' => 'user.'], function () {
        Route::get('/', 'UserController@getTimeLine')->name('getTimeLine');
        Route::get('followers', 'UserController@listFollower')->name('follower');
        Route::get('followings', 'UserController@listFollowing')->name('following');
        Route::get('owned-campaign', 'UserController@listOwnedCampaign')->name('owned-campaign');
        Route::get('joined-campaign', 'UserController@listJoinedCampaign')->name('joined-campaign');
        Route::get('following-campaign', 'UserController@listFollowingCampaign')->name('following-campaign');
        Route::get('following-tag', 'UserController@listFollowingTag')->name('following-tag');
        Route::get('get-user', 'UserController@getUser')->name('user');
        Route::get('search-followers/{data}', 'UserController@searchFollowers')->name('search-followers');
        Route::get('search-followings/{data}', 'UserController@searchFollowings')->name('search-followings');
    });

    Route::resource('tag', 'TagController', ['only' => ['index', 'show']]);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('logout', 'Auth\AuthController@logout')->name('logout');

        Route::post('send-message', 'ChatController@sendMessage');
        Route::post('send-message-to-group', 'ChatController@sendMessageToGroup');
        Route::get('show-message/{id}', 'ChatController@showMessages');
        Route::get('show-notifications', 'ChatController@getNotification');
        Route::post('like/{modelId}/{flag}', 'LikeController@like')->name('like');

        Route::group(['as' => 'user.'], function () {
            Route::group(['prefix' => 'settings'], function () {
                Route::patch('password', 'UserController@updatePassword')->name('password');
                Route::patch('profile', 'UserController@updateProfile')->name('profile');
                Route::post('avatar', 'UserController@updateAvatar')->name('avatar');
                Route::post('header-photo', 'UserController@updateHeaderPhoto')->name('header');
                Route::post('upload-files/{path}', 'UserController@uploadImages')->name('upload');
            });
            Route::patch('follow/{id}', 'UserController@follow')->name('follow');
            Route::patch('follow-tag/{id}', 'UserController@followTag')->name('follow-tag');
            Route::patch('join-campaign/{id}', 'UserController@joinCampaign')->name('join-campaign');
            Route::get('list-user-following', 'UserController@getListFollow')->name('list-user-following');
        });

        Route::group(['prefix' => '/campaign', 'as' => 'campaign.'], function () {
            Route::post('like/{campaignId}', 'CampaignController@like')->name('like');
            Route::patch('change-role', 'CampaignController@changeMemberRole')->name('change-role');
            Route::patch('remove-user', 'CampaignController@removeUser')->name('remove-user');
            Route::patch('change-owner', 'CampaignController@changeOwner')->name('change-owner');
            Route::post('approve-user', 'CampaignController@approveUserJoinCampaign')->name('approve');
            Route::get('get/tags', 'CampaignController@getTags')->name('tags');
            Route::get('/{id}/timeline/event', 'CampaignController@getListEvent');
            Route::get('member/{campaignId}', 'CampaignController@members')->name('members');
            Route::post('attend-campaign/{id}/{flag}', 'CampaignController@attendCampaign')->name('attendCampaign');
            Route::get('list-photos/{id}', 'CampaignController@listPhotos');
            Route::get('campaign-related/{id}', 'CampaignController@getCampaignRelated');
        });

        Route::resource('campaign', 'CampaignController', ['only' => ['store', 'update', 'destroy', 'show']]);

        Route::group(['prefix' => '/event', 'as' => 'event.'], function () {
            Route::post('create', 'EventController@create')->name('create');
            Route::patch('/update/{id}', 'EventController@update')->name('update-event');
            Route::patch('/update-setting/{id}', 'EventController@updateSetting')->name('update-setting');
            Route::post('like/{eventId}', 'EventController@like')->name('like');
            Route::get('show/{id}', 'EventController@show');
            Route::get('donation', 'EventController@getTypeQuality')->name('getTypeQuality');
            Route::get('check-permission/{id}', 'EventController@checkIfUserCanManageEvent')->name('check-permission');
        });

        Route::group(['prefix' => '/action', 'as' => 'action.'], function () {
            Route::patch('/update/{id}', 'ActionController@update')->name('update');
            Route::post('like/{actionId}', 'ActionController@like')->name('like');
            Route::get('list/{eventId}', 'ActionController@listAction');
            Route::get('search/{eventId}', 'ActionController@searchAction');
            Route::post('create', 'ActionController@store')->name('create');
        });

        Route::resource('/comment', 'CommentController', ['only' => ['update', 'destroy', 'show']]);

        Route::group(['prefix' => '/comment', 'as' => 'comment.'], function () {
            Route::post('/create-comment/{modelId}/{parentId}/{flag}', 'CommentController@createComment')->name('create');
            Route::get('/sub-comment/{parentId}', 'CommentController@getSubComment');
        });

        Route::group(['prefix' => '/donation'], function () {
            Route::patch('/update-status/{id}', 'DonationController@updateStatus')->name('update-status');
            Route::post('create-many', 'DonationController@createMany');
            Route::resource('donation', 'DonationController', ['only' => ['store', 'update', 'destroy']]);
        });

        Route::resource('expense', 'ExpenseController', ['only' => ['store', 'update', 'destroy']]);

        Route::group(['prefix' => 'file', 'as' => 'file'], function () {
            Route::post('upload', 'UploadController@upload')->name('upload');
            Route::delete('delete/{path?}', 'UploadController@delete')->name('delete')->where('path', '.+');
        });
    });
});


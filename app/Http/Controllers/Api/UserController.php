<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Contracts\UserInterface;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Requests\User\SecurityRequest;
use App\Http\Requests\User\ImageUploadRequest;
use App\Exceptions\Api\UnknowException;
use App\Traits\Common\UploadableTrait;
use Carbon\Carbon;
use Hash;

class UserController extends ApiController
{
    use UploadableTrait;

    public function __construct(UserInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * Update current user's password
     *
     * @param  App\Http\Requests\Api\User\SecurityRequest  $request;
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(SecurityRequest $request)
    {
        if (!Hash::check($request->get('current_password'), $this->user->password)) {
            throw new UnknowException('Current password is incorrect!', BAD_REQUEST);
        }

        return $this->doAction(function () use ($request) {
            $this->repository->update($this->user->id, [
                'password' => $request->get('password'),
            ]);
        });
    }

    /**
     * Update current user profile
     *
     * @param  App\Http\Requests\Api\User\ProfileRequest  $request;
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(ProfileRequest $request)
    {
        $request->has('birthday')
            ? $request->merge(['birthday' => Carbon::parse($request->get('birthday'))->toDateString()])
            : null;

        return $this->doAction(function () use ($request) {
            $user = $this->repository
                ->update($this->user->id, $request->only('name', 'birthday', 'address', 'phone', 'gender', 'about'));
            $this->compacts['user'] = $user;
        });
    }

    /**
     * Update current user's avatar
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(ImageUploadRequest $request)
    {
        if (!$request->hasFile('avatar')) {
            throw new UnknowException('File is incorrect', BAD_REQUEST);
        }

        return $this->doAction(function () use ($request) {
            $uploaded = $this->uploadFile($request->file('avatar'), 'avatar');
            $this->destroyFile($this->user->url_file);
            $this->repository->update($this->user->id, ['url_file' => $uploaded]);
        });
    }

    /**
     * Update current user's header photo
     * @return \Illuminate\Http\Response
     */
    public function updateHeaderPhoto(ImageUploadRequest $request)
    {
        if (!$request->hasFile('head_photo')) {
            throw new UnknowException('File is incorrect', BAD_REQUEST);
        }

        return $this->doAction(function () use ($request) {
            $uploaded = $this->uploadFile($request->file('head_photo'), 'header');
            $this->destroyFile($this->user->head_photo);
            $this->repository->update($this->user->id, ['head_photo' => $uploaded]);
        });
    }

    /**
     * List all user's follower
     * @return \Illuminate\Http\Response
     */
    public function listFollower($id)
    {
        return $this->getData(function () use ($id) {
            $this->compacts['data'] = $this->repository->listFollower($id);
        });
    }

    /**
     * List all user that this user are following
     * @return \Illuminate\Http\Response
     */
    public function listFollowing($id)
    {
        return $this->getData(function () use ($id) {
            $this->compacts['data'] = $this->repository->listFollowing($id);
        });
    }

    /**
     * List all campaigns that the user owns
     * @return \Illuminate\Http\Response
     */
    public function listOwnedCampaign($id)
    {
        return $this->getData(function () use ($id) {
            $this->compacts['data'] = $this->repository->ownedCampaign($id);
        });
    }

    /**
     * List all campaigns that the user join
     * @return \Illuminate\Http\Response
     */
    public function listJoinedCampaign($id)
    {
        return $this->getData(function () use ($id) {
            $this->compacts['data'] = $this->repository->joinedCampaign($id);
        });
    }

    /**
     * Follow other user
     * @return \Illuminate\Http\Response
     */
    public function follow($id)
    {
        return $this->doAction(function () use ($id) {
            $this->user->followings()->syncWithOutDetaching($id);
        });
    }

    /**
     * Unfollow current user
     * @return \Illuminate\Http\Response
     */
    public function unfollow($id)
    {
        return $this->doAction(function () use ($id) {
            $this->user->followings()->detach($id);
        });
    }

    /**
     * Follow a tag
     * @return \Illuminate\Http\Response
     */
    public function followTag($id)
    {
        //
    }

    /**
     * Unfollow a tag
     * @return \Illuminate\Http\Response
     */
    public function unfollowTag($id)
    {
        //
    }

    /**
     * List all campaigns that user are following (with tag)
     */
    public function listFollowCampaign($id)
    {
        //
    }
    /**
     * Join a campaign
     * @return \Illuminate\Http\Response
     */
    public function joinCampaign($id)
    {
        //
    }

    /**
     * Quit a campaign which user are in
     * @return \Illuminate\Http\Response
     */
    public function quitCampaign($id)
    {
        //
    }
}

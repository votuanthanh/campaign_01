<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Repositories\Contracts\UserInterface;
use App\Repositories\Contracts\TagInterface;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Requests\User\SecurityRequest;
use App\Http\Requests\User\ImageUploadRequest;
use App\Exceptions\Api\UnknowException;
use App\Traits\Common\UploadableTrait;
use Carbon\Carbon;
use Hash;
use App\Models\User;
use App\Models\Campaign;

class UserController extends ApiController
{
    use UploadableTrait;

    protected $tagRepository;

    public function __construct(UserInterface $userRepository, TagInterface $tagRepository)
    {
        parent::__construct($userRepository);
        $this->tagRepository = $tagRepository;
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
            $data = $this->repository->listFollower($id);
            $this->compacts['currentPageUser'] = $data['currentPageUser'];
            $this->compacts['data'] = $data['follower'];
        });
    }

    /**
     * List all user that this user are following
     * @return \Illuminate\Http\Response
     */
    public function listFollowing($id)
    {
        return $this->getData(function () use ($id) {
            $data = $this->repository->listFollowing($id);
            $this->compacts['currentPageUser'] = $data['currentPageUser'];
            $this->compacts['data'] = $data['follower'];
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
     * Toggle follow or unfollow user
     * @return \Illuminate\Http\Response
     */
    public function follow($id)
    {
        return $this->doAction(function () use ($id) {
            $this->user->followings()->toggle($id);
        });
    }

    /**
     * Toogle follow or unfollow a tag
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function followTag($id)
    {
        $this->tagRepository->findOrFail($id);

        return $this->doAction(function () use ($id) {
            $this->user->tags()->toggle($id);
        });
    }

    /**
     * List all campaigns that user are following (with tag)
     * @return \Illuminate\Http\Response
     */
    public function listFollowingCampaign($id)
    {
        return $this->doAction(function () use ($id) {
            $this->compacts['data'] = $this->repository->listFollowingCampaign($id);
        });
    }

    public function listFollowingTag($id)
    {
        return $this->doAction(function () use ($id) {
            $this->compacts['data'] = $this->repository->listFollowingTag($id);
        });
    }

    /**
     * Join/quit a campaign
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function joinCampaign($id)
    {
        $this->repository->findOrFail($id);

        return $this->doAction(function () use ($id) {
            $this->repository->joinCampaign($id);
        });
    }

    public function getUser($id)
    {
        return $this->getData(function () use ($id) {
            $this->compacts['data'] = $this->repository->find($id);
        });
    }

    public function searchFollowers($id, $data)
    {
        return $this->getData(function () use ($id, $data) {
            $this->compacts['data'] = $this->repository->searchFollowers($id, $data);
        });
    }

    public function searchFollowings($id, $data)
    {
        return $this->getData(function () use ($id, $data) {
            $this->compacts['data'] = $this->repository->searchFollowings($id, $data);
        });
    }

    /**
     * Timeline of user
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getTimeline($id)
    {
        $user = $this->repository->findOrFail($id);

        return $this->doAction(function () use ($user) {
            $this->compacts['data'] = $this->repository->getTimeline($user);
        });
    }

    public function getListFollow()
    {
        return $this->getData(function () {
            $this->compacts['followings'] = $this->user->followings()
                ->where('status', User::ACTIVE)
                ->get([
                    'users.id',
                    'name',
                    'email',
                    'url_file'
                ]);
            $this->compacts['groups'] = $this->user->campaigns()
                ->where('campaigns.status', Campaign::ACTIVE)
                ->with(['media', 'users' => function ($query) {
                    return $query->select(['users.id', 'users.email', 'users.name'])
                        ->where('users.status', User::ACTIVE);
                }])
                ->get([
                    'campaigns.id',
                    'hashtag'
                ]);
        });
    }
}

<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;
use App\Models\Action;
use App\Models\event;
use App\Repositories\Contracts\UserInterface;
use App\Jobs\SendEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exceptions\Api\UnknowException;
use App\Traits\Common\UploadableTrait;

class UserRepository extends BaseRepository implements UserInterface
{
    use DispatchesJobs, UploadableTrait;

    public function model()
    {
        return User::class;
    }

    public function active($token)
    {
        $user = $token ? $this->where('token_confirm', $token)->first() : false;

        if (!$user) {
            return false;
        }

        $this->update($user->id, [
            'status' => User::ACTIVE,
            'token_confirm' => null,
        ]);

        return true;
    }

    public function register($inputs, $roleId)
    {
        $user = $this->create($inputs);

        if (!$user) {
            throw new UnknowException('Had errors while processing');
        }

        $user->roles()->attach($roleId);

        // Send email active to user
        $info = [
            'email' => $inputs['email'],
            'subject' => trans('emails.active_subject'),
        ];

        $fields = [
            'linkActive' => action('Frontend\UserController@active', $user->token_confirm),
            'content' => trans('emails.active_account', ['object' => $user->name]),
        ];

        $this->dispatch(new SendEmail($info, User::ACTIVE_LINK_SEND, $fields));

        return $fields;
    }

    /**
     * List all campaigns that the user owns
     * @return Illuminate\Pagination\Paginator
     */
    public function ownedCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)->campaigns()
            ->with('users', 'owner', 'media')
            ->wherePivot('role_id', app(RoleRepository::class)->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)->id)
            ->orderBy($orderBy, $direction)
            ->simplePaginate(2);
    }

    /**
     * List all campaigns that the user join
     * @return Illuminate\Pagination\Paginator
     */
    public function joinedCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)->campaigns()
            ->with('users', 'owner', 'media')
            ->wherePivotIn('role_id', app(RoleRepository::class)->findRoleOrFail([
                Role::ROLE_OWNER,
                Role::ROLE_MODERATOR,
                Role::ROLE_MEMBER,
            ], Role::TYPE_CAMPAIGN)->pluck('id')->all())
            ->orderBy($orderBy, $direction)
            ->simplePaginate(2);
    }

    /**
     * List all user's follower
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollower($id, $orderBy = 'created_at', $direction = 'desc')
    {
        $user = $this->findOrFail($id);
        $listFollower = $user
            ->followers()
            ->where('status', User::ACTIVE)
            ->with('followers', 'followed')
            //"followers" -> this statement will get all follower who is following user
            //"followed" -> this will check followers of current page user is following auth or not
            ->orderBy($orderBy, $direction)
            ->paginate(config('settings.pagination.follow'));

        return [
            'currentPageUser' => $user,
            'follower' => $listFollower,
        ];
    }

    /**
     * List all user that this user are following
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollowing($id, $orderBy = 'created_at', $direction = 'desc')
    {
        $user = $this->findOrFail($id);
        $listFollower = $user
            ->followings()
            ->where('status', User::ACTIVE)
            ->with('followers', 'followed')
            ->orderBy($orderBy, $direction)
            ->paginate(config('settings.pagination.follow'));

        return [
            'currentPageUser' => $user,
            'follower' => $listFollower,
        ];
    }

    public function searchFollowers($id, $data)
    {
        return $this->findOrFail($id)
            ->followers()
            ->with('followers', 'followed')
            ->where('name', 'like', '%' . $data . '%')
            ->get();
    }

    public function searchFollowings($id, $data)
    {
        return $this->findOrFail($id)
            ->followings()
            ->with('followers', 'followed')
            ->where('name', 'like', '%' . $data . '%')
            ->get();
    }

    public function getTimeline($user)
    {
        $activities = $user->activities()
            ->with('activitiable')
            ->with('activitiable.media')
            ->whereIn('activitiable_type', [
                Campaign::class,
                Action::class,
                Event::class,
            ])
            ->paginate(config('setting.pagination.timeline'));

        return [
            'currentPageUser' => $user,
            'listActivity' => $activities,
        ];
    }

    /**
     * List all campaigns that the user are following through tag
     * @param  int $id
     * @param  string $orderBy
     * @param  string $direction
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollowingCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return \App\Models\Campaign::with('users', 'owner', 'media')->whereHas('tags', function ($query) use ($id) {
            $query->whereIn('campaign_tag.tag_id', \App\Models\User::findOrFail($id)->tags->pluck('id'));
        })
            ->distinct()
            ->orderBy($orderBy, $direction)
            ->simplePaginate(config('settings.pagination.following_campaign'));
    }

    /**
     * List all tags that the user are following
     * @param  int $id
     * @param  string $orderBy
     * @param  string $direction
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollowingTag($id, $orderBy = 'created_at', $direction = 'desc')
    {
        $user = $this->findOrFail($id);

        return $user
            ->tags()
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * User join or quit campaign
     * @param  int $campaignId
     */
    public function joinCampaign($campaignId)
    {
        \Auth::guard('api')
            ->user()
            ->campaigns()
            ->toggle([$campaignId => ['status' => Campaign::APPROVING]]);
    }

    /**
     * Multiupload images
     * @param  array  $files
     * @param  string $path
     */
    public function uploadImages(array $files, $path)
    {
        foreach ($files as $key => $file) {
            $uploadedFiles[$key]['url_file'] = $this->uploadFile($file, $path);
            $uploadedFiles[$key]['type'] = \App\Models\Media::IMAGE;
        }

        return \Auth::guard('api')
            ->user()
            ->media()
            ->createMany($uploadedFiles);
    }
}

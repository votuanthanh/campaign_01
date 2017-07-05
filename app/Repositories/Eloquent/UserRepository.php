<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Role;
use App\Models\Campaign;
use App\Repositories\Contracts\UserInterface;
use App\Jobs\SendEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exceptions\Api\UnknowException;

class UserRepository extends BaseRepository implements UserInterface
{
    use DispatchesJobs;

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
            ->wherePivot('role_id', app(RoleRepository::class)->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)->id)
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all campaigns that the user join
     * @return Illuminate\Pagination\Paginator
     */
    public function joinedCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)->campaigns()
            ->wherePivotIn('role_id', app(RoleRepository::class)->findRoleOrFail([
                Role::ROLE_OWNER,
                Role::ROLE_MODERATOR,
                Role::ROLE_MEMBER,
            ], Role::TYPE_CAMPAIGN)->pluck('id')->all())
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all user's follower
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollower($id, $orderBy = 'created_at', $direction = 'desc')
    {
        $user = $this->findOrFail($id);
        $listFollower = $user->followers()
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
        $listFollower = $user->followings()
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

    public function getTimeLine($id)
    {
        return $this->findOrFail($id)
            ->campaigns()
            ->with('events', 'actions')
            ->paginate(config('settings.pagination.timeline'));
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
        $this->findOrFail($id);

        return \DB::table('users')->join('tag_user', 'users.id', '=', 'tag_user.user_id')
            ->join('campaign_tag', 'tag_user.tag_id', '=', 'campaign_tag.tag_id')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_tag.campaign_id')
            ->where('users.id', $id)
            ->select('users.id', 'campaigns.*')
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
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
}

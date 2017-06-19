<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Role;
use App\Repositories\Contracts\UserInterface;
use App\Jobs\SendEmail;
use Exception;
use DB;

class UserRepository extends BaseRepository implements UserInterface
{
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
        $data = $inputs ?: false;

        if (!$data || !$roleId) {
            return false;
        }

        $data['token_confirm'] = md5(uniqid($data['email'], true));
        $data['status'] = User::IN_ACTIVE;
        $info = [
            'email' => $data['email'],
            'subject' => trans('emails.active_subject'),
        ];

        $fields = [
            'linkActive' => action('Frontend\UserController@active', $data['token_confirm']),
            'content' => trans('emails.active_account', ['object' => $data['name']]),
        ];

        DB::beginTransaction();
        try {
            $user = $this->create($data);
            $role = $user->roles()->attach(['role_id' => $roleId]);
            $job = (new SendEmail($info, 'emails.active', $fields))->onConnection('redis')->onQueue('emails');
            dispatch($job);
            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollback();

            return false;
        }
    }

    /**
     * List all campaigns that the user owns
     * @return Illuminate\Pagination\Paginator
     */
    public function ownedCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)->campaigns()
            ->wherePivot('role_id', app(RoleRepository::class)->getRoleByName(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)->first())
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
            ->wherePivotIn('role_id', app(RoleRepository::class)->getRoleByName([
                Role::ROLE_OWNER,
                Role::ROLE_MODERATOR,
                Role::ROLE_MEMBER,
            ], Role::TYPE_CAMPAIGN)->all())
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all user's follower
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollower($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)
            ->followers()
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all user that this user are following
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollowing($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)
            ->followings()
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }
}

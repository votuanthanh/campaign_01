<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Jobs\SendEmail;
use Exception;
use DB;

class UserRepository extends BaseRepository implements UserRepositoryInterface
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
        } catch(Exception $e) {
            DB::rollback();

            return false;
        }
    }
}

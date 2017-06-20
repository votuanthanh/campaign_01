<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\PassportService;
use App\Repositories\Contracts\UserInterface;
use App\Models\User;
use App\Exceptions\Api\UnknowException;
use App\Exceptions\Api\NotFoundException;

class AuthController extends ApiController
{
    protected $dataSelected = [
        'name',
        'email',
        'birthday',
        'address',
        'phone',
        'url_file',
    ];

    public function __construct(UserInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function login(LoginRequest $request, PassportService $passport)
    {
        $data = $request->only('email', 'password');

        $user = $this->repository->select($this->dataSelected)->where('email', $data['email'])->first();

        if (!$user) {
            throw new NotFoundException('Not Found user');
        }

        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => User::ACTIVE])) {
            throw new UnknowException('User not active or credential invalid');
        }

        return $this->getData(function () use ($passport, $data, $user) {
            $this->compacts['auth'] = $passport->requestGrantToken($data);
            $this->compacts['user'] = $user;
        });
    }

    public function logout()
    {
        if (!$this->user) {
            throw new UnknowException('Access token was invalid');
        }

        $this->user->token()->revoke();

        return $this->trueJson();
    }
}

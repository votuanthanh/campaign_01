<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\PassportService;

class AuthController extends ApiController
{
    public function login(Request $request, PassportService $passport)
    {
        $data = $request->only('email', 'password');

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            throw new \App\Exceptions\Api\NotFoundException('NOT FOUND USER');
        }

        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password'], 'status' => 1])) {
            throw new \App\Exceptions\Api\UnknowException('USER NOT ACTIVE OR CREDENTIAL INVALID');
        }

        return $this->getData(function () use ($passport, $data) {
            $this->compacts['user'] = $passport->requestGrantToken($data);
        });
    }
}

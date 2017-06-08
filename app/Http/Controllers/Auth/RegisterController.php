<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\RoleInterface;
use App\Http\Controllers\AbstractController;
use Auth;
use Illuminate\Http\Request;
use App\Models\Role;

class RegisterController extends AbstractController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    private $roleRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleInterface $roleRepository
    ) {
        parent::__construct($userRepository);
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|numeric',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return $this->responseFail();
        }

        $data = $request->only('name', 'email', 'password', 'gender');
        $role = $this->roleRepository->findRoleId(Role::ROLE_USER, Role::TYPE_SYSTEM);

        if ($role->isEmpty()) {
            return $this->responseFail();
        }
        
        $user = $this->repository->register($data, $role->first()->id);
        
        if (!$user) {
            return $this->responseFail();
        }

        Auth::login($user);

        return $this->responseSuccess();
    }
}

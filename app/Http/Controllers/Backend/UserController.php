<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends BackendController
{
    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);
    }

    public function index()
    {
        $this->compacts['users'] = $this->repository->paginate();

        // view: backend.home.index
        $this->view = 'home.index';

        return $this->viewRender();
    }
}

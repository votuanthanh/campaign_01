<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\ActionInterface;
use App\Exceptions\Api\NotFoundException;
use Exception;

class ActionController extends ApiController
{
    private $actionRepository;

    public function __construct(ActionInterface $actionRepository)
    {
        parent::__construct();
        $this->actionRepository = $actionRepository;
    }

    /**
     * Like or unlike action
     *
     * @return Model if create or bool if delete
     */
    public function like($id)
    {
        $action = $this->actionRepository->findOrFail($id);

        if ($this->user->cannot('view', $action)) {
            throw new Exception('Policy fail');
        }

        return $this->doAction(function () use ($action) {
            $this->compacts['action'] = $this->actionRepository->createOrDeleteLike($action, $this->user->id);
        });
    }
}

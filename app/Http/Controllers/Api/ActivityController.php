<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\Api\UnknowException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\Contracts\ActivityInterface;

class ActivityController extends ApiController
{
    protected $activityRepository;

    public function __construct(ActivityInterface $activity)
    {
        parent::__construct();
        $this->activityRepository = $activity;
    }

    public function getHomePage()
    {
        return $this->getData(function () {
            $this->compacts['listActivity'] = $this->activityRepository->getHomePage();
        });
    }
}

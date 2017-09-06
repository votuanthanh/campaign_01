<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Exceptions\Api\UnknowException;
use App\Exceptions\Api\NotFoundException;
use App\Repositories\Contracts\ActivityInterface;
use App\Repositories\Contracts\SettingInterface;
use App\Repositories\Contracts\EventInterface;
use App\Models\Campaign;

class ActivityController extends ApiController
{
    protected $activityRepository;
    protected $settingRepository;
    protected $eventRepository;

    public function __construct(
        ActivityInterface $activity,
        SettingInterface $setting,
        EventInterface $event
    ) {
        parent::__construct();
        $this->activityRepository = $activity;
        $this->settingRepository = $setting;
        $this->eventRepository = $event;
    }

    public function getNewsFeed()
    {
        $campaigns = $this->settingRepository->getCampaignIds();
        $events = $this->eventRepository->getEventIds($campaigns);

        return $this->getData(function () use ($campaigns, $events) {
            $this->compacts['listActivity'] = $this->activityRepository->getNewsFeed($campaigns, $events);
        });
    }
}

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
        $campaignPublic = $this->settingRepository->getCampaignPublicIds();
        $eventPublic = $this->eventRepository->getEventPublicIds($campaignPublic);

        return $this->getData(function () use ($campaignPublic, $eventPublic) {
            $this->compacts['listActivity'] = $this->activityRepository->getNewsFeed($campaignPublic, $eventPublic);
        });
    }
}

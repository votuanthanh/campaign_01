<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EventRequest;
use App\Exceptions\Api\UnknowException;
use App\Http\Controllers\AbstractController;
use App\Repositories\Contracts\EventInterface;
use App\Repositories\Contracts\QualityInterface;
use App\Repositories\Contracts\CampaignInterface;

class EventController extends ApiController
{
    protected $eventRepository;
    protected $qualityRepository;
    protected $campaignRepository;

    public function __construct(
        EventInterface $eventRepository,
        QualityInterface $qualityRepository,
        CampaignInterface $campaignRepository
    ) {
        parent::__construct();
        $this->qualityRepository = $qualityRepository;
        $this->campaignRepository = $campaignRepository;
        $this->eventRepository = $eventRepository;
    }

    public function create(EventRequest $request)
    {
        $input['data_event'] = $request->only('campaign_id', 'title', 'description', 'longitude', 'latitude');
        $input['data_event']['user_id'] = $this->user->id;
        $input['other'] = $request->only('settings', 'files');
        $input['donations'] = $this->qualityRepository->getOrCreate($request->get('donations'));
        $campaign = $this->campaignRepository->find($input['data_event']['campaign_id']);

        return $this->doAction(function () use ($input, $campaign) {
            if (!$this->user->can('createEvent', $campaign)) {
                throw new UnknowException('Have error when create event');
            }

            $this->compacts['event'] = $this->eventRepository->create($input);
        });
    }
}

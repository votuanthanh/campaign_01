<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Http\Requests\CommentRequest;
use App\Exceptions\Api\UnknowException;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Controllers\AbstractController;
use App\Repositories\Contracts\DonationTypeInterface;
use App\Repositories\Contracts\EventInterface;
use App\Repositories\Contracts\QualityInterface;
use App\Repositories\Contracts\CampaignInterface;
use App\Repositories\Contracts\ActionInterface;

class EventController extends ApiController
{
    protected $eventRepository;
    protected $qualityRepository;
    protected $campaignRepository;
    protected $donationTypeRepository;
    protected $actionRepository;

    public function __construct(
        EventInterface $eventRepository,
        QualityInterface $qualityRepository,
        CampaignInterface $campaignRepository,
        DonationTypeInterface $donationTypeRepository,
        ActionInterface $actionRepository
    ) {
        parent::__construct();
        $this->qualityRepository = $qualityRepository;
        $this->campaignRepository = $campaignRepository;
        $this->eventRepository = $eventRepository;
        $this->donationTypeRepository = $donationTypeRepository;
        $this->actionRepository = $actionRepository;
    }

    public function create(EventRequest $request)
    {
        $input['data_event'] = $request->intersect('campaign_id', 'title', 'description', 'longitude', 'latitude', 'address');
        $input['data_event']['user_id'] = $this->user->id;
        $input['other'] = $request->only('settings', 'files');
        $input['donations'] = $this->qualityRepository->getOrCreate($request->get('donations'));
        $campaign = $this->campaignRepository->find($input['data_event']['campaign_id']);

        return $this->doAction(function () use ($input, $campaign) {
            if ($this->user->cant('createEvent', $campaign)) {
                throw new UnknowException('Have error when create event');
            }

            $this->compacts['event'] = $this->eventRepository->create($input);
        });
    }

    public function update(EventRequest $request, $id)
    {
        $data = $request->only(
            'title',
            'description',
            'longitude',
            'latitude',
            'mediaAdds',
            'mediaDels',
            'goalDels',
            'goalUpdates',
            'goalAdds'
        );
        $event = $this->eventRepository->getEventExist($id);

        if ($this->user->cannot('manage', $event)) {
            throw new UnknowException('Permission error: User can not edit this event.');
        }

        return $this->doAction(function () use ($event, $data) {
            if (!empty($data['goalAdds'])) {
                $data['goalAdd'] = $this->qualityRepository->getOrCreate($data['goalAdds']);
            }

            if (!empty($data['goalUpdates'])) {
                $data['goalUpdate'] = $this->qualityRepository->getOrCreate($data['goalUpdates']);
            }

            $data = array_except($data, ['goalAdds']);
            $this->compacts['event'] = $this->eventRepository->update($event, $data);
        });
    }

    public function updateSetting(Request $request, $id)
    {
        if (!is_array($request->setting)) {
            throw new UnknowException('Error: Invalid parameter.');
        }

        $event = $this->eventRepository->getEventExist($id);

        return $this->doAction(function () use ($event, $request) {
            $this->compacts['event'] = $this->eventRepository->updateSettings($event, $request->setting);
        });
    }

    public function like($id)
    {
        $event = $this->eventRepository->findOrFail($id);

        if ($this->user->cannot('view', $event)) {
            throw new Exception('Policy fail');
        }

        return $this->doAction(function () use ($event) {
            $this->compacts['event'] = $this->eventRepository->createOrDeleteLike($event, $this->user->id);
        });
    }

    public function getTypeQuality()
    {
        return $this->doAction(function () {
            $this->compacts['qualitys'] = $this->qualityRepository->distinct()->select('name')->get();
            $this->compacts['types'] = $this->donationTypeRepository->distinct()->select('name')->get();
        });
    }

    public function show($id)
    {
        return $this->doAction(function () use ($id) {
            $event = $this->eventRepository->findOrFail($id);
            $this->compacts['actions'] = $this->actionRepository->getActionPaginate($event->actions());
            $this->compacts['event'] = $this->eventRepository
                ->where('id', $id)
                ->with([
                    'user',
                    'media',
                    'likes',
                ])
                ->get();
            $this->compacts['goals'] = $event
                ->goals()
                ->select('id', 'donation_type_id', 'goal')
                ->with([
                    'donations' => function ($query) {
                        return $query->with('user')->where('status', \App\Models\Donation::ACCEPT)->latest();
                    },
                    'donationType.quality',
                ])
                ->get();
        });
    }

    public function getComment(Request $request)
    {
        $event = $request->event;

        if ($event instanceof Illuminate\Database\Eloquent\Model) {
            return $this->getData(function () use ($event) {
                $this->compacts['comments'] = $event->comments;
            });
        }

        return $this->getData(function () use ($event) {
            $this->compacts['comments'] = [];
        });
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\CampaignInterface;
use App\Repositories\Contracts\RoleInterface;
use App\Repositories\Contracts\TagInterface;
use App\Repositories\Contracts\EventInterface;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\UnknowException;
use Illuminate\Http\Request;
use App\Http\Requests\CampaignRequest;
use App\Models\Role;

class CampaignController extends ApiController
{
    private $roleRepository;
    private $tagRepository;
    private $eventRepository;
    private $campaignRepository;

    public function __construct(
        CampaignInterface $campaignRepository,
        RoleInterface $roleRepository,
        TagInterface $tagRepository,
        EventInterface $eventRepository
    ) {
        parent::__construct();
        $this->roleRepository = $roleRepository;
        $this->tagRepository = $tagRepository;
        $this->eventRepository = $eventRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function store(CampaignRequest $request)
    {
        $data = $request->only(
            'title',
            'description',
            'hashtag',
            'longitude',
            'latitude',
            'tags',
            'settings',
            'media'
        );

        $data['role_id'] = $this->roleRepository->findRoleOrFail(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)->id;
        $data['user_id'] = $this->user->id;
        $data['tags'] = $this->tagRepository->getOrCreate($data['tags']);

        return $this->doAction(function () use ($data) {
            $this->compacts['campaign'] = $this->campaignRepository->create($data);
        });
    }

    public function destroy($id)
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            throw new NotFoundException('Not foud campaign with: ' . $id, NOT_FOUND);
        }

        if (!$this->user->can('manage', $campaign)) {
            throw new UnknowException('You do not have authorize to delete this campaign', UNAUTHORIZED);
        }

        return $this->doAction(function () use ($campaign) {
            $eventIds = $campaign->events()->pluck('id');
            $this->campacts['event'] = $this->eventRepository->delete($eventIds);
            $this->campacts['campaign'] = $this->campaignRepository->delete($campaign);
        });
    }

    public function update(CampaignRequest $request, $id)
    {
        $data = $request->only(
            'title',
            'description',
            'hashtag',
            'longitude',
            'latitude',
            'settings',
            'tags',
            'media'
        );

        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            throw new NotFoundException('Not found campaign with id :' . $id);
        }

        if ($this->user->cannot('manage', $campaign)) {
            throw new Exception('Policy fail');
        }

        return $this->doAction(function () use ($data, $campaign) {
            $this->compacts['campaign'] = $this->campaignRepository->update($campaign, $data);
        });
    }
    /**
     * show campaign the first.
     *
     * @param  array  $data
     * @return $campaign
    */
    public function show($id)
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            throw new NotFoundException('Not foud campaign with: ' . $id, NOT_FOUND);
        }

        if ($this->user->cant('view', $campaign)) {
            throw new UnknowException('You do not have authorize to delete this campaign', UNAUTHORIZED);
        }

        return $this->getData(function () use ($campaign) {
            $this->compacts['events'] = $this->paginateData(
                $this->eventRepository->getEvent($campaign->events())
            );

            $this->compacts['campaignTimeline'] = $this->campaignRepository->getCampaignTimeline($campaign);
            $this->compacts['campaign'] = $campaign;
        });
    }

    /**
     * show list user.
     *
     * @param  array  $data
     * @return $campaign
    */
    public function getListUser($id)
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            throw new NotFoundException('Not foud campaign with: ' . $id, NOT_FOUND);
        }

        if ($this->user->cannot('view', $campaign)) {
            throw new UnknowException('You do not have authorize to delete this campaign', UNAUTHORIZED);
        }

        return $this->getData(function () use ($campaign) {
            $this->compacts['user'] = $this->campaignRepository->getListUser($campaign);
        });
    }

    /**
     * show campaign timeline.
     *
     * @param  array  $data
     * @return $campaign
    */
    public function getCampaignTimeline($id)
    {
        $campaign = $this->campaignRepository->find($id);

        if (!$campaign) {
            throw new NotFoundException('Not foud campaign with: ' . $id, NOT_FOUND);
        }

        if ($this->user->cannot('view', $campaign)) {
            throw new UnknowException('You do not have authorize to delete this campaign', UNAUTHORIZED);
        }

        return $this->getData(function () use ($campaign) {
            $this->compacts['event'] = $this->paginateData(
                $this->eventRepository->getEventFromCampaign($campaign->events())
            );
        });
    }
}

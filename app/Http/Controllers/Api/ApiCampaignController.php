<?php

namespace App\Http\Controllers\Api;

use App\Repositories\Contracts\CampaignInterface;
use App\Repositories\Contracts\RoleInterface;
use App\Repositories\Contracts\TagInterface;
use App\Exceptions\Api\NotFoundException;
use App\Http\Requests\CreateCampaignRequest;
use App\Models\Role;

class ApiCampaignController extends ApiController
{
    private $roleRepository;

    private $tagRepository;

    public function __construct(
        CampaignInterface $campaignRepository,
        RoleInterface $roleRepository,
        TagInterface $tagRepository
    ) {
        parent::__construct($campaignRepository);
        $this->roleRepository = $roleRepository;
        $this->tagRepository = $tagRepository;
    }

    protected function create(CreateCampaignRequest $request)
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

        $data['role_id'] = $this->roleRepository->findRoleId(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN);

        if ($data['role_id']->isEmpty()) {
            throw new NotFoundException('Not found role when create campaign');
        }

        $data['user_id'] = auth()->id();
        $data['role_id'] = $data['role_id']->first()->id;
        $data['tags'] = $this->tagRepository->getOrCreate($data['tags']);

        return $this->doAction(function () use ($data) {
            $this->compacts['campaign'] = $this->repository->create($data);
        });
    }
}

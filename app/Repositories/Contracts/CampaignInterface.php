<?php

namespace App\Repositories\Contracts;

interface CampaignInterface extends RepositoryInterface
{
    public function create($inputs);
}

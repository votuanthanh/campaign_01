<?php

namespace App\Repositories\Contracts;

interface ActivityInterface extends RepositoryInterface
{
    public function getNewsFeed($campaignIds, $eventIds);
}

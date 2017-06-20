<?php

namespace App\Repositories\Contracts;

interface SocialAccountInterface extends RepositoryInterface
{
    public function getAccount($providerId, $providerName);
}

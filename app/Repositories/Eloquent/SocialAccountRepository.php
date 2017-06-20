<?php

namespace App\Repositories\Eloquent;

use App\Models\SocialAccount;
use App\Repositories\Contracts\SocialAccountInterface;
use App\Exceptions\Api\NotFoundException;

class SocialAccountRepository extends BaseRepository implements SocialAccountInterface
{
    public function model()
    {
        return SocialAccount::class;
    }

    public function getAccount($providerId, $providerName)
    {
        if (!$providerId || !$providerName) {
            throw new NotFoundException();
        }

        return $this->where('provider_id', $providerId)
            ->where('provider', $providerName)
            ->first();
    }
}

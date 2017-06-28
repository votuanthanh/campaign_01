<?php

namespace App\Repositories\Eloquent;

use App\Models\DonationType;
use App\Repositories\Contracts\DonationTypeInterface;

class DonationTypeRepository extends BaseRepository implements DonationTypeInterface
{
    public function model()
    {
        return DonationType::class;
    }
}
